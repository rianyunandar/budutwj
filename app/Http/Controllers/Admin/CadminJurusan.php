<?php
/*
  Nama : @mryes
  FB   : https://web.facebook.com/youngkq/
  Page : Controler Jurusan
*/

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
use App\Master_jurusan;

class CadminJurusan extends Controller
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
  
	public function lihatJurusan()
	{
		
		$params = [
			'title'	=>'Data Jurusan',
			'label'	=>'<b>DAFTAR DATA JURUSAN</b> ',
			'getSekolah' => $this->getSekolah(),
		];
		return view('crew/jurusan/viewjrs')->with($params);
	}
  public function editJurusan($id)
  {
    $idd = Crypt::decrypt($id);
    $data = Master_jurusan::find($idd);
    $params = [
      'title' =>'Edit Jurusan',
      'label' =>'FORM EDIT SISWA ',
      'getSekolah' => $this->getSekolah(),
      'idjurusan' =>$id,
      'datajrs' =>$data,
    ];
    return view('crew/jurusan/editjrs')->with($params);
  }
  public function jsonJurusan()
  {
    if (Cache::has('jurusan_json')){ $data = Cache::get('jurusan_json'); }//->with('master_sekolah')
    else{
      if(empty($this->getSkl())){ 
        $data = Master_jurusan::with('master_sekolah')->get();
      }else{
        $data = Master_jurusan::where('jrsSklId',$this->getSkl())->with('master_sekolah')->get();
      }
      
      $chace = Cache::put('jurusan_json', $data, ChaceJam()); 
    }

    $dt= DataTables::of($data)
    ->addColumn('no','')
    ->addColumn('aksi',function ($data) { 
      $id = Crypt::encrypt($data->jrsId);
      if(AksiUpdate()){
        $button = '<a href="'.$id.'/edit-jurusan" title="Edit Data" class="dropdown-item btn btn-sm btn-outline bg-primary text-primary border-primary legitRipple" ><i class="icon-pencil7"> Edit</i></a>';
      }else { $button = '<a title="No Akses" class="dropdown-item btn btn-sm btn-outline bg-danger text-danger border-danger legitRipple" ><i class="icon-cancel-circle2"> No Akses</i></a>'; }
      if(AksiDelete()){
        $button .='<a title="Hapus Data" id="deletejurusan" class="dropdown-item btn btn-sm btn-outline bg-danger text-danger border-danger legitRipple" data-id="'.$id.'"><i class="icon-trash"> Hapus</i></a>';
      }
      $return  = '<ul class="list-inline list-inline-condensed mb-0 mt-2 mt-sm-0">
        <li class="list-inline-item dropdown">
          <a href="#" class="text-default dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>

          <div class="dropdown-menu dropdown-menu-right">
            '.$button.'
          </div>
        </li>
      </ul>';
      
      return $return;
      
    })->rawColumns(['aksi']);
    return $dt->make(true);  
  }
  function add()
  {
    $params = [
      'title' =>'Tambah Jurusan',
      'label' =>'<b>TAMBAH JURUSAN</b> ',
      'getSekolah' => $this->getSekolah(),
    ];
    return view('crew/jurusan/addjrs')->with($params);
  }
  function insertJurusan(Request $request)
  {
    $jrs = new Master_jurusan();
    $jrs->jrsSlag = $request->slagjrs;
    $jrs->jrsKode = $request->jrsKode;
    $jrs->jrsNama = $request->namajrs;
    $jrs->jrsSklId = $request->skl;
    if($jrs->save()){
      Cache::forget('master_jurusan');
      Cache::forget('jurusan_json');
      $response = ['save'=>'Berhasil Tambah Jurusan'];
    }
    else{
      $response = ['error'=>'Gagal Tambah Jurusan']; 
    }
    return response()->json($response,200);
  }
  function deleteJurusan($id)
  {$idd = Crypt::decrypt($id);
    $idsiswa= Master_jurusan::where('jrsId',$idd)->delete();
    if($idsiswa){
      Cache::forget('master_jurusan');
      Cache::forget('jurusan_json');
      return response()->json([
        'success' => 'Data Berhasil Di Hapus'
      ]);
    }
    else{
      return response()->json([
        'error' => 'Gagal Hapus Data!'
      ]);
    }# code...
  }
  function UpdateJurusan($id,Request $request)
  {
    $idd = Crypt::decrypt($id);
    $jrs = Master_jurusan::find($idd);
    $jrs->jrsSlag = $request->slagjrs;
    $jrs->jrsKode = $request->jrsKode;
    $jrs->jrsNama = $request->namajrs;
    $jrs->jrsSklId = $request->skl;
    if($jrs->save()){
      Cache::forget('master_jurusan');
      Cache::forget('jurusan_json');
      $response = ['save'=>'Berhasil Update Jurusan'];
    }
    else{
      $response = ['error'=>'Gagal Update Jurusan']; 
    }
    return response()->json($response,200);
  }
  
  

	
}
