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
use App\Master_jurusan;
use App\Master_kelas;
use App\Master_rombel;
use App\Anggota_rombel;
use App\User_siswa;

class CadminRombel extends Controller
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
  function getLevelKelas()
  {
    if (Cache::has('master_kelas')){ $data = Cache::get('master_kelas'); }
    else{ 
      $data = Master_kelas::all();
      Cache::put('master_kelas', $data, ChaceJam());
    }
    return $data;
  }
  function getJurusan()
  {
    $jurusan = new Master_jurusan();
    return $jurusan->getJurusan($this->getSkl());
  }
  function getRombel()
  {
    $rombel = new Master_rombel();
    return $rombel->getRombel($this->getSkl());
  }

  function cekSiswaRombelKosong()
  {
    if(empty($this->getSkl())){
      $siswa = User_siswa::where('ssaIsActive',1)->with('Master_jurusan')->with('Master_sekolah')->get();
    }
    else{
      $siswa = User_siswa::where('ssaIsActive',1)->where('ssaSklId',$this->getSkl())->with('Master_jurusan')->with('Master_sekolah')->get();
    } 
    
    foreach ($siswa as $cek) {
      $cekrombel = Anggota_rombel::where('arbSsaId',$cek->ssaUsername)->count();
      if($cekrombel > 0){ 
        $data=null;
      }
      else{
        $data[] = $cek;
      }

    }
    return $data;
  }
//---------------------------------------------------------------------------------------
	public function lihatRombel()
	{
		
		$params = [
			'title'	=>'Data Rombel',
			'label'	=>'<b>DAFTAR DATA ROMBEL</b> ',
			'getSekolah' => $this->getSekolah(),
		];
		return view('crew/rombel/viewrombel')->with($params);
	}
  public function lihatAnggotaRombel()
  {
    
    $params = [
      'title' =>'Data Anggota Rombel',
      'label' =>'<b>DAFTAR DATA ANGGOTA ROMBEL</b> ',
      'getSekolah' => $this->getSekolah(),
      'getRombel' => $this->getRombel(),
    ];
    return view('crew/rombel/view_anggota_rombel')->with($params);
  }
  public function addAnggotaRombel()
  {
    $params = [
      'title' =>'Tambah Anggota Romel',
      'label' =>'<b>TAMBAH ANGGOTA ROMBEL</b> ',
      'getSekolah' => $this->getSekolah(),
      'getJurusan' => $this->getJurusan(),
      'getRombel' => $this->getRombel(),
      'siswaNoRombel' => $this->cekSiswaRombelKosong(),
    ];
    return view('crew/rombel/add_anggota_rombel')->with($params);
  }
 
  
  function addRombel()
  {
    $params = [
      'title' =>'Tambah Rombel',
      'label' =>'<b>TAMBAH ROMBEL</b> ',
      'getSekolah' => $this->getSekolah(),
      'getKelas' => $this->getLevelKelas(),
      'getJurusan' => $this->getJurusan(),
    ];
    return view('crew/rombel/addrombel')->with($params);
  }
  function editRombel($id){
    $idd = Crypt::decrypt($id);
    $data = Master_rombel::find($idd);
    $params = [
      'title' =>'Edit Rombel',
      'label' =>'<b>EDIT ROMBEL</b> ',
      'getSekolah' => $this->getSekolah(),
      'getKelas' => $this->getLevelKelas(),
      'getJurusan' => $this->getJurusan(),
      'getRombel' => $data,
      'idrombel'=>$id
    ];
    return view('crew/rombel/editrombel')->with($params);
  }
//---------------------------------------------------------------------------------------
  function InsertRombel(Request $request)
  {
    $pesan =[
        'rblKode.required' =>'Kode Romel Tidak Boleh Kosong',
        'unique' => 'Kode Rombel Sudah Terdaftar Di Database',
      ];

      $validator = Validator::make(request()->all(), [
        'rblKode' => 'required|unique:master_rombel',
      ],$pesan);

    if($validator->fails()) {
      $response = $validator->messages();
    }
    else{ 
      $rbl = new Master_rombel();
      $rbl->rblSklId = $request->skl;
      $rbl->rblJrsId = $request->jrs;
      $rbl->rblKlsId = $request->kls;
      $rbl->rblKode = $request->rblKode;
      $rbl->rblNama = $request->namarombel;
      $rbl->rblCreatedBy = Auth::user()->admId;

      if($rbl->save()){
        Cache::forget('master_rombel'.$this->getSkl());
        Cache::forget('rombel_json'.$this->getSkl());
        $response = ['save'=>'Berhasil Tambah Rombel'];
      }
      else{
        $response = ['error'=>'Gagal Tambah Rombel']; 
      }
    }
    return response()->json($response,200);
  }
  function UpdateRombel($id, Request $request){
    $idd = Crypt::decrypt($id);
    $rbl = Master_rombel::find($idd);
    $rbl->rblSklId = decrypt_url($request->skl);
    $rbl->rblJrsId = decrypt_url($request->jrs);
    $rbl->rblKlsId = decrypt_url($request->kls);
    $rbl->rblKode = $request->rblKode;
    $rbl->rblNama = $request->namarombel;
    $rbl->rblUpdated = date("Y-m-d h:i:s");
    $rbl->rblUpdatedBy = Auth::user()->admId;

    if($rbl->save()){
      Cache::forget('master_rombel'.$this->getSkl());
      Cache::forget('rombel_json'.$this->getSkl());
      $response = ['save'=>'Berhasil Update Rombel'];
    }
    else{
      $response = ['error'=>'Gagal Update Rombel']; 
    }
    return response()->json($response,200);
  }
  function InsertAnggotaRombel(Request $request)
  {
    $jml = count($request->id);
    
    for ($i=0; $i <$jml ; $i++) { 
      $data=array(
        'arbSklId'=> $request->skl,
        'arbJrsId'=> $request->jrs,
        'arbRblId'=> $request->rbl,
        'arbSsaId'=> $request->id[$i]
      );
      $siswa = User_siswa::where('ssaUsername',$request->id[$i])->first();
      $siswa->ssaRblId =$request->rbl;
      $siswa->save();

       $datarombel[]=$data;
    }
    // dd($datarombel);
    $insert = Anggota_rombel::insert($datarombel);
    if( $insert){
        Cache::forget('master_rombel'.$this->getSkl());
        Cache::forget('rombel_json'.$this->getSkl());
        $response = ['save'=>'Berhasil Tambah Rombel'];
      }
      else{
        $response = ['error'=>'Gagal Tambah Rombel']; 
      }
    return response()->json($response,200);
  }
 
  public function jsonRombel()
  {
    if (Cache::has('rombel_json'.$this->getSkl())){ $data = Cache::get('rombel_json'.$this->getSkl()); }//->with('master_sekolah')
    else{
      if(empty($this->getSkl())){ 
        $data = Master_rombel::with('master_sekolah')
        ->with('master_kelas')
        ->with('master_jurusan')
        //->with('user_guru')
        ->with('master_wali_kelas')
        ->get();
      }else{
        $data = Master_rombel::where('rblSklId',$this->getSkl())
        ->with('master_sekolah')
        ->with('master_kelas')
        ->with('master_jurusan')
        //->with('user_guru')
        ->with('master_wali_kelas')
        ->get();
      }
      
      $chace = Cache::put('rombel_json'.$this->getSkl(), $data, ChaceMenit()); 
    }
    $dt= DataTables::of($data)
    ->addColumn('no','')
    ->addColumn('walikelas',function ($data) { 
      if(empty($data->master_wali_kelas->wakasUgrId)){
         return $nama ="kosong";
      }
      else{
        $id = Crypt::encrypt($data->master_wali_kelas->user_guru->ugrId);
        $button ='<a href="'.$id.'/edit-guru">'.$data->master_wali_kelas->user_guru->ugrFirstName.' '.$data->master_wali_kelas->user_guru->ugrLastName.'</a';
        return $button;
      }
      
    })
    ->addColumn('aksi',function ($data) { 
      $id = Crypt::encrypt($data->rblId);
      if(AksiUpdate()){
      $button = '<a href="'.$id.'/edit-rombel" title="Edit Data" class="dropdown-item btn btn-sm btn-outline bg-primary text-primary border-primary legitRipple" ><i class="icon-pencil7"> Edit</i></a>';
      }else{ $button = '<a title="No Akses" class="dropdown-item btn btn-sm btn-outline bg-danger text-danger border-danger legitRipple" ><i class="icon-cancel-circle2"> No Akses</i></a>';  }
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
      
    })
    ->addColumn('nama_rombel',function ($data) { 
      return $data->master_kelas->klsKode.' '.$data->rblNama;
      
    })
    ->rawColumns(['aksi','walikelas']);
    return $dt->make(true);  
  }
  public function jsonAnggotaRombel(Request $request)
  {
    $rbl = decrypt_url($request->input('rbl'));
    if(empty($rbl)){
      $data=[];
      $dt= DataTables::of($data);
    }
    else{
     
        if(empty($this->getSkl())){  
          $data = Anggota_rombel::where('arbRblId',$rbl)
              ->with('master_sekolah')
              ->with('master_rombel')
              ->with('user_siswa')
              ->with('master_jurusan')
              ->with('master_kelas')
              ->get();
        }
        else{ 
          $data = Anggota_rombel::where('arbSklId',$this->getSkl())
              ->where('arbRblId',$rbl)
              ->with('master_sekolah')
              ->with('master_rombel')
              ->with('user_siswa')
              ->with('master_jurusan')
              ->with('master_kelas')
              ->get();
        }
     
      $dt= DataTables::of($data)
      ->addColumn('no','')
      ->addColumn('namasiswa',function ($data) { 
        return $data->user_siswa->ssaFirstName.' '.$data->user_siswa->ssaLastName;
      })
      ->addColumn('namarombel',function ($data) { 
        return $data->master_rombel->master_kelas->klsNama.$data->master_rombel->rblNama;
      });
    }
    return $dt->make(true);  
    
  }

  
  
  

	
}
