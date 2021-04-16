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
use App\El_materi;
use App\El_materi_view;
use App\El_materi_anggota_rombel;

class CsiswaMateri extends Controller
{
	public function __construct()
  {
    $this->middleware('auth:siswa');
	}
	
  //menampilkan jadwal mapel pada list siswa
  function getJadwalMapelBy()
  {
    
  	$data = new Master_mapel_jadwal();
    return $data->getJadwalMapel(getKodeRombel(),getKodeKelas());
  }
  //tampilkan materi berdasarkan kode mapel, kelas dan rombel
  function getMateriBy()
  {
    $kodemapel = decrypt_url(@$_GET['mapel']);
    $data = new El_materi_anggota_rombel();
    return $data->getMateriBy(getKodeRombel(),$kodemapel,getIdKelas());
  }
  
  
//----------------------------------------------------------------------------
	public function ListJadwalMapel()
	{
    
		$params = [
      'title' =>'List Mapel',
      'label' =>'LIST MAPEL',
      'dataJadwal' =>$this->getJadwalMapelBy(),
      'datamateri' =>$this->getMateriBy(),
    ];
    
    return view('siswa/materi/list_jadwal_mapel')->with($params);
  }
  public function BacaMateriSiswa($id){
    $idd = decrypt_url($id);
    $data = new El_materi();

    $params = [
      'title' =>'Baca Materi',
      'label' =>'BACA MATERI',
      'datamateri' => $data->getMateriById($idd),
    ];
    
    //---insert view materi ------------------------------------
      $dataArray=array(
        'viewIdSiswa' => getIdSiswa(),
        'viewIdMateri' => $idd,
        'viewJenis'   =>1,
      );
      EL_materi_view::insert($dataArray);
    //---insert view materi ------------------------------------
    
    return view('siswa/materi/baca_materi')->with($params);
  }
	

	
}
