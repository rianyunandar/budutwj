<?php

//membaca lokasi Controller dalam Folder
namespace App\Http\Controllers\Siswa;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; //menjalankan printah Hash Enkripsi
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use DataTables;
use Auth;//menjalankan printah auth
use App\User_siswa;
use App\Master_sekolah;
use App\Master_jurusan;
use App\Master_rombel;
use App\Master_kelas;
use App\Profile_siswa;
use App\Anggota_rombel;
use App\Master_wali_kelas;
use App\Log_aksi_user;
use App\Master_mapel_jadwal;
use App\Master_mapel_jadwal_rombel;

use App\El_tugas;
use App\El_tugas_anggota_rombel;

class CsiswaTugas extends Controller
{
	public function __construct()
  {
    $this->middleware('auth:siswa');
	}
	
  //menampilkan jadwal mapel pada list siswa
  function getJadwalMapelBy()
  {
    
  	$data = new Master_mapel_jadwal();
    return $data->getJadwalTugas(getKodeRombel(),getKodeKelas());
  }

  function offkanTugas($data){
    //ofkan tugas yang sudah lewat batas tampil sesuai tanggal
    
    // foreach($data as $datas){
    //   //tugasTampil
    //   if(!empty($datas->el_tugas->tugasId)){
    //     $cek = El_tugas::find($datas->el_tugas->tugasId);
    //     $cek->tugasTampil = 0;
    //     $cek->save();
    //     Cache::forget('list_tugas_siswa'.$datas->tgsarRblKode);
    //   }
      
    // }
      $cek = El_tugas_anggota_rombel::find($data);
      $cek->tgsarTampilSiswa = 0;
      $cek->save();

  }
  //tampilkan materi berdasarkan kode mapel, kelas dan rombel
  function getTugasBy()
  {
    
    $data = new El_tugas_anggota_rombel();
    $retrun =  $data->getTugasBy(getKodeRombel());
    //looping list tugas berdasarkan rombel
    $datatugas = [];
    $datatugasOff = [];
    foreach ($retrun as $data){
      //filter data yang bersetatus tampil
      if($data->el_tugas->tugasTampil == 1){
        $tgl_terbit = $data->tgsarTerbit;
        $tgl_berakhir = $data->tgsarBatasTerbit;
        //------------------------------------------------------
        $ambilTglSekarang = date('Y-m-d');
        $ambilTglMulai = date('Y-m-d',strtotime($tgl_terbit));
        $ambilTglAkhir = date('Y-m-d',strtotime($tgl_berakhir));
											
        $bandingTglSekarang = strtotime($ambilTglSekarang);
        $bandingTglMulai = strtotime($ambilTglMulai);
        $bandingTglAkhir = strtotime($ambilTglAkhir);
        //------------------------------------------------------
        
        //filter data tugas yang tanggal sekarang tampil
        if($bandingTglSekarang >= $bandingTglMulai AND $bandingTglSekarang <= $bandingTglAkhir){
          $datatugas[] = $data;
        }
        else{
          $this->offkanTugas($data->tgsarId);
        }
      
      }
    
    }//end forace
    
    return $datatugas;

  }
  
  
//----------------------------------------------------------------------------
	public function ListMapelTugas()
	{
    // dd($this->getJadwalMapelBy());
    
		$params = [
      'title' =>'List Tugas',
      'label' =>'LIST TUGAS',
      //'dataJadwal' =>$this->getJadwalMapelBy(),
      'dataTugas' =>$this->getTugasBy(),
      
    ];
    
    return view('siswa/tugas/list_mapel_tugas')->with($params);
  }
  
  public function BacaTugasSiswa($id){
    $idd = decrypt_url($id);
    $data = new El_tugas();
    
    $params = [
      'title' =>'Tugas Siswa',
      'label' =>'TUGAS SISWA',
      'getData' => $data->getTugasById($idd),
      'tombol'  =>'TUGAS',
    ];
    return view('siswa/tugas/baca_tugas_siswa')->with($params);

  }
	

	
}
