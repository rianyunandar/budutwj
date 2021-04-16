<?php

//membaca lokasi Controller dalam Folder
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Auth;//menjalankan printah auth
use DB;
use DataTables;
use App\Master_sekolah;
use App\Master_yayasan;
use App\Master_jabatan;
use App\Master_smp;
use App\Master_agama;
use App\Penghasilan;
use App\Transportasi;
use App\Tahun_ajaran;
use App\Tingkat_pendidikan;
use App\Semester;
/*
1.sekolah
2.jabatan
3.agama
4.penghasilan
5.transportasi
6.tahunajaran
7.semester
8.tingkat_pendidikan
*/


class CadminSekolah extends Controller
{
	public function __construct()
  {
    $this->middleware('auth:admin');
  }

  function getSkl() //ambil id sekolah dari data user
  {
  	$idskl = Auth::user()->admSklId; 
  	return $idskl;
  }
  function getSekolah() //ambil data sekolah
  {
    $data = new Master_sekolah();
    return $data->getSekolah($this->getSkl());
  }
  function getJabatan()
  {
    if (Cache::has('master_jabatan1')){ 
      $jabatan = Cache::get('master_jabatan1'); 
    }
    else{ 
       $jabatan = Master_jabatan::where('mjbIsActive',1)->get();
       $chace = Cache::put('master_jabatan1', $jabatan, ChaceJam());
    }
    return $jabatan;
  }
  
//1.sekolah
  public function lihatSekolah()
  {
    $params = [
      'title' =>'Data Sekolah',
      'label' =>'<b>DAFTAR DATA SEKOLAH</b> ',
    ];
    return view('crew/sekolah/viewskl')->with($params);
  }

	public function add()
	{
		$params = [
      'title' =>'Tambah Sekolah',
      'label' =>'<b>TAMBAH SEKOLAH</b> ',
      //'getSekolah' => $this->getSekolah(),
      'tingkat_pendidikan' =>Tingkat_pendidikan::All(),
      'yayasan' =>Master_yayasan::All(),
    ];
    return view('crew/sekolah/add-skl')->with($params);
		
	}
  public function edit($id)
  {
    $idd = Crypt::decrypt($id);
    $data_skl = Master_sekolah::find($idd);
    $params = [
      'title' =>'Edit Sekolah',
      'label' =>'<b>FORM EDIT DATA SEKOLAH</b>',
      'idskl' =>$id,
      'tingkat_pendidikan' =>Tingkat_pendidikan::All(),
      'yayasan' =>Master_yayasan::All(),
      'getSkl' =>$data_skl,
    ];
    return view('crew/sekolah/edit-skl')->with($params);
  }
  
	public function InsertSekolah(Request $request)
	{
    $tingkat_pendidiakn = decrypt_url($request->tk_skl);
    $status_sekolah = decrypt_url($request->stk_skl);

    $data = Master_sekolah::where('sklNpsn',$request->npsn_skl);
    if($data->count() > 0){
      $response = ['error'=>'Upss NPSN Sekolah Sudah ada'];
    }
    else{
      $data2 = Master_sekolah::where('sklKode',$request->kode_skl);
      if($data2->count() > 0){
        $response = ['error'=>'Upss KODE Sekolah Sudah ada'];
      }
      else{
        $skl = new Master_sekolah();
        $skl->sklMsysId = $status_sekolah;
        $skl->sklTkpdId = $tingkat_pendidiakn;
        $skl->sklIdDapodik = $request->dapo_skl;
        $skl->sklNpsn = $request->npsn_skl;
        $skl->sklNis = $request->nis_skl;
        $skl->sklKode = $request->kode_skl;
        $skl->sklNama = $request->nama_skl;
        $skl->sklAlamat = $request->alamat_skl;
        $skl->sklEmail = $request->email_skl;

        $skl->sklCreatedBy = Auth::user()->admId;
        if($skl->save()){ 
          Cache::flush(); //hapus semua cache
          $response = ['save'=>'Berhasil Tambah Sekolah'];
        }
        else{ $response = ['error'=>'Opsss Gagal !!!'];}
      }
    }
    return response()->json($response,200);
	}
  public function UpdateSekolah($id,Request $request)
  {
    $idd = Crypt::decrypt($id);
    $tingkat_pendidiakn = decrypt_url($request->tk_skl);
    $status_sekolah = decrypt_url($request->stk_skl);

    $skl = Master_sekolah::find($idd);
    $skl->sklMsysId = $status_sekolah;
    $skl->sklTkpdId = $tingkat_pendidiakn;
    $skl->sklIdDapodik = $request->dapo_skl;
    $skl->sklNpsn = $request->npsn_skl;
    $skl->sklNis = $request->nis_skl;
    $skl->sklKode = $request->kode_skl;
    $skl->sklNama = $request->nama_skl;
    $skl->sklAlamat = $request->alamat_skl;
    $skl->sklEmail = $request->email_skl;
    $skl->sklUpdated = date("Y-m-d h:i:s");
    $skl->sklUpdateBy = Auth::user()->admId;

    if($skl->save()){ 
      Cache::flush(); //hapus semua cache
      $response = ['save'=>'Berhasil Update Sekolah'];
    }
    else{ $response = ['error'=>'Opsss Gagal !!!'];}
    
    return response()->json($response,200);
  }

	public function jsonSekolah()
	{
    if (Cache::has('sekolah_json')){ $data = Cache::get('sekolah_json'); }//->with('master_sekolah')
    else{
      if(empty($this->getSkl())){ $data = Master_sekolah::with('master_yayasan')->with('profile_sekolah')->get(); }
      else{ $data = Master_sekolah::where('sklId',$this->getSkl())->with('master_yayasan')->with('profile_sekolah')->get(); }
      $chace = Cache::put('sekolah_json', $data, ChaceJam()); 
    }

    $dt= DataTables::of($data)
    ->addColumn('aksi',function ($data) { 
      $id = Crypt::encrypt($data->sklId);
      if(AksiUpdate()){
        $button = '<a href="'.$id.'/edit-sekolah" title="Edit Data" class="btn btn-sm btn-outline bg-primary text-primary border-primary legitRipple" ><i class="icon-pencil7"></i></a> ';
      } else{ $button =""; }
      if(AksiDelete()){
        $button .='<a title="Hapus Data" id="deletesekolah" class="btn btn-sm btn-outline bg-danger text-danger border-danger legitRipple" data-id="'.$id.'"><i class="icon-trash"></i></a>';
      } else{ $button .=""; }
      return $button;
      
    })->rawColumns(['aksi']);
    return $dt->make(true);  
	}
  
  
  public function DeleteSekolah($id)
  {
    
  }
//2.jabatan
  public function addJabatan()
  {
    $params = [
      'title' =>'Tambah Jabatan',
      'label' =>'<b>TAMBAH JABATAN</b> ',
    ];
    return view('crew/jabatan/add_jabatan')->with($params);
    
  }
  public function editJabatan($id)
  {
    $idd = Crypt::decrypt($id);
    $data_skl = Master_sekolah::find($idd);
    $params = [
      'title' =>'Edit Jabatan',
      'label' =>'<b>FORM EDIT JABATAN</b>',
      'idskl' =>$id,
    ];
    return view('crew/jabatan/edit_jabatan')->with($params);
  }
  public function LihatJabatan()
  {
    $params = [
      'title' =>'Data Jabatan',
      'label' =>'<b>DAFTAR DATA JABATAN</b> ',
      'getJabatan' => $this->getJabatan(),
    ];
    return view('crew/jabatan/view_jabatan')->with($params);
  }
  public function InsertJabatan(Request $request)
  {
   
    $data = Master_jabatan::where('mjbKode',$request->kodeJabatan);
    if($data->count() > 0){
      $response = ['error'=>'Upss Kode Jabatan Sudah Ada'];
    }
    else{
        $data = new Master_jabatan();
        $data->mjbKode = $request->kodeJabatan;
        $data->mjbNama = $request->namaJabatan;
        $data->mjbIsActive = $request->statusJabatan;

        $data->mjbCreatedBy = Auth::user()->admId;
        if($data->save()){ 
          Cache::forget('Master_jabatan1');
          $response = ['save'=>'Berhasil Tambah Jabatan'];
        }
        else{ $response = ['error'=>'Opsss Gagal !!!'];}
     
    }
    return response()->json($response,200);

  }
//3.agama
  public function LihatAgama()
  {
    $params = [
      'title' =>'Data Agama',
      'label' =>'<b>DAFTAR DATA AGAMA</b> ',
      'getAgama' => Master_agama::all(),
    ];
    return view('crew/agama/view_agama')->with($params);
  }
//4.penghasilan
  public function LihatPenghasilan()
  {
    $params = [
      'title' =>'Data Penghasilan',
      'label' =>'<b>DAFTAR DATA PENGHASILAN</b> ',
      'getPenghasilan' => Penghasilan::all(),
    ];
    return view('crew/penghasilan/view_penghasilan')->with($params);
  }
//5.transportasi
  public function LihatTransportasi()
  {
    $params = [
      'title' =>'Data Transportasi',
      'label' =>'<b>DAFTAR DATA TRANSPORTASI</b> ',
      'getTranport' => Transportasi::all(),
    ];
    return view('crew/transportasi/view_transportasi')->with($params);
  }
//6.tahunajaran
  public function LihatTahunAjaran()
  {
    $params = [
      'title' =>'Data Tahun Ajaran',
      'label' =>'<b>DAFTAR DATA TAHUN AJARAN</b> ',
      'getTa' => Tahun_ajaran::all(),
    ];
    return view('crew/tahun_ajaran/view_tahun_ajaran')->with($params);
  }
  public function InsertTahunAjaran(Request $request)
  {
   
    $data = Tahun_ajaran::where('tajrKode',$request->taKode);
    if($data->count() > 0){
      $response = [
        'status'=>'gagal',
        'error'=>'Upss Kode Tahun Ajaran Sudah Ada'
      ];
    }
    else{
        $data = new Tahun_ajaran();
        $data->tajrKode = $request->taKode;
        $data->tajrNama = $request->taNama;
        $data->tajrDescription = $request->taKtr;

        $data->tajrCreatedBy = Auth::user()->admId;
        if($data->save()){ 
          Cache::forget('Tahun_ajaran');
          $response = [
            'status'=>200,
            'success'=>'Berhasil Tambah Tahun Ajaran'];
        }
        else{ $response = [ 
          'status'=>'eror',
          'error'=>'Opsss Gagal !!!'
        ];}
     
    }
    return response()->json($response,200);

  }
  public function UpdateTahunAjaran(Request $request){
    
    
    if(empty($request->taKode)){
      $response = [
        'status'=>'gagal',
        'error'=>'Upss Kode Tahun Ajaran Tidak Boleh Kosong'
      ];
    }
    else{
      $id = decrypt_url($request->taKode2);

      $data = Tahun_ajaran::find($id);
      $data->tajrKode = $request->taKode;
      $data->tajrNama = $request->taNama;
      $data->tajrDescription = $request->taKtr;
      $data->tajrIsActive = $request->taStatus;
      $data->tajrUpdatedBy = Auth::user()->admId;

      if($data->save()){ 
        Cache::forget('Tahun_ajaran');
        $response = [
          'status'=>200,
          'success'=>'Berhasil Update Tahun Ajaran'];
        }
      else{ $response = [ 
        'status'=>'eror',
        'error'=>'Opsss Gagal !!!'
        ];
      }
     
     }
    return response()->json($response,200);

  }
//7.semester
  public function LihatSemester()
  {
    $params = [
      'title' =>'Data Semester',
      'label' =>'<b>DAFTAR DATA SEMESTER</b> ',
      'getSemester' => Semester::with('master_sekolah','tahun_ajaran')->get(),
      'getSekolah' => $this->getSekolah(),
      'getTa' => Tahun_ajaran::all(),
    ];
    return view('crew/semester/view_semester')->with($params);
  }
//8.tingkat_pendidikan
  public function LihatTingkatPendidikan()
  {
    $params = [
      'title' =>'Data Tingkat Pendidikan',
      'label' =>'<b>DAFTAR DATA TINGKAT PENDIDIKAN</b> ',
      'getTk' => Tingkat_pendidikan::all(),
    ];
    return view('crew/tingkat_pendidikan/view_tingkat_pendidikan')->with($params);
  }
//9. SMP --------------------------------------------------------------------------
 public function LihatSmp(){
  $params = [
    'title' =>'Data SMP',
    'label' =>'<b>DAFTAR DATA SMP </b> ',
    'no'  =>1,
    'getSmp' => Master_smp::all(),
  ];
  return view('crew/smp/view_smp')->with($params);
 }

}
