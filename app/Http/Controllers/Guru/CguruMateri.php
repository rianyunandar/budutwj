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
use App\El_materi_anggota_rombel;
use App\Master_mapel;

use App\Log_aksi_user;


class CguruMateri extends Controller
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
	function addMateri(){
		$params = [
			'title'	=>'Tambah Materi',
			'label' =>'TAMBAH MATERI',
			'getSekolah' => $this->getSekolah(),
			'getKelas'	=> $this->getLevelKelas(),
		];
		return view('guru/materi/addmateri')->with($params);
	}
	function LihatMateri(){
		$params = [
			'title'	=>'Data Materi',
			'label' =>'DATA MATERI',
			'getSekolah' => $this->getSekolah(),
			'getKelas'	=> $this->getLevelKelas(),
		];
		return view('guru/materi/lihat_materi')->with($params);
	}
	function editMateri($id){
		$idd = Crypt::decrypt($id);
		$data = El_materi::find($idd);
		$params = [
			'title'	=>'Data Materi',
			'label' =>'DATA MATERI',
			'getSekolah' => $this->getSekolah(),
			'getKelas'	=> $this->getLevelKelas(),
			'id'	=>$id,
			'getMateri' =>$data,
		];
		return view('guru/materi/editmateri')->with($params);
	}
	function editMateriRombel($id,$idd){
		
		$idmateri = Crypt::decrypt($id);
		$kodemateri = Crypt::decrypt($idd);

		$data = El_materi::find($idmateri);
		$dataRombel = El_materi_anggota_rombel::where('mtraMateriKode',$kodemateri)
		->where('mtraUgrId',$this->GetIdGuru())
		->with('master_rombel')->get();
		
		$params = [
			'title'	=>'Data Materi Anggota Rombel',
			'label' =>'DATA MATERI ANGGOTA ROMBEL',
			'getSekolah' => $this->getSekolah(),
			'getKelas'	=> $this->getLevelKelas(),
			'id'	=>$id,
			'getMateri' =>$data,
			'getMateriRombel' =>$dataRombel,
		];
		return view('guru/materi/edit_materi_rombel')->with($params);
		
	}

// CRUD ---------------------------------------------------------------------------
function InsertMateri(Request $request){
	$idsklcek=decrypt_url($request->skl);
	$kodeMateri = $request->mapel.$request->kls.'_'.time();
	$namaMapel = $this->getMapel($request->mapel);
	//$tgl = date('Y-m-d H:i:s', strtotime($request->tgl));
	$rombel = $request->rbl;
	$kelas 				= decrypt_url($request->kelas);
	$idkelas = master_kelas::where('klsKode',$kelas)->first();
	
	if($idsklcek == 'ALL'){ $idskl=null; }else{ $idskl=$idsklcek; }

	$materi = new El_materi();
	$materi->materiSklId					= !empty($idskl) ? $idskl : null;
	$materi->materiKlsId					= $idkelas->klsId;
	$materi->materiUgrId					= $this->GetIdGuru();
	$materi->materiMapelKode			= $request->mapel;
	$materi->materiMapelNama 			= $namaMapel->mapelNama;
	$materi->materiKode 					= $kodeMateri;
	$materi->materiJudul					= $request->judul;
	$materi->materiIsi						= $request->isi;
	//$materi->materiTglMulai				= $tgl;
	//$materi->materiTglSelesai			= $request->skl;
	//$materi->materiLink						= $request->skl;
	$materi->materiIdYotube				= $request->idyotube;
	$materi->materiFile						= $request->materifile;

	$materi->materiPesanSingkat		= $request->pesan_singkat;
	$materi->materiTampil					= $request->status;
	$materi->materiKi							= $request->ki;
	$materi->materiKd							= $request->kd;
	$materi->materiKdSub					= $request->subkd;
	$materi->materiPertemuan			= $request->pertemuan;

	$materi->materiCreatedBy			= $this->GetIdGuru();
	if($materi->save()){
		//jika berhasil save 
		
		foreach($rombel as $val){
			$data[]=array(
				'mtraSklId' => $idskl,
				'mtraKlsId' => $idkelas->klsId,
				'mtraRblKode' => $val,
				'mtraUgrId' =>$this->GetIdGuru(),
				'mtraMateriKode'	=> $kodeMateri,
				'mtraMapelKode' => $request->mapel,
				);
				Cache::forget('list_materi'.$this->GetIdGuru().$request->mapel);
				//pada siswa
				Cache::forget('list_materi_siswa'.$val.$request->mapel).$idkelas->klsId;
				Cache::forget('getJadwalMapelListSiswa'.$request->mapel.$idkelas->klsKode);
		}
		$add = El_materi_anggota_rombel::insert($data);
		if($add){
			$dataArray = array(
				'laIdGuru' =>Auth::user()->ugrId,
				'laNamaUser' => FullNamaGuru(),
				'laNamaAksi' =>'Insert Materi Judul '.$request->judul.' Mapel '.$namaMapel->mapelNama,
				'laDateTIme' =>date("Y-m-d H:i:s"),
			);
			Log_aksi_user::insert($dataArray);
			$response = ['success'=>'Berhasil Tambah Materi'];
			return response()->json($response,200);
		}
	
	}
	else{
		//jika gagal save materi
		$response = ['error'=>'Opsss Gagal !!!'];
		return response()->json($response,202);

	}


}
function UpdateMateri(Request $request){
	$idd = Crypt::decrypt($request->id);
	$idsklcek=decrypt_url($request->skl);
	$kodeMateri = $request->mapel.$request->kls.'_'.time();
	$namaMapel = $this->getMapel($request->mapel);
	//$tgl = date('Y-m-d H:i:s', strtotime($request->tgl));

	$rombel = $request->rbl;
	$kelas 				= decrypt_url($request->kelas);
	$idkelas = master_kelas::where('klsKode',$kelas)->first();

	if($idsklcek == 'ALL'){ $idskl=null; }else{ $idskl=$idsklcek; }
	
	

	$materi = El_materi::find($idd);
	$materi->materiSklId					= !empty($idskl) ? $idskl : null;
	$materi->materiKlsId					= $idkelas->klsId;
	$materi->materiUgrId					= $this->GetIdGuru();
	$materi->materiMapelKode			= $request->mapel;
	$materi->materiMapelNama 			= $namaMapel->mapelNama;
	$materi->materiKode 					= $kodeMateri;
	$materi->materiJudul					= $request->judul;
	$materi->materiIsi						= $request->isi;
	//$materi->materiTglMulai				= $tgl;
	//$materi->materiTglSelesai			= $request->skl;
	//$materi->materiLink						= $request->skl;
	$materi->materiIdYotube				= $request->idyotube;
	$materi->materiFile						= $request->materifile;
	$materi->materiPesanSingkat		= $request->pesan_singkat;
	$materi->materiTampil					= $request->status;
	$materi->materiKi							= $request->ki;
	$materi->materiKd							= $request->kd;
	$materi->materiKdSub					= $request->subkd;
	$materi->materiPertemuan			= $request->pertemuan;

	$materi->materiCreatedBy			= $this->GetIdGuru();
	if($materi->save()){
		$dataArray = array(
			'laIdGuru' =>Auth::user()->ugrId,
			'laNamaUser' => FullNamaGuru(),
			'laNamaAksi' =>'Update Materi Judul '.$request->judul.' Mapel '.$namaMapel->mapelNama,
			'laDateTIme' =>date("Y-m-d H:i:s"),
		);
		Cache::forget('list_materi'.$this->GetIdGuru().$request->mapel);
		
		//pada baca materi siswa
		
		Cache::forget('getJadwalMapelListSiswa'.$request->mapel.$idkelas->klsKode);
		Cache::forget('bacamateri'.$idd);

		Log_aksi_user::insert($dataArray);
		$response = ['success'=>'Berhasil Update Materi'];
		return response()->json($response,200);
	}
	else{
		//jika gagal save materi
		$response = ['error'=>'Opsss Gagal !!!'];
		return response()->json($response,202);

	}

}
function UpdateMateriRombel(Request $request){
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
		$cek = El_materi_anggota_rombel::where('mtraMateriKode',$kodeMateri)
		->where('mtraRblKode', $val)
		->count();

		if($cek > 0){
			//jika sudah ada
			$ada++;
		}
		else{
			//jika belum ada
			$data[]=array(
				
				'mtraSklId' 				=> !empty($idskl) ? $idskl : null,
				'mtraKlsId' 				=> $idkelas->klsId,
				'mtraRblKode' 			=> $val,
				'mtraUgrId' 				=> $this->GetIdGuru(),
				'mtraMateriKode'		=> $kodeMateri,
				'mtraMapelKode' 		=> $kodeMapel,
				);
				$tidak++;
		}
		Cache::forget('list_materi'.$this->GetIdGuru().$kodeMapel);

		//pada materi siswa
		Cache::forget('list_materi_siswa'.$val.$kodeMapel.$idkelas->klsId);
		Cache::forget('getJadwalMapelListSiswa'.$kodeMapel.$idkelas->klsKode);
		Cache::forget('bacamateri'.$idmateri);
			
	}//end foreach
	
	$add = El_materi_anggota_rombel::insert($data);
	if($add){
		$dataArray = array(
			'laIdGuru' =>Auth::user()->ugrId,
			'laNamaUser' => FullNamaGuru(),
			'laNamaAksi' =>'Insert Rombel Materi '.$kodeMapel.' Mapel',
			'laDateTIme' =>date("Y-m-d H:i:s"),
		);
		Log_aksi_user::insert($dataArray);
		$response = ['success'=>'Berhasil Tambah Materi Rombel '.$tidak.' | Rombel Sudah Ada '.$ada];
		return response()->json($response,200);
	}
	else{
		//jika gagal save rombel materi
		$response = ['error'=>'Opsss Gagal !!!'];
		return response()->json($response,202);

	}

}
function DeleteMateriRombel(Request $request){
	$cek = El_materi_anggota_rombel::find($request->id);
	$idkelas = master_kelas::find($cek->mtraKlsId);
	$kodekelas = $idkelas->klsKode;
	
	$cek->delete();
	if($cek){
		$dataArray = array(
			'laIdGuru' =>Auth::user()->ugrId,
			'laNamaUser' => FullNamaGuru(),
			'laNamaAksi' =>'Menghapus Rombel Materi Kode Materi'.$cek->mtraMateriKode.' Mapel '.$cek->mtraMapelKode.' Rombel '.$cek->mtraRblKode,
			'laDateTIme' =>date("Y-m-d H:i:s"),
		);
		Cache::forget('list_materi'.$this->GetIdGuru().$cek->mtraMapelKode);

		//pada materi siswa
		Cache::forget('list_materi_siswa'.$cek->mtraRblKode.$cek->mtraMapelKode.$cek->mtraKlsId);
		Cache::forget('getJadwalMapelListSiswa'.$cek->mtraMapelKode.$kodekelas);

		Log_aksi_user::insert($dataArray);
		$response = ['success'=>'Berhasil Menghapus Rombel Materi'];
		return response()->json($response,200);
	}
	else{
		$response = ['error'=>'Opsss Gagal !!!'];
		return response()->json($response,202);
	}

}
function DeleteMateri($id){
	$idd = Crypt::decrypt($id);
	$cek = El_materi::find($idd);
	$idkelas = master_kelas::find($cek->materiKlsId);
	$cek->delete();
	if($cek){
		$dataArray = array(
			'laIdGuru' =>Auth::user()->ugrId,
			'laNamaUser' => FullNamaGuru(),
			'laNamaAksi' =>'Menghapus Materi Kode Materi'.$cek->materiKode.' Mapel '.$cek->materiMapelNama.' Judul '.$cek->materiJudul,
			'laDateTIme' =>date("Y-m-d H:i:s"),
		);
		
		Cache::forget('list_materi'.$this->GetIdGuru().$cek->materiMapelKode);

		//pada materi siswa
		Cache::forget('getJadwalMapelListSiswa'.$cek->materiMapelKode.$idkelas->klsKode);
		Log_aksi_user::insert($dataArray);
		$response = ['success'=>'Berhasil Menghapus Materi'];
		return response()->json($response,200);
	}
	else{
		$response = ['error'=>'Opsss Gagal !!!'];
		return response()->json($response,202);
	}

}

function JsonMateri(Request $request){
	$skl = decrypt_url($request->input('skl'));
  $idkelas = decrypt_url($request->input('amp;kelas'));
	$mapel = $request->input('amp;mapel');
	$kelas = Master_kelas::where('klsKode',$idkelas)->first();

	$data = EL_materi::where('materiUgrId',Auth::user()->ugrId)
	// ->where('materiSklId',$skl)
	->where('materiKlsId',$kelas->klsId)
	->where('materiMapelKode',$mapel)
	->with('anggota_rombel')
	->get();


	$dt= DataTables::of($data)
	->addColumn('no',function ($data) { 
			
	})
	->addColumn('anggota_rombel',function ($data) { 
		
		$data2 = El_materi_anggota_rombel::where('mtraMateriKode', $data->materiKode)
		->with('master_rombel')
		->get(); 
		foreach ($data2 as $val){
			$dataArray[]= '<span class="badge badge-primary">'.$val->master_rombel->master_kelas->klsKode.' '.$val->master_rombel->rblNama.'</span>' ;	
		}
		if(empty($dataArray)){
			return null;
		}
		else{
			return implode(' ',$dataArray);
		}
		
		
	})
	->addColumn('guru',function ($data) { 
		$data2 = User_guru::find($data->materiUgrId);
		return $data2->ugrFirstName.' '.$data2->ugrLastName;
	})
	->addColumn('aksi',function ($data) { 
		$id = Crypt::encrypt($data->materiId);
		$kode_materi = Crypt::encrypt($data->materiKode);
		
		$button = '<a href="edit-materi/'.$id.'" data-id="'.$id.'" title="Edit Data" class="edit dropdown-item btn btn-sm btn-outline bg-primary text-primary border-primary legitRipple" ><i class="icon-pencil7"> Edit</i></a>';
		$button .= '<a href="edit-materi-rombel/'.$id.'/'.$kode_materi.'" data-id="'.$id.'" title="Edit Rombel" class="erombel dropdown-item btn btn-sm btn-outline bg-success text-success border-success legitRipple" ><i class="icon-users4"> Rombel</i></a>';
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
	->rawColumns(['aksi','anggota_rombel']);
	return $dt->make(); 

}

function editDataMateriRombel(Request $request){
	
	$data = El_materi_anggota_rombel::find($request->id);
	$data->mtraTerbit					= date('Y-m-d H:i:s', strtotime($request->tgl));
	$data->mtraTampilSiswa		=$request->tampil;

	$idkelas = master_kelas::find($data->mtraKlsId);
	
	if($data->save()){

		//pada materi siswa
		Cache::forget('list_materi_siswa'.$data->mtraRblKode.$data->mtraMapelKode.$data->mtraKlsId);
		Cache::forget('getJadwalMapelListSiswa'.$data->mtraMapelKode.$idkelas->klsKode);

		$response = ['success'=>'Berhasil Edit Rombel Materi'];
		return response()->json($response,200);
	}
	else{
		$response = ['error'=>'Opsss Gagal !!!'];
		return response()->json($response,202);
	}

}




}
