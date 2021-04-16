<?php

//membaca lokasi Controller dalam Folder
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash; //menjalankan printah Hash Enkripsi
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Auth;//menjalankan printah auth
use DB;
use DataTables;
use App\User_guru;
use App\Master_sekolah;
use App\Master_rombel;
use App\Master_jurusan;

class CadminKajur extends Controller
{
	public function __construct()
  {
    $this->middleware('auth:admin');
  }

  function getSkl() //ambil id skl
  {
  	$idskl = Auth::user()->admSklId; 
  	return $idskl;
  }
  // panggil fungsi pada model
  function getSekolah() //ambil data sekolah berdasarkan id skl
  {
  	$data = new Master_sekolah();
    return $data->getSekolah($this->getSkl());
  }
  function getGuru()
  {
    $data = new User_guru();
    return $data->getGuruSkl($this->getSkl());
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
 
	public function AddKajur()
	{
		
		$params = [
			'title'	=>'Tambah Ketua Jurusan',
			'label'	=>'<b>FORM TAMBAH KETUA JURUSAN </b>',
			'getSekolah' => $this->getSekolah(),
      'getJurusan' => $this->getJurusan(),
      'getGuru' => $this->getGuru(),
		];
		return view('crew/kajur/add_kajur')->with($params);
	}
  public function lihatKajur(){
    $params = [
      'title' =>'Data Ketua Jurusan',
      'label' =>'<b>FORM DATA KETUA JURUSAN</b>',
      'getJurusan' => $this->getJurusan(),
    ];
    return view('crew/kajur/view_kajur')->with($params);
  }
  function jsonKajur()
  {
    //cache --------------------------------------------
    if (Cache::has('kajur'.$this->getSkl())){ 
      $data = Cache::get('kajur'.$this->getSkl()); 
    }
    else{ 
      if(empty($this->getSkl())){ 
        $data = User_guru::whereNotNull('ugrJrsId') //cari id jurusan yang tidak kosong
        ->with('master_sekolah','master_jurusan')->get(); 
      }
      else{ 
      $data = User_guru::whereNotNull('ugrJrsId') //cari id jurusan yang tidak kosong
      ->with('master_sekolah','master_jurusan')
      ->where('ugrSklId',$this->getSkl())
      ->get(); 
      }
      $cek = Cache::put('kajur'.$this->getSkl(), $data, ChaceJam());
    }
    //end cache ------------------------------------------
    $dt= DataTables::of($data)
    ->addColumn('namaguru',function ($data) { 
      return $nama = $data->ugrFirstName.' '.$data->ugrLastName;
    })
    ->addColumn('aksi',function ($data) { 
      $id = Crypt::encrypt($data->ugrId);
      if(AksiUpdate()){
        $button = '<a id="edit" data-id="'.$id.'" data-toggle="modal" data-target="#modal_backdrop"  title="Edit Data" class="btn btn-sm btn-outline bg-primary text-primary border-primary legitRipple" ><i class="icon-pencil7"></i></a> ';
      }else{ $button=null; }
      if(AksiDelete()){
        $button .='<a title="Hapus Data" id="delete" class="btn btn-sm btn-outline bg-danger text-danger border-danger legitRipple" data-id="'.$id.'"><i class="icon-trash"></i></a>';
      }
        return $button;
    })->rawColumns(['aksi']);
    return $dt->make(true); 
   //return $data;
  }
  function InsertKajur(Request $request)
  {
    $idskl = decrypt_url($request->skl);
    $idjrs = decrypt_url($request->jrs);
    $idguru = decrypt_url($request->guru);

    $data = User_guru::where('ugrJrsId',$idjrs)->where('ugrSklId',$idskl);
    $data2 = User_guru::whereNotNull('ugrJrsId')->where('ugrSklId',$idskl)->where('ugrId',$idguru);
    if($data->count() > 0){
      $kajur=$data->first();
      $response = ['error'=>'Upss Jurusan Sudah Memiliki Ketua Jurusan <br> Guru '.$kajur->ugrFirstName];
    }
    else if($data2->count() > 0){
      $kajur=$data2->first();
      $response = ['error'=>'Upss Guru Sudah Menjabat Ketua Jurusan <br> Guru '.$kajur->ugrFirstName];
    }
    else{
      $kajur = User_guru::find($idguru);
      $kajur->ugrJrsId = $idjrs;
      $kajur->ugrUpdated = date("Y-m-d h:i:s");
      $kajur->ugrUpdatedBy = Auth::user()->admId;
      if($kajur->save()){ 
        Cache::forget('kajur'.$this->getSkl());
        $response = ['save'=>'Berhasil Tambah Ketua Jurusan'];
      }
      else{ $response = ['error'=>'Opsss Gagal !!!'];}
    }
    return response()->json($response,200);
  }
  function UpdateKajur(Request $request){
    $idd = Crypt::decrypt($request->id);
    $jrs = decrypt_url($request->jrs);
    $guru= User_guru::find($idd);
    $guru->ugrJrsId = $jrs;
    $guru->ugrUpdated = date("Y-m-d h:i:s");
    $guru->ugrUpdatedBy = Auth::user()->admId;
    if($guru->save()){
       Cache::forget('kajur'.$this->getSkl());
      return response()->json([
        'success' => 'Data Berhasil Di Update'
      ]);
    }
    else{
      return response()->json([
        'error' => 'Gagal Update Data!'
      ]);
    }
  }
  function DeleteKajur($id){
    $idd = Crypt::decrypt($id);
    $guru= User_guru::find($idd);
    $guru->ugrJrsId = null;
    $guru->ugrUpdated = date("Y-m-d h:i:s");
    $guru->ugrUpdatedBy = Auth::user()->admId;
    if($guru->save()){
       Cache::forget('kajur'.$this->getSkl());
      return response()->json([
        'success' => 'Data Berhasil Di Hapus'
      ]);
    }
    else{
      return response()->json([
        'error' => 'Gagal Hapus Data!'
      ]);
    }
  }
  
}
