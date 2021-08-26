<?php

//membaca lokasi Controller dalam Folder
namespace App\Http\Controllers\Guru;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; //menjalankan printah Hash Enkripsi
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Support\Facades\DB;
use DataTables;
use Auth;//menjalankan printah auth
use App\User_guru;
use App\Master_sekolah;
use App\Master_agama;
use App\Master_jurusan;
use App\Master_rombel;
use App\Master_kelas;
use App\El_materi;
use App\El_tugas;
use App\El_materi_anggota_rombel;
use App\El_tugas_anggota_rombel;
use App\Master_mapel;

use App\Log_aksi_user;


class CguruTugas extends Controller
{
	public function __construct()
  {
		$this->middleware('auth:guru');
		
	}
	
	function GetIdGuru(){
		$id = Auth::user()->ugrId; 
		return $id;
	
	}
	function getSekolah()
  {
    $data = new Master_sekolah();
    return $data->getSekolah();
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
	function getMapel($id=null)
  {
		//panggil mapel bisa berdasarkan kode atau semua
    $data = new Master_mapel();
    return $data->getMapelByKode($id);
	}

// -------------------------------------------------------------------------------
	function addTugas(){
		$params = [
			'title'	=>'Tambah Tugas',
			'label' =>'TAMBAH TUGAS',
			'getSekolah' => $this->getSekolah(),
			'getKelas'	=> $this->getLevelKelas(),
		];
		return view('guru/tugas/add_tugas')->with($params);
	}
	function LihatTugas(){
		$params = [
			'title'	=>'Data Tugas',
			'label' =>'DATA TUGAS',
			'getSekolah' => $this->getSekolah(),
			'getKelas'	=> $this->getLevelKelas(),
		];
		return view('guru/tugas/lihat_tugas')->with($params);
	}
	function editTugas($id){
	
		$idd = Crypt::decrypt($id);
		
		$data = El_tugas::find($idd);
		$params = [
			'title'	=>'Edit Tugas',
			'label' =>'EDIT TUGAS',
			'getSekolah' => $this->getSekolah(),
			'getKelas'	=> $this->getLevelKelas(),
			'id'	=>$id,
			'getData' =>$data,
		];
		return view('guru/tugas/edit_tugas')->with($params);
	}
	function editTugasRombel($id,$idd){
		
		$idmateri = Crypt::decrypt($id);
		$kodemateri = Crypt::decrypt($idd);

		$data = El_tugas::find($idmateri);
		$dataRombel = El_tugas_anggota_rombel::where('tgsarTugasKode',$kodemateri)
		->where('tgsarUgrId',$this->GetIdGuru())
		->with('master_rombel')
		->get();
		
		$params = [
			'title'	=>'Data Tugas Anggota Rombel',
			'label' =>'DATA TUGAS ANGGOTA ROMBEL',
			'getSekolah' => $this->getSekolah(),
			'getKelas'	=> $this->getLevelKelas(),
			'id'	=>$id,
			'getData' =>$data,
			'getDataRombel' =>$dataRombel,
		];
		return view('guru/tugas/edit_tugas_rombel')->with($params);
		
	}

	// CRUD ---------------------------------------------------------------------------
function InsertTugas(Request $request){
	
	$idsklcek=decrypt_url($request->skl);
	$kodeTugas = $request->mapel.$request->kls.'_'.time();
	$namaMapel = $this->getMapel($request->mapel);
	//$tgl = date('Y-m-d H:i:s', strtotime($request->tgl));
	$rombel = $request->rbl;
	$kelas 				= decrypt_url($request->kelas);
	$idkelas = Master_kelas::where('klsKode',$kelas)->first();
	
	if($idsklcek == 'ALL'){ $idskl=null; }else{ $idskl=$idsklcek; }

	$tugas = new El_tugas();
	$tugas->tugasSklId					= !empty($idskl) ? $idskl : null;
	$tugas->tugasKlsId					= $idkelas->klsId;
	$tugas->tugasUgrId					= $this->GetIdGuru();
	$tugas->tugasMapelKode			= $request->mapel;
	$tugas->tugasMapelNama 			= $namaMapel->mapelNama;
	$tugas->tugasKode 					= $kodeTugas;
	$tugas->tugasJudul					= $request->judul;
	$tugas->tugasIsi						= $request->isi;
	$tugas->tugasLink						= $request->link_tugas;
	$tugas->tugasFile						= $request->link_download;

	$tugas->tugasPesanSingkat		= $request->pesan_singkat;
	$tugas->tugasTampil					= $request->status;
	$tugas->tugasKi							= $request->ki;
	$tugas->tugasKd							= $request->kd;
	$tugas->tugasPertemuan			= $request->pertemuan;


	$tugas->tugasCreatedBy			= $this->GetIdGuru();
	if($tugas->save()){
	
		$dataArray = array(
			'laIdGuru' =>Auth::user()->ugrId,
			'laNamaUser' => FullNamaGuru(),
			'laNamaAksi' =>'Insert Tugas Judul '.$request->judul.' Tugas '.$namaMapel->mapelNama,
			'laDateTIme' =>date("Y-m-d H:i:s"),
		);
		Log_aksi_user::insert($dataArray);
		$response = ['success'=>'Berhasil Tambah Tugas'];
		return response()->json($response,200);
	
	}
	else{
		//jika gagal save materi
		$response = ['error'=>'Opsss Gagal !!!'];
		return response()->json($response,202);

	}


}
function UpdateTugas(Request $request){
	$idd = Crypt::decrypt($request->id);
	$idsklcek=decrypt_url($request->skl);
	$kodeTugas = $request->mapel.$request->kls.'_'.time();
	$namaMapel = $this->getMapel($request->mapel);
	//$tgl = date('Y-m-d H:i:s', strtotime($request->tgl));
	$rombel = $request->rbl;
	$kelas 				= decrypt_url($request->kelas);
	$idkelas = Master_kelas::where('klsKode',$kelas)->first();
	
	if($idsklcek == 'ALL'){ $idskl=null; }else{ $idskl=$idsklcek; }

	$tugas = El_tugas::find($idd);
	$tugas->tugasSklId					= !empty($idskl) ? $idskl : null;
	$tugas->tugasKlsId					= $idkelas->klsId;
	$tugas->tugasUgrId					= $this->GetIdGuru();
	$tugas->tugasMapelKode			= $request->mapel;
	$tugas->tugasMapelNama 			= $namaMapel->mapelNama;
	$tugas->tugasKode 					= $kodeTugas;
	$tugas->tugasJudul					= $request->judul;
	$tugas->tugasIsi						= $request->isi;
	$tugas->tugasLink						= $request->link_tugas;
	$tugas->tugasFile						= $request->link_download;

	$tugas->tugasPesanSingkat		= $request->pesan_singkat;
	$tugas->tugasTampil					= $request->status;
	$tugas->tugasKi							= $request->ki;
	$tugas->tugasKd							= $request->kd;
	$tugas->tugasPertemuan			= $request->pertemuan;


	$tugas->tugasCreatedBy			= $this->GetIdGuru();
	if($tugas->save()){
		
		$dataArray = array(
			'laIdGuru' =>Auth::user()->ugrId,
			'laNamaUser' => FullNamaGuru(),
			'laNamaAksi' =>'Edit Tugas Judul '.$request->judul.' Tugas '.$namaMapel->mapelNama,
			'laDateTIme' =>date("Y-m-d H:i:s"),
		);
		Log_aksi_user::insert($dataArray);
		$response = ['success'=>'Berhasil Edit Tugas'];
		return response()->json($response,200);
	
	}
	else{
		//jika gagal save materi
		$response = ['error'=>'Opsss Gagal !!!'];
		return response()->json($response,202);

	}


}
function JsonTugas(Request $request){
	$skl = decrypt_url($request->input('skl'));
  $idkelas = decrypt_url($request->input('amp;kelas'));
	$mapel = $request->input('amp;mapel');
	$kelas = Master_kelas::where('klsKode',$idkelas)->first();

	if($skl == 'ALL'){ $idskl=null; }else{ $idskl=$skl; }

	$data = EL_tugas::where('tugasUgrId',Auth::user()->ugrId)
	->where('tugasSklId',$idskl)
	->where('tugasKlsId',$kelas->klsId)
	->where('tugasMapelKode',$mapel)
	// ->with('anggota_rombel')
	->get();


	$dt= DataTables::of($data)
	->addColumn('no',function ($data) { 
			
	})
	// ->addColumn('anggota_rombel',function ($data) { 
		
	// 	$data2 = El_materi_anggota_rombel::where('mtraMateriKode', $data->materiKode)
	// 	->with('master_rombel')
	// 	->get(); 
	// 	foreach ($data2 as $val){
	// 		$dataArray[]= '<span class="badge badge-primary">'.$val->master_rombel->master_kelas->klsKode.' '.$val->master_rombel->rblNama.'</span>' ;	
	// 	}
	// 	if(empty($dataArray)){
	// 		return null;
	// 	}
	// 	else{
	// 		return implode(' ',$dataArray);
	// 	}
		
		
	// })
	->addColumn('guru',function ($data) { 
		$data2 = User_guru::find($data->tugasUgrId);
		return $data2->ugrFirstName.' '.$data2->ugrLastName;
	})
	->addColumn('aksi',function ($data) { 
		$id = Crypt::encrypt($data->tugasId);
		$kode_tugas = Crypt::encrypt($data->tugasKode);
		
		$button = '<a href="edit-tugas/'.$id.'" data-id="'.$id.'" title="Edit Data" class="edit dropdown-item btn btn-sm btn-outline bg-primary text-primary border-primary legitRipple" ><i class="icon-pencil7"> Edit</i></a>';
		$button .= '<a href="edit-tugas-rombel/'.$id.'/'.$kode_tugas.'" data-id="'.$id.'" title="Edit Rombel" class="erombel dropdown-item btn btn-sm btn-outline bg-success text-success border-success legitRipple" ><i class="icon-users4"> Rombel</i></a>';
		$button .='<a title="Hapus Data" id="delete" class="dropdown-item btn btn-sm btn-outline bg-danger text-danger border-danger legitRipple" data-id="'.$id.'"><i class="icon-trash"> Hapus</i></a>';
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
	->rawColumns(['aksi']);
	return $dt->make(); 

}

//insert atau update rombel yang bisa mengakses tugas
function UpdateTugasRombel(Request $request){
	
	$rombel 			= $request->rbl;
	$idmateri 		= decrypt_url($request->idmateri);
	$kodeMateri 	= decrypt_url($request->kodemateri);
	$kodeMapel 		= decrypt_url($request->kodemapel);
	$idsklcek 		= decrypt_url($request->idskl);
	$kelas 				= decrypt_url($request->kelas);
	$idkelas = master_kelas::where('klsKode',$kelas)->first();

	if($idsklcek == 'ALL'){ $idskl=null; }else{ $idskl=$idsklcek; }

	$ada=$tidak=0;
	foreach($rombel as $val){
		//cek apakah rombel pada materi ini sudah ada
		$cek = El_tugas_anggota_rombel::where('tgsarTugasKode',$kodeMateri)
		->where('tgsarRblKode', $val)
		->count();

		if($cek > 0){
			//jika sudah ada
			$ada++;
		}
		else{
			//jika belum ada
			$data[]=array(
				
				'tgsarSklId' 				=> !empty($idskl) ? $idskl : null,
				'tgsarKlsId' 				=> $idkelas->klsId,
				'tgsarRblKode' 			=> $val,
				'tgsarUgrId' 				=> $this->GetIdGuru(),
				'tgsarTugasKode'		=> $kodeMateri,
				'tgsarMapelKode' 		=> $kodeMapel,
				);
				$tidak++;
		}
		// Cache::forget('list_materi'.$this->GetIdGuru().$kodeMapel);

		// //pada materi siswa
		// Cache::forget('list_materi_siswa'.$val.$kodeMapel.$idkelas->klsId);
		// Cache::forget('getJadwalMapelListSiswa'.$kodeMapel.$idkelas->klsKode);
		// Cache::forget('bacamateri'.$idmateri);
		Cache::forget('list_tugas_siswa'.$val);
			
	}//end foreach
	
	$add = El_tugas_anggota_rombel::insert($data);
	if($add){
		$dataArray = array(
			'laIdGuru' =>Auth::user()->ugrId,
			'laNamaUser' => FullNamaGuru(),
			'laNamaAksi' =>'Insert Rombel Materi '.$kodeMapel.' Mapel',
			'laDateTIme' =>date("Y-m-d H:i:s"),
		);
		Log_aksi_user::insert($dataArray);
		$response = ['success'=>'Berhasil Tambah Tugas Rombel '.$tidak.' | Rombel Sudah Ada '.$ada];
		return response()->json($response,200);
	}
	else{
		//jika gagal save rombel materi
		$response = ['error'=>'Opsss Gagal !!!'];
		return response()->json($response,202);

	}

}
function editDataTugasRombel(Request $request){
	
	$data = El_tugas_anggota_rombel::find($request->id);
	$data->tgsarTerbit					= date('Y-m-d H:i:s', strtotime($request->tgl));
	$data->tgsarBatasTerbit			= date('Y-m-d H:i:s', strtotime($request->akhir));
	$data->tgsarTampilSiswa			=$request->tampil;

	$idkelas = master_kelas::find($data->tgsarKlsId);
	
	if($data->save()){

		//pada materi siswa
		Cache::forget('list_tugas_siswa'.$data->tgsarRblKode);
		Cache::forget('getJadwalMapelTugasListSiswa'.$data->tgsarRblKode.$idkelas->klsKode);

		$response = ['success'=>'Berhasil Edit Rombel Materi'];
		return response()->json($response,200);
	}
	else{
		$response = ['error'=>'Opsss Gagal !!!'];
		return response()->json($response,202);
	}
	

}
function DeleteTugasRombel(Request $request){
	$cek = El_tugas_anggota_rombel::find($request->id);
	$idkelas = Master_kelas::find($cek->tgsarKlsId);
	$kodekelas = $idkelas->klsKode;
	
	$cek->delete();
	if($cek){
		$dataArray = array(
			'laIdGuru' =>Auth::user()->ugrId,
			'laNamaUser' => FullNamaGuru(),
			'laNamaAksi' =>'Menghapus Rombel Tugas Kode Tugas'.$cek->tgsarTugasKode.' Mapel '.$cek->tgsarMapelKode.' Rombel '.$cek->tgsarRblKode,
			'laDateTIme' =>date("Y-m-d H:i:s"),
		);
		Cache::forget('list_tugas'.$this->GetIdGuru().$cek->tgsarMapelKode);

		// //pada materi siswa
		Cache::forget('list_tugas_siswa'.$cek->tgsarRblKode);
		Cache::forget('getJadwalMapelTugasListSiswa'.$cek->tgsarRblKode.$kodekelas);

		Log_aksi_user::insert($dataArray);
		$response = ['success'=>'Berhasil Menghapus Rombel Tugas'];
		return response()->json($response,200);
	}
	else{
		$response = ['error'=>'Opsss Gagal !!!'];
		return response()->json($response,202);
	}

}
function UpdateTugasOff(Request $request){
	dd($request);
}
function DeleteTugas($id){
	$idd = Crypt::decrypt($id);
	$cek = El_tugas::find($idd);
	$idkelas = master_kelas::find($cek->tugasKlsId);
	$cek->delete();
	if($cek){
		
		Cache::forget('list_tugas'.$this->GetIdGuru().$cek->tugasMapelKode);
		

		
		$response = ['success'=>'Berhasil Menghapus Materi'];
		return response()->json($response,200);
	}
	else{
		$response = ['error'=>'Opsss Gagal !!!'];
		return response()->json($response,202);
	}

}




}
