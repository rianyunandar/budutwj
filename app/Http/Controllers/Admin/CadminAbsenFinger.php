<?php

//membaca lokasi Controller dalam Folder
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash; //menjalankan printah Hash Enkripsi
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Auth;//menjalankan printah auth
use DB;
use Excel;
use DataTables;
use App\Master_sekolah;
use App\Master_rombel;
use App\Master_jurusan;
use App\Absen_finger_siswa;
use App\Absen_kategori;

use App\Imports\ImportAbsenFingerSiswa;



class CadminAbsenFinger extends Controller
{
	public function __construct()
  {
    $this->middleware('auth:admin');
  }

  function getSkl()
  {
  	$idskl = Auth::user()->admSklId; 
  	return $idskl;
  }
  // panggil fungsi pada model
  function getSekolah()
  {
  	$data = new Master_sekolah();
    return $data->getSekolah($this->getSkl());
  }
 
  function getRombel()
  {
    $data = new Master_rombel();
    return $data->getRombel($this->getSkl());
  }
  function getJurusan()
  {
    $jurusan = new Master_jurusan();
    return $jurusan->getJurusan($this->getSkl());
  }
  function getBulanTahunAbsen(){
    if (Cache::has('ta_asben'.$this->getSkl())){ $data = Cache::get('ta_asben'.$this->getSkl()); }
    else{ 
      $data = Absen_finger_siswa::distinct()->select(DB::raw('MONTH(afsDatetime) AS bulan,YEAR(afsDatetime) AS tahun'))->get();
      $cek = Cache::put('ta_asben'.$this->getSkl(), $data, ChaceJam());
    }
    return $data;
    
  }
  function getKategoriAbsen(){
    if (Cache::has('ktg_absen'.$this->getSkl())){ $data = Cache::get('ktg_absen'.$this->getSkl()); }
    else{ 
      $data = Absen_kategori::All();
      $cek = Cache::put('ktg_absen'.$this->getSkl(), $data, ChaceJam());
    }
    return $data;
    
  }
  
//---------------------------------------------------------------------------------------------------------
  function add()
  {
    $params = [
      'title' =>'Tambah Absen Finger',
      'label' =>'<b>TAMBAH ABSEN FINGER</b> ',
      'getSekolah' => $this->getSekolah(),
      'getKategoriAbsen' => $this->getKategoriAbsen(),
    ];
    return view('crew/absen_finger_siswa/add_absen')->with($params);
  }
 
	public function FormImportAbsen()
	{
		
		$params = [
			'title'	=>'Import Absen Siswa',
			'label'	=>'<b>FORM Import Absen Finger Siswa </b>',
		];
		return view('crew/absen_finger_siswa/form_upload')->with($params);
	}
  public function ImportAbsenFingerSiswa(Request $request) {
    
    $file = $request->file('absen_finger');
    $extensi = $file->getClientOriginalExtension();

    if($extensi == 'csv'){
      $import = new ImportAbsenFingerSiswa();
      Excel::import($import,$file,\Maatwebsite\Excel\Excel::CSV);
      
      $insert = get_object_vars($import);
      if($insert['hasil'] ==1){
        $response = ['save'=>'Berhasil Upload Absensi Finger '.$insert['jm'].' Siswa'];
      }
      elseif ($insert['hasil'] == 0) {
        $response = ['error'=>'Gagal Upload Absensi Finger Siswa'];
      }
      elseif ($insert['hasil'] == 2) {
        $response = ['save'=>'Semua Abses Siswa Pada Tanggal Hari ini Sudah Ada'];
      }
      else{
        $response = ['errorr'=>'Sistem Error'];
      }

    }
    else{
      $response = ['errorr'=>'Cek File, Pastikan Ber extensi atau Format CSV'];
    }
    return redirect('crew/form-import-absen-finger')->with($response);    
  }
  public function LihatAbsenFinger()
  {
    $params = [
      'title' =>'Absen Finger Siswa',
      'label' =>'DATA ABSENS FINGER SISWA ',
      'getSekolah' => $this->getSekolah(),
      'getRombel' => $this->getRombel(),
      'getBulanTahunAbsen' => $this->getBulanTahunAbsen(),

    ];
    return view('crew/absen_finger_siswa/view_absen')->with($params);
  }
  function jsonAbsenFinger(Request $request)
  {
    $skl = decrypt_url($request->input('skl'));
    $rbl = decrypt_url($request->input('amp;rbl'));
    $thn = decrypt_url($request->input('amp;thn'));
    $bln = decrypt_url($request->input('amp;bln'));

      if(empty($skl)){
        $data=[];
        $dt= DataTables::of($data);
      }
      else{
        if (Cache::has('absen_siswa_finger'.$skl.$rbl)){ $data = Cache::get('absen_siswa_finger'.$skl.$rbl); }
        else{
          $data = Absen_finger_siswa::with(
            'master_sekolah',
            'master_jurusan',
            'master_rombel',
            'absen_kategori',
            'user_siswa'
          )
          ->where('afsSklId',$skl)
          ->where('afsRblId',$rbl)
          ->whereYear('afsDatetime', $thn)
          ->whereMonth('afsDatetime', $bln)
          ->get();
          $chace = Cache::put('absen_siswa_finger'.$skl.$rbl, $data, ChaceJam()); 
        }


        $dt= DataTables::of($data)
        ->addColumn('no','')
        ->addColumn('namasiswa',function ($data) { 
            return $data->user_siswa->ssaFirstName.' '.$data->user_siswa->ssaLastName;
          })
        ->addColumn('username',function ($data) { 
            return $data->user_siswa->ssaUsername;
          })
        ->addColumn('jurusan',function ($data) { 
            return $data->master_jurusan->jrsSlag;
          })
        ->addColumn('sekolah',function ($data) { 
            return $data->master_sekolah->sklKode;
          })
        ->addColumn('status_absen',function ($data) { 
            //return $data->absen_kategori->akKode;
            return $data->afsAkId;
          })
        ->addColumn('namarombel',function ($data) { 
          return $data->master_rombel->master_kelas->klsKode.$data->master_rombel->rblNama;
        })
        ->addColumn('aksi',function ($data) { 
        $id = Crypt::encrypt($data->afsId);
        $button = '<a href="'.$id.'/edit-absen-finger" title="Edit Data" class="btn btn-sm btn-outline bg-primary text-primary border-primary legitRipple" ><i class="icon-pencil7"></i></a> ';
        $button .='<a title="Hapus Data" id="delete" class="btn btn-sm btn-outline bg-danger text-danger border-danger legitRipple" data-id="'.$id.'"><i class="icon-trash"></i></a>';
        return $button;
        })->rawColumns(['aksi']);
      
      }
    return $dt->make(); 
  }
  function InsertAbsenFinger(Request $request){
    
    $skl = decrypt_url($request->skl);
    $siswa = Crypt::decrypt($request->siswa);
    $jrs = Crypt::decrypt($request->jrs);
    $rbl = Crypt::decrypt($request->rbl);
    $status = decrypt_url($request->status);
    $tgl = date('Y-m-d',strtotime($request->tgl));
    $data = [];
    $cekabsen = Absen_finger_siswa::where('afsSsaId',$siswa)->where('afsDatetime',$tgl)->count();
    //cek absen siswa berdasarkan tanggal
    if($cekabsen > 0){ 
      $response = ['error'=>'Siswa Dengan Tanggal Ini Sudah Ada Absensinya !!!'];
    }
    else{
      $data[] = array(
        'afsSklId'  =>$skl,
        'afsSsaId'  =>$siswa,
        'afsJrsId'  =>$jrs,
        'afsRblId'  =>$rbl,
        'afsAkId'   =>$status,
        'afsDatetime' =>$tgl,
        'afsJenis' =>2,
        'afsIn'     =>JamAbsenIn($status), //cek di Helper
        'afsOut'    =>JamAbsenOut($status),
        'afsCreatedBy' =>Auth::user()->admId,
      );
 
      $insert = Absen_finger_siswa::insert($data);
      if($insert){ 
        Cache::forget('absen_siswa_finger'.$skl.$rbl);
        $response = ['save'=>'Berhasil Tambah Absensi']; 
      }
      else{ $response = ['error'=>'Opsss Gagal !!!'];}
    }
    return response()->json($response,200);
  }

  public function RekapFpbulan()
  {
    # code...
  }
 
  
}
