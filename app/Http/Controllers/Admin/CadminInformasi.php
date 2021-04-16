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
use App\Informasi;
use App\Log_aksi_user;
use App\Master_sekolah;


class CadminInformasi extends Controller
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
  function getSekolah()
  {
  	$data = new Master_sekolah();
    return $data->getSekolah($this->getSkl());
  }
  function getInfoSekolah()
  {
  	$data = new Informasi();
    return $data->getInfoSekolah($this->getSkl());
  }
 
//------------------------------------------------------------------
	public function AddInformasi()
	{
		
		$params = [
			'title'	=>'Tambah Informasi Sekolah',
      'label'	=>'<b>FORM TAMBAH INFORMASI SEKOLAH</b>',
      'getSekolah' => $this->getSekolah(),
		];
		return view('crew/informasi/sekolah/addinformasi')->with($params);
  }
  public function LihatInformasi(){
    $params = [
			'title'	=>'Data Informasi Sekolah',
      'label'	=>'<b>DATA INFORMASI SEKOLAH</b>',
      'no'  =>1,
      'getInfo' => $this->getInfoSekolah(),
		];
		return view('crew/informasi/sekolah/lihatinformasi')->with($params);
  }
  public function EditInformasi($id)
	{
    $idd = decrypt_url($id);
    $data = Informasi::find($idd);
		
		$params = [
			'title'	=>'Edit Informasi Sekolah',
      'label'	=>'<b>FORM EDIT INFORMASI SEKOLAH</b>',
      'getSekolah' => $this->getSekolah(),
      'idinfo' =>$idd,
      'getInfo' => $data,
		];
		return view('crew/informasi/sekolah/editinformasi')->with($params);
  }

//CRUD ---------------------------------------------------------------
  function InsertInformasiSekolah(Request $request){
    $idskl = Auth::user()->admSklId;
    $sklinput = $request->skl;
    if($sklinput == 'ALL'){
      $tujuan = $sklinput;
      $idinputskl = 0;
    }
    else{
      $idinputskl = $sklinput;
      $data = Master_sekolah::find($sklinput);
      $tujuan = $data->sklNama; 
    }
    $info  = new Informasi();
    $info->infoSkl = $idskl;
    $info->infoJudul = $request->judul;
    $info->infoIsi = $request->isi;
    $info->infoTujuan = $tujuan;
    $info->infoIdTujuan = $idinputskl;
    $info->infoNamaUser = FullNama();
    $info->infoCreatedBy = Auth::user()->admId;

    if($info->save()){ 
      Cache::forget('informasi_sekolah'.$idskl); 
      Cache::forget('userinformasi_sekolah'.$idskl); 
      //tampung array aktiftas
				$dataArray = array(
					'laIdAdmin' =>Auth::user()->admId,
					'laNamaUser' => FullNamaGuru(),
					'laNamaAksi' =>'Membuat Informasi Sekolah '.$tujuan,
					'laDateTIme' =>date("Y-m-d H:i:s"),
				);
				//save aktifitas kegiatan
        Log_aksi_user::insert($dataArray);
        
      $response = ['save'=>'Berhasil Tambah Informsi Sekolah'];
				return response()->json($response,200);
    }
    else{ 
      $response = ['error'=>'Opsss Gagal !!!'];
      return response()->json($response,202);
    }
  }

  function UpdateInformasiSekolah($id,Request $request){
    $idskl = Auth::user()->admSklId;
    $sklinput = $request->skl;
    if($sklinput == 'ALL'){
      $tujuan = $sklinput;
      $idinputskl = 0;
    }
    else{
      $idinputskl = $sklinput;
      $data = Master_sekolah::find($sklinput);
      $tujuan = $data->sklNama; 
    }
    $info  = Informasi::find($id);
    $info->infoSkl = $idskl;
    $info->infoJudul = $request->judul;
    $info->infoIsi = $request->isi;
    $info->infoTujuan = $tujuan;
    $info->infoIdTujuan = $idinputskl;
    $info->infoNamaUser = FullNama();
    $info->infoCreatedBy = Auth::user()->admId;

    if($info->save()){ 
      Cache::forget('informasi_sekolah'.$idskl); 
      Cache::forget('userinformasi_sekolah'.$idskl); 
      //tampung array aktiftas
				$dataArray = array(
					'laIdAdmin' =>Auth::user()->admId,
					'laNamaUser' => FullNamaGuru(),
					'laNamaAksi' =>'Update Informasi Sekolah '.$request->judul.' '.$tujuan,
					'laDateTIme' =>date("Y-m-d H:i:s"),
				);
				//save aktifitas kegiatan
        Log_aksi_user::insert($dataArray);
        
      $response = ['save'=>'Berhasil Update Informsi Sekolah'];
				return response()->json($response,200);
    }
    else{ 
      $response = ['error'=>'Opsss Gagal !!!'];
      return response()->json($response,202);
    }

  }
  function DeleteInformasiSekolah($id){
    $idskl = Auth::user()->admSklId;
    $idd = decrypt_url($id);
    $info = Informasi::find($idd);
    $info->infoIsActive = 0;
    $info->infoDeleted = date("Y-m-d H:i:s");
    $info->infoDeletedBy = Auth::user()->admId;

    if($info->save()){ 
      Cache::forget('informasi_sekolah'.$idskl); 
      Cache::forget('userinformasi_sekolah'.$idskl); 
      //tampung array aktiftas
				$dataArray = array(
					'laIdAdmin' =>Auth::user()->admId,
					'laNamaUser' => FullNamaGuru(),
					'laNamaAksi' =>'Menghapus Informasi Sekolah ',
					'laDateTIme' =>date("Y-m-d H:i:s"),
				);
				//save aktifitas kegiatan
        Log_aksi_user::insert($dataArray);
        
      $response = ['success'=>'Berhasil Hapus Informsi Sekolah'];
				return response()->json($response,200);
    }
    else{ 
      $response = ['error'=>'Opsss Gagal !!!'];
      return response()->json($response,202);
    }

  }
  
  
}
