<?php
/*
  Nama : @mryes
  FB   : https://web.facebook.com/youngkq/
  Page : Controler Wali Kelas
*/
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
use App\Master_wali_kelas;


class CadminWaliKelas extends Controller
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
  
//------------------------------------------------------------------
	public function AddWaliKelas()
	{
		
		$params = [
			'title'	=>'Tambah Wali Kelas',
			'label'	=>'<b>FORM TAMBAH WALI KELAS </b>',
			'getSekolah' => $this->getSekolah(),
      'getRombel' => $this->getRombel(),
      'getJurusan' => $this->getJurusan(),
      'getGuru' => $this->getGuru(),
		];
		return view('crew/wali-kelas/add_wali_kelas')->with($params);
	}
  public function lihatWaliKelas(){
    $params = [
      'title' =>'Data Wali Kelas',
      'label' =>'<b>FORM DATA WALI KELAS </b>',
    ];
    return view('crew/wali-kelas/view_wali_kelas')->with($params);
  }
  public function editWakas($id){
    $idd = Crypt::decrypt($id);
    $data = Master_wali_kelas::find($idd);
    $params = [
      'title' =>'Edit Wali Kelas',
      'label' =>'<b>FORM EDIT WALI KELAS </b>',
      'getSekolah' => $this->getSekolah(),
      'getRombel' => $this->getRombel(),
      'getJurusan' => $this->getJurusan(),
      'getGuru' => $this->getGuru(),
      'idwakas' =>$id,
      'datawakas' =>$data,
    ];
    return view('crew/wali-kelas/edit-wakas')->with($params);
  }
//--------------------------------------------------------------
  function jsonWaliKelas()
  {
    //Cache Redis ------------------------------------------------------------------------------------
    if (Cache::has('wakas'.$this->getSkl())){ $data = Cache::get('wakas'.$this->getSkl()); }
    else{
      if(empty($this->getSkl())){ 
        $data = Master_wali_kelas::with('master_sekolah','master_jurusan','master_rombel','user_guru')->get();
      }
      else{
        $data = Master_wali_kelas::where('wakasSklId',$this->getSkl())
        ->with('master_sekolah','master_jurusan','master_rombel','user_guru')
        ->get();
      }
      $chace = Cache::put('wakas'.$this->getSkl(), $data, ChaceJam()); 
    }
    //Cache Redis ------------------------------------------------------------------------------------
    
    $dt= DataTables::of($data)
    ->addColumn('wakas',function ($data) { 
      return $wakas = $data->master_rombel->master_kelas->klsKode.$data->master_rombel->rblNama;
    })
    ->addColumn('namaguru',function ($data) { 
      if(empty($data->user_guru->ugrFirstName)){
         return $nama ="Belum ada Wali Kelas";
      }
      else{
        return $nama = $data->user_guru->ugrFirstName.' '.$data->user_guru->ugrLastName; 
      }

    })
    ->addColumn('aksi',function ($data) { 
      $id = Crypt::encrypt($data->wakasId);
      if(AksiUpdate()){
      $button = '<a href="'.$id.'/edit-wakas" title="Edit Data"  class="dropdown-item btn btn-sm btn-outline bg-primary text-primary border-primary legitRipple"><i class="icon-pencil7"> Edit</i></a> ';
      }else{ $button = '<a title="No Akses" class="dropdown-item btn btn-sm btn-outline bg-danger text-danger border-danger legitRipple" ><i class="icon-cancel-circle2"> No Akses</i></a>';  }
      if(AksiDelete()){
        $button .='<a title="Hapus Data" id="delete"  data-id="'.$id.'" class="dropdown-item btn-sm btn-outline bg-danger text-danger border-danger legitRipple"><i class="icon-trash"> Hapus</i></a>';
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
  function InsertWakas(Request $request)
  {
    $idskl = decrypt_url($request->skl);
    $idjrs = decrypt_url($request->jrs);
    $idrbl = decrypt_url($request->rbl);
    $idguru = decrypt_url($request->guru);

    $data = Master_wali_kelas::where('wakasUgrId',$idguru)->orWhere('wakasRblId',$idrbl);
    if($data->count() > 0){
      $cekwakas=$data->first();
      $response = ['error'=>'Upss Wali Kelas Sudah Ada <br> Guru '.$cekwakas->user_guru->ugrFirstName.' <br> Rombel '.$cekwakas->master_rombel->master_kelas->klsKode.' '.$cekwakas->master_rombel->rblNama.''];
    }
    else{
      $wakas =  new Master_wali_kelas();
      $wakas->wakasSklId = $idskl;
      $wakas->wakasJrsId = $idjrs;
      $wakas->wakasRblId = $idrbl;
      $wakas->wakasUgrId = $idguru;
      $wakas->wakasCreatedBy = Auth::user()->admId;

      $rombel = Master_rombel::find($idrbl);
      $rombel->rblUgrId = $idguru;
      $rombel->rblUpdated = date("Y-m-d h:i:s");
      $rombel->rblUpdatedBy = Auth::user()->admId;

      if($wakas->save()){ 
        if($rombel->save()){
          //hapus cacche
          Cache::forget('wakas'.$this->getSkl());
          Cache::forget('anggota.rombel'.$idrbl);
          $response = ['save'=>'Berhasil Tambah Wali Kelas'];
        }
        else{
          $response = ['error'=>'Opsss Gagal Tambah Guru ke Rombel !!!'];
        }
        
      }
      else{ $response = ['error'=>'Opsss Gagal !!!'];}
    }
    return response()->json($response,200);
  }
  function UpdateWakas($id, Request $request){
    $idd = Crypt::decrypt($id);
    $idskl = decrypt_url($request->skl);
    $idjrs = decrypt_url($request->jrs);
    $idrbl = decrypt_url($request->rbl);
    $idguru = decrypt_url($request->guru);
    
    $data = Master_wali_kelas::find($idd);
    $data->wakasSklId = $idskl;
    $data->wakasJrsId = $idjrs;
    $data->wakasRblId = $idrbl;
    $data->wakasUgrId = $idguru;
    $data->wakasCreatedBy = Auth::user()->admId;

    $rombel = Master_rombel::find($idrbl);
    $rombel->rblUgrId = $idguru;
    $rombel->rblUpdated = date("Y-m-d h:i:s");
    $rombel->rblUpdatedBy = Auth::user()->admId;


    if($data->save()){ 
      if($rombel->save()){
        Cache::forget('wakas'.$this->getSkl());
        $response = ['save'=>'Berhasil Update Wali Kelas'];
      }
      else{
        $response = ['error'=>'Opsss Gagal Tambah Guru ke Rombel !!!'];
      }
    }
    else{ $response = ['error'=>'Opsss Gagal Update !!!'];}
    
    return response()->json($response,200);
  }
  function DeleteWakas($id){
    $idd = Crypt::decrypt($id);
    $data = Master_wali_kelas::findOrFail($idd);
    $idguru = $data->wakasUgrId;
      $rombel = Master_rombel::where('rblUgrId',$idguru)->first();
      $rombel->rblUgrId = null;
      $rombel->rblUpdated = date("Y-m-d h:i:s");
      $rombel->rblUpdatedBy = Auth::user()->admId;

      
    
    if($data->delete()){
      //update null wali kelas pada master rombel
      
      $rombel->save();

       Cache::forget('wakas'.$this->getSkl());
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
