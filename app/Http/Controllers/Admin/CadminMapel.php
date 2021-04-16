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
use Excel;
use DataTables;
use App\User_guru;
use App\Master_sekolah;
use App\Master_jurusan;
use App\Master_kelas;
use App\Master_mapel;
use App\Log_aksi_user;
use App\Imports\ImportDataMapel;

class CadminMapel extends Controller
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
  function getLevelKelas()
  {
    if (Cache::has('master_kelas')){ $data = Cache::get('master_kelas'); }
    else{ 
      $data = Master_kelas::all();
      Cache::put('master_kelas', $data, ChaceJam());
    }
    return $data;
  }
// ------------------------------------------------------------------------------
  function addMapel()
  {
    $params = [
      'title' =>'Tambah Mapel',
      'label' =>'<b>TAMBAH MAPEL</b> ',
      'getSekolah' => $this->getSekolah(),
      'getKelas' => $this->getLevelKelas(),
      'getJurusan' => $this->getJurusan(),
    ];
    return view('crew/mapel/add_mapel')->with($params);
  }
  function editMapel($id)
  {
    $idd = Crypt::decrypt($id);
    $data = Master_mapel::find($idd); 
    $params = [
      'title' =>'Edit Mapel',
      'label' =>'<b>EDIT MAPEL</b> ',
      'getSekolah' => $this->getSekolah(),
      'getKelas' => $this->getLevelKelas(),
      'getJurusan' => $this->getJurusan(),
      'id' =>$idd ,
      'dataMapel' =>$data,
    ];
    return view('crew/mapel/edit_mapel')->with($params);
  }
  public function lihatMapel()
  {
    $params = [
      'title' =>'Data Mapel',
      'label' =>'<b>DATA MAPEL</b> ',
    ];
    return view('crew/mapel/view_mapel')->with($params);
  }
//json ----------------------------------------------------------------------
  function jsonGetMapel(){
    //menampilkan data mapel
    if(empty($this->getSkl())){ 
      $data = Master_mapel::with('master_sekolah')
        ->with('master_kelas')
        ->with('master_jurusan')
        ->get();
    }
    else{
      $data = Master_mapel::with('master_sekolah')
        ->with('master_kelas')
        ->with('master_jurusan')
        ->where('mapelSklId',$this->getSkl())
        ->get();
    }
    
    $dt= DataTables::of($data)
    ->addColumn('no','')
    ->addColumn('jurusan',function ($data) { 
        if(empty($data->mapelJrsId)){
          return null;
        }
        else{
          return $data->master_jurusan->jrsSlag;
        }

    })
    ->addColumn('sekolah',function ($data) { 
      if(empty($data->mapelSklId)){
        return null;
      }
      else{
        return $data->master_sekolah->sklKode;
      }

    })
    ->addColumn('kelas',function ($data) { 
      if(empty($data->mapelKlsId)){
        return null;
      }
      else{
        return $data->master_kelas->klsKode;
      }

    })
    ->addColumn('aksi',function ($data) { 
      $id = Crypt::encrypt($data->mapelId);
      if(AksiUpdate()){
        $button = '<a href="'.$id.'/edit-mapel" title="Edit Data" class="dropdown-item btn btn-sm btn-outline bg-primary text-primary border-primary legitRipple" ><i class="icon-pencil7"> Edit</i></a>';
      }else{ $button = '<a title="No Akses" class="dropdown-item btn btn-sm btn-outline bg-danger text-danger border-danger legitRipple" ><i class="icon-cancel-circle2"> No Akses</i></a>';  }
      if(AksiDelete()){
      //$button .='<a title="Hapus Data" id="delete" class="dropdown-item btn btn-sm btn-outline bg-danger text-danger border-danger legitRipple" data-id="'.$id.'"><i class="icon-trash"> Hapus</i></a>';
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


//crud ----------------------------------------------------------------------
  function insertMapel(Request $request){
    $kode = strtoupper($request->kodemapel);
    $cek = Master_mapel::where('mapelKode',$kode)->first();
    
    if(!empty($cek)){
      $response = ['warning'=>'Kode Mapel Sudah di Gunakan Oleh Mapel '.$cek->mapelNama]; 
    }
    else{
      $data = new Master_mapel();
      $data->mapelSklId = ($request->skl == 'all') ? null :  $request->skl;
      $data->mapelJrsId = ($request->jrs == 'all') ? null : $request->jrs;
      $data->mapelMpktKode = $request->kategori;
      $data->mapelKlsId = ($request->kls == 'all') ? null : $request->kls;
      $data->mapelKode = $kode;
      $data->mapelSlug = strtoupper($request->slugmapel);
      $data->mapelNama = $request->namamapel;
      $data->mapelPaket = $request->paketmapel;

      if($data->save()){
        //Cache::forget('mapel'.$this->getSkl());
        $dataArray = array(
          'laIdAdmin' =>Auth::user()->admId,
          'laNamaUser' => FullNama(),
          'laNamaAksi' =>'Tambah Mapel '.$request->namamapel,
          'laDateTIme' =>date("Y-m-d H:i:s"),
        );
        Log_aksi_user::insert($dataArray);

        $response = ['save'=>'Berhasil Tambah Mapel'];
      }
      else{
        $response = ['error'=>'Opsss Gagal !!! ']; 
      }
    }

    
    return response()->json($response,200);

  }
  function updateMapel(Request $request){

      $id =$request->id;  
      $kode = strtoupper($request->kodemapel);
    
      $data = Master_mapel::find($id);
      $data->mapelSklId = ($request->skl == 'all') ? null :  $request->skl;
      $data->mapelJrsId = ($request->jrs == 'all') ? null : $request->jrs;
      $data->mapelMpktKode = $request->kategori;
      $data->mapelKlsId = ($request->kls == 'all') ? null : $request->kls;
      $data->mapelKode = $kode;
      $data->mapelSlug = strtoupper($request->slugmapel);
      $data->mapelNama = $request->namamapel;
      $data->mapelPaket = $request->paketmapel;

      if($data->save()){
        //Cache::forget('mapel'.$this->getSkl());
        $dataArray = array(
          'laIdAdmin' =>Auth::user()->admId,
          'laNamaUser' => FullNama(),
          'laNamaAksi' =>'Update Mapel '.$request->namamapel,
          'laDateTIme' =>date("Y-m-d H:i:s"),
        );
        Log_aksi_user::insert($dataArray);

        $response = ['save'=>'Berhasil Update Mapel'];
      }
      else{
        $response = ['error'=>'Opsss Gagal !!! ']; 
      }
   

    
    return response()->json($response,200);

  }


//import data -------------------------------------------------------------------  
	public function FormMapel()
	{
		
		$params = [
			'title'	=>'Import Data Mapel',
      'label'	=>'<b>IMPORT DATA MAPEL</b> ',
      'text' =>'Format File Excel Upload Data Mapel.xlsx',
		];
		return view('crew/mapel/form_import_data_mapel')->with($params);
  }

  public function ImportDataMapel(Request $request)
  {
    $file = $request->file('import_data_mapel');
    $extensi = $file->getClientOriginalExtension();

    if($extensi == 'xls' or $extensi == 'xlsx' ){
      $import = new ImportDataMapel();
      Excel::import($import,$file);
      $insert = get_object_vars($import); //mengubah objek ke data array
      if($insert['hasil'] ==1){
        $response = ['save'=>'Berhasil Upload Data '.$insert['jm'].' Mapel'];
      }
      elseif ($insert['hasil'] == 0) {
        $response = ['error'=>'Gagal Upload Data Mapel'];
      }
      else{
        $response = ['error'=>'Sistem Error'];
      }

    }
    else{
      $response = ['danger'=>'Cek File, Pastikan Ber extensi atau Format XLSX'];
     }
     //return redirect('crew/form-import-siswa')->with($response);
     return response()->json($response,200);    
  }

  
  
  

	
}
