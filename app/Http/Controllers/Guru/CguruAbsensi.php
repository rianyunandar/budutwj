<?php

//membaca lokasi Controller dalam Folder
namespace App\Http\Controllers\Guru;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; //menjalankan printah Hash Enkripsi
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Carbon;
use DB;
use DataTables;
use Auth;//menjalankan printah auth
use App\User_siswa;
use App\User_guru;
use App\Master_sekolah;
use App\Master_agama;
use App\Master_jurusan;
use App\Master_rombel;
use App\Profile_siswa;
use App\Profile_guru;
use App\Tahun_ajaran;
use App\Master_wali_kelas;
use App\Log_aksi_user;
use App\Semester;
use App\Master_kelas;
use App\Master_mapel;
use App\Master_mapel_jadwal;
use App\Master_mapel_jadwal_rombel;
use App\Absen_mapel;
use App\Absen_finger_siswa;

class CguruAbsensi extends Controller
{
	public function __construct()
  {
		$this->middleware('auth:guru');
		
	}

  function getSkl()
  {
  	$idskl = Auth::user()->ugrSklId; 
  	return $idskl;
	}
	function GetIdGuru(){
		$id = Auth::user()->ugrId; 
		return $id;
	
	}
	function getTahunAjaran()
	{ //ambil kode tahun ajaran yg aktif
		if (Cache::has('tahunAjaranAktif')){ $data= Cache::get('tahunAjaranAktif'); }
		else{ 
			$data = Tahun_ajaran::where('tajrIsActive',1)->first();
			Cache::put('tahunAjaranAktif', $data, ChaceJam() );
		}
		return $data->tajrKode;
	}
	function getTahunAjaranNama()
	{ //ambil kode tahun ajaran yg aktif
		if (Cache::has('NamatahunAjaranAktif')){ $data= Cache::get('NamatahunAjaranAktif'); }
		else{
			$data = Tahun_ajaran::where('tajrIsActive',1)->first();
		Cache::put('NamatahunAjaranAktif', $data, ChaceJam() );
		}
		return $data->tajrNama;
	}
	function getSemester()
  { //ambil kode semester yg aktif
    if (Cache::has('semesterkode')){ $data = Cache::get('semesterkode'); }
    else{ 
      $data = Semester::where('smIsActive',1)->first();
      Cache::put('semesterkode', $data, ChaceJam());
    }
		return $data->smKode;
	}
	function cariRombelKode($id){
		$data = Master_rombel::find($id);
		return $data->rblKode;
	}
	
  function getSekolah()
  {
    $data = new Master_sekolah();
    return $data->getSekolah();
	}
	function getRombel()
  {
    $data = new Master_rombel();
    return $data->getRombel($this->getSkl());
	}
	public function getIdRombel($id)
  {
		//Cache Redis --------------------------------------------------------------------
		if (Cache::has('getIdRombelJadwalRombel'.$id)){ $data = Cache::get('getIdRombelJadwalRombel'.$id); }
		else{ 
			$data = Master_rombel::where('rblKode', $id)->first();
			Cache::put('getIdRombelJadwalRombel'.$id, $data, ChaceJam());
		}
		return $data;
		//Cache Redis --------------------------------------------------------------------
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
	function getRombelMapelJadwal($id){
		if (Cache::has('getRombelMapelJadwal'.$id)){ $val2 = Cache::get('getRombelMapelJadwal'.$id); }
    else{ 
			$datas = Master_mapel_jadwal_rombel::where('mjrMapelJadwal',$id)->get();
			foreach($datas as $data){
				$val= array(
					'namarombel' => $data->mjrNamaRombel,
				);
				$val2[]=$val;
			}
		Cache::put('getRombelMapelJadwal'.$id, $val2, ChaceJam());
		}
		return $val2;
	}
	//ambil data mapel pada jadwal berdasarkan id guru
	//dan id sekolah pada select pilihan 
	//karna guru bisa mengajar pada 2 sekolah
	function getJadwalMapel(){
		if(!empty($_GET['skl'])){
			$skl=decrypt_url($_GET['skl']);
			if(!empty($_GET['rbl'])){ 
				$rbl = decrypt_url($_GET['rbl']);
				$kodeRombel = $this->cariRombelKode($rbl);
				$data = Master_mapel_jadwal::where('majdUgrId',$this->GetIdGuru())
				->where('majdSklId',$skl)
				->where('majdRblKode',$kodeRombel)
				->get();
			}
			else{
				$data = Master_mapel_jadwal::where('majdUgrId',$this->GetIdGuru())
				->where('majdSklId',$skl)
				->get();
			}
		}else{
			$data = [];
		}
		
		return $data;
		
	}
	function getJadwalMapelOne(){
		//ambil jadwal mapel hanya satu
		if(!empty($_GET['skl'])){
			$skl=decrypt_url($_GET['skl']);
			if(!empty($_GET['rbl'])){ 
				$rbl = decrypt_url($_GET['rbl']);
				$kodeRombel = $this->cariRombelKode($rbl);
				$data = Master_mapel_jadwal::where('majdUgrId',$this->GetIdGuru())
				->where('majdSklId',$skl)
				->where('majdRblKode',$kodeRombel)
				->groupBy('majdRblKode')
				->get();
			}
			else{
				$data = Master_mapel_jadwal::where('majdUgrId',$this->GetIdGuru())
				->where('majdSklId',$skl)
				->groupBy('majdRblKode')
				->get();
			}
		}else{
			$data = [];
		}
		
		return $data;
		
	}
	function getBulanTahunAbsen(){
    if (Cache::has('ta_asben'.$this->getSkl())){ $data = Cache::get('ta_asben'.$this->getSkl()); }
    else{ 
    $data = Absen_finger_siswa::select(DB::raw('MONTH(afsDatetime) AS bulan,YEAR(afsDatetime) AS tahun'))
    ->groupBy('tahun')
    ->get();
     Cache::put('ta_asben'.$this->getSkl(), $data, ChaceJam());
    }
    return $data;
    
  }
  
//------------------------------------------------------------------------------------------------------------------
	//jadwal mapel absensi ---------------------------------------------------------------
	/* Menu Absensi ------------------------------*/ 
	function GuruJadwalMapelAbsen(){
		
			$params = [
				'title'	=>'Jadwal Mapel Absensi',
				'label' =>'DATA JADWAL MAPEL ABSENSI',
				'getSekolah' => $this->getSekolah(),
				'getKelas'	=> $this->getLevelKelas(),
			];
			return view('guru/Mapelabsensi/jadwal_mapel_absensi')->with($params);
	}
	// function GuruInsertJadwalMapelAbsen(Request $request){
		
	// 	$skl=decrypt_url($request->skl);
	// 	$getMapel = Master_mapel::where('mapelKode',$request->mapel)->first();
	// 	$getKelas = Master_kelas::where('klsId',$request->kelas)->first();
	// 	$rombel = $request->rbl;
		
	// 	//cek berdasarlam kode mapel, kelas, guru, hari
	// 	$cek = Master_mapel_jadwal::where('majdMapelKode',$request->mapel)
	// 	->where('majdKlsKode',$getKelas->klsKode)
	// 	->where('majdUgrId',Auth::user()->ugrId)
	// 	->where('majdHari',$request->hari);

	// 	if($cek->count() > 0){
	// 		$data=$cek->first();
	// 		$response = ['error'=>'Jadwal Mapel Sudah ada, '.$data->majdNama.' Gurunya '.$data->user_guru->ugrFirstName.' '.$data->user_guru->ugrLastName];
	// 		return response()->json($response,202);
	// 	}
	// 	else{
	// 		//di gunakan sebagai kode id random agar tidak sama serta untuk kode penghubung
	// 		//pada tabel mapel anggota rombel jadwal mapel 
	// 		$kodeJadwalMapel = $getKelas->klsKode.$request->mapel.'_'.time();
		
	// 		$data = array(
	// 			'majdKodeId'	=>$kodeJadwalMapel, 
	// 			'majdSklId' => $skl,
	// 			'majdTahunAjaran' => $this->getTahunAjaran(),
	// 			'majdUgrId' => Auth::user()->ugrId,
	// 			'majdKlsKode' => $getKelas->klsKode,
	// 			'majdMapelKode' => $request->mapel,
	// 			'majdNama' => $getMapel->mapelNama,
	// 			'majdHari' => $request->hari,
	// 			'majdJamKe' => $request->mapeljamke,
	// 			'majdJamMulai' => $request->jamin,
	// 			'majdJamAkhir' => $request->jamout,
	// 			'majdCreatedBy' => Auth::user()->ugrId,
	// 		);
	// 		$cek = Master_mapel_jadwal::insert($data);
  //   	if($cek){
	// 			//cari id mapel jadawl
	// 			$carjadwal = Master_mapel_jadwal::where('majdKodeId',$kodeJadwalMapel)->first();
	// 			//loping berapa rombel yang akan di masukan sesuai inputan
	// 			foreach($rombel as $item){
	// 				$carRombel = $this->getIdRombel($item);
	// 				$data2 = array(
	// 					'mjrSklId'	=> $skl,
	// 					'mjrRombelId'	=> $item, //kode rombel
	// 					'mjrMapelJadwal' => $carjadwal->majdId,
	// 					'mjrTahunAjaran' =>$this->getTahunAjaran(),
	// 					'mjrNamaRombel'	=> $getKelas->klsKode.$carRombel->rblNama,
	// 					'mjrHari'	=> $request->hari,
	// 					'mjrJamke'	=> $request->mapeljamke,
	// 				);
	// 				$data3[]=$data2;
	// 				//hapus cache siswa jadwal rombel
	// 				Cache::forget('jadwal.mapel.rombel'.$carRombel->rblId);
	// 				Cache::forget('jadwalmapel'.$this->GetIdGuru());

	// 			}
	// 			//simpan data array
	// 			Master_mapel_jadwal_rombel::insert($data3);
	// 			//tampung array aktiftas
	// 			$dataArray = array(
	// 				'laIdGuru' =>Auth::user()->ugrId,
	// 				'laNamaUser' => FullNamaGuru(),
	// 				'laNamaAksi' =>'Menambah Jadwal Mapel Guru '.$getMapel->mapelNama.' '.$request->hari,
	// 				'laDateTIme' =>date("Y-m-d H:i:s"),
	// 			);
	// 			//save aktifitas kegiatan
	// 			Log_aksi_user::insert($dataArray);
	// 			$response = ['save'=>'Berhasil Tambah Jadwal Mapel Guru'];
	// 			return response()->json($response,200);
	// 		}
	// 		else{ 
	// 			$response = ['error'=>'Opsss Gagal !!!'];
	// 			return response()->json($response,202);
	// 		}
		
	// 	} //end else
		

	// }

	//insert jadwal mapel absensi guru per rombel 
	function GuruInsertJadwalMapelAbsen(Request $request){
		$skl=decrypt_url($request->skl);
		$getMapel = Master_mapel::where('mapelKode',$request->mapel)->first();
		$getKelas = Master_kelas::where('klsId',$request->kelas)->first();
		$rombel = $request->rbl;
		$idRombel = $this->getIdRombel($rombel);
		
		//di gunakan sebagai kode id random agar tidak sama serta untuk kode penghubung
		//pada tabel mapel anggota rombel jadwal mapel 
		$kodeJadwalMapel = $getKelas->klsKode.$request->mapel.'_'.time();
		$data = array(
			'majdKodeId'	=>$kodeJadwalMapel, 
			'majdSklId' => $skl,
			'majdRblKode' => $rombel,
			'majdTahunAjaran' => $this->getTahunAjaran(),
			'majdUgrId' => Auth::user()->ugrId,
			'majdKlsKode' => $getKelas->klsKode,
			'majdMapelKode' => $request->mapel,
			'majdNama' => $getMapel->mapelNama,
			'majdHari' => $request->hari,
			'majdJamKe' => $request->mapeljamke,
			'majdJamMulai' => $request->jamin,
			'majdJamAkhir' => $request->jamout,
			'majdCreatedBy' => Auth::user()->ugrId,
		);
		$save = Master_mapel_jadwal::insert($data);
		if($save){
			//hapus cache siswa jadwal rombel
			Cache::forget('jadwal.mapel.rombel'.$idRombel->rblId);
			Cache::forget('jadwalmapel'.$this->GetIdGuru());
			$dataArray = array(
				'laIdGuru' =>Auth::user()->ugrId,
				'laNamaUser' => FullNamaGuru(),
				'laNamaAksi' =>'Menambah Jadwal Mapel Guru '.$getMapel->mapelNama.' '.$request->hari,
				'laDateTIme' =>date("Y-m-d H:i:s"),
			);
			//save aktifitas kegiatan
			Log_aksi_user::insert($dataArray);
			$response = ['save'=>'Berhasil Tambah Jadwal Mapel Guru'];
			return response()->json($response,200);
		}
		else{ 
			$response = ['error'=>'Opsss Gagal !!!'];
			return response()->json($response,202);
		}

	}
	// function GuruUpdateJadwal(Request $request){
		
	// 	$id = Crypt::decrypt($request->eid);
	// 	$data = Master_mapel_jadwal::find($id);
	// 	$data->majdHari = $request->ehari;
	// 	$data->majdJamKe = $request->emapeljamke;
	// 	$data->majdJamMulai = $request->ejamin;
	// 	$data->majdJamAkhir = $request->ejamout;
	// 	$data->madjTampilkan = $request->etampil;
	// 	$data->majdIsActive = $request->eaktif;
	// 	$data->mdjdUpdated = date("Y-m-d H:i:s");
	// 	$data->majdUpdatedBy = Auth::user()->ugrId;
	// 	if($data->save()){
	// 		$dataArray = array(
	// 			'laIdGuru' =>Auth::user()->ugrId,
	// 			'laNamaUser' => FullNamaGuru(),
	// 			'laNamaAksi' =>'Update Jadwal '.$data->majdNama.' Ke Hari '.hariIndo($request->ehari),
	// 			'laDateTIme' =>date("Y-m-d H:i:s"),
	// 		);
	// 		Log_aksi_user::insert($dataArray);
	// 		$dataRombelJadwal = Master_mapel_jadwal_rombel::where('mjrMapelJadwal',$id)->get();
	// 		//dd($dataRombelJadwal);
	// 		foreach($dataRombelJadwal as $item){
	// 			$idrombel = $this->getIdRombel($item->mjrRombelId);
	// 			//hapus cache siswa jadwal rombel
	// 			Cache::forget('jadwal.mapel.rombel'.$idrombel['rblId']);
	// 			Cache::forget('jadwalmapel'.$this->GetIdGuru());
	// 		}
	// 		$response = ['save'=>'Berhasil Update Jadwal'];
	// 		return response()->json($response,200);
	// 	}
	// 	else{ 
	// 		$response = ['error'=>'Opsss Gagal !!!'];
	// 		return response()->json($response,202);
	// 	}


	// }
	function GuruUpdateJadwal(Request $request){
		$idRombel = $this->getIdRombel($request->erbl);

		$id = Crypt::decrypt($request->eid);
		$data = Master_mapel_jadwal::find($id);
		$data->majdHari = $request->ehari;
		$data->majdJamKe = $request->emapeljamke;
		$data->majdJamMulai = $request->ejamin;
		$data->majdJamAkhir = $request->ejamout;
		$data->madjTampilkan = $request->etampil;
		$data->majdIsActive = $request->eaktif;
		$data->mdjdUpdated = date("Y-m-d H:i:s");
		$data->majdUpdatedBy = Auth::user()->ugrId;
		
		if($data->save()){
			$dataArray = array(
				'laIdGuru' =>Auth::user()->ugrId,
				'laNamaUser' => FullNamaGuru(),
				'laNamaAksi' =>'Update Jadwal '.$data->majdNama.' Ke Hari '.hariIndo($request->ehari),
				'laDateTIme' =>date("Y-m-d H:i:s"),
			);
			Log_aksi_user::insert($dataArray);
			Cache::forget('jadwal.mapel.rombel'.$idRombel);
			Cache::forget('jadwalmapel'.$this->GetIdGuru());
			
			$response = ['save'=>'Berhasil Update Jadwal'];
			return response()->json($response,200);
		}
		else{ 
			$response = ['error'=>'Opsss Gagal !!!'];
			return response()->json($response,202);
		}


	}

	function GuruHapusJadwal(Request $request){
		
		$id = Crypt::decrypt($request->id);
		$data = Master_mapel_jadwal::find($id);
		$data->delete();
		if($data ==true){
			$dataArray = array(
				'laIdGuru' =>Auth::user()->ugrId,
				'laNamaUser' => FullNamaGuru(),
				'laNamaAksi' =>'Menghapus Jadwal '.$data->majdNama.' Pada Hari '.hariIndo($data->majdHari),
				'laDateTIme' =>date("Y-m-d H:i:s"),
			);
			Log_aksi_user::insert($dataArray);
			Cache::forget('jadwalmapel'.$this->GetIdGuru());
			$response = ['success'=>'Berhasil Hapus Jadwal'];
			return response()->json($response,200);
		}
		else{ 
			$response = ['error'=>'Opsss Gagal !!!'];
			return response()->json($response,202);
		}
	}
	
	function JsonGuruJadwalMapel(){
		//cache data redis ---------------------------------------------------------------------------
		if (Cache::has('jadwalmapel'.$this->GetIdGuru())){ $data= Cache::get('jadwalmapel'.$this->GetIdGuru()); }
		else{ 
			$data = Master_mapel_jadwal::where('majdUgrId',Auth::user()->ugrId)->get();
			
			Cache::put('jadwalmapel'.$this->GetIdGuru(), $data, ChaceJam() );
			
		}
		
		$dt= DataTables::of($data)
			
			->addColumn('rombel',function ($data) { 
				// $items = $this->getRombelMapelJadwal($data->majdId);
				// foreach($items as $item){
				// 	$val[] = $item['namarombel'];
				// }
				// return implode(',',$val);
				$rombel = Master_rombel::where('rblKode',$data->majdRblKode)->with('master_kelas')->first();
				return $rombel->master_kelas->klsKode.$rombel->rblNama;
			})
			->addColumn('namaguru',function ($data) { 
				$getGuru = User_guru::find($data->majdUgrId);
				return $getGuru->ugrFirstName.' '.$getGuru->ugrLastName;
			})
			->addColumn('sekolah',function ($data) { 
				$getGuru = Master_sekolah::find($data->majdSklId);
				return $getGuru->sklKode;
			})
			->addColumn('hari',function ($data) { 
				return hariIndo($data->majdHari);
			})
			->addColumn('no',function ($data) { 
				return null;
			})
			->addColumn('status',function ($data) { 
				if($data->majdIsActive == 1){
					$item1 = "<sapn class='badge badge-success' >AKTIF</sapn>";
				}
				else{
					$item1 = "<sapn class='badge badge-danger' >TIDAK AKTIF</sapn>";
				}
				return $item1;
			})
			->addColumn('tampil',function ($data) { 
				if($data->madjTampilkan == 1){
					$item1 = "<sapn class='badge badge-success' >TAMPILKAN</sapn>";
				}
				else{
					$item1 = "<sapn class='badge badge-danger' >TIDAK</sapn>";
				}
				return $item1;
			})
			->addColumn('aksi',function ($data) { 
				$id = Crypt::encrypt($data->majdId);
				$hari = $data->majdHari;
				$jamin = $data->majdJamMulai;
				$jamout = $data->majdJamAkhir;
				$jamke = $data->majdJamKe;
				$tampil = $data->madjTampilkan;
				$aktif = $data->majdIsActive;
				$rbl = $data->majdRblKode;

				$button = '<a data-rbl="'.$rbl.'" data-aktif="'.$aktif.'" data-id="'.$id.'" data-tampil="'.$tampil.'" data-hari="'.$hari.'" data-jamin="'.$jamin.'" data-jamout="'.$jamout.'" data-jamke="'.$jamke.'" data-toggle="modal" data-target="#modal_form_vertical" title="Edit Data" class="editjadwal dropdown-item btn btn-sm btn-outline bg-primary text-primary border-primary legitRipple" ><i class="icon-pencil7"> Edit</i></a>';
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
			->rawColumns(['aksi','status','tampil']);
		return $dt->make(true); 
		
	}
	
// cari absensi mapel ------------------------------------------------------------
	function MapelAbsenManualGuru(){
		if(!empty($_GET['rbl'])){
			$rbl = decrypt_url($_GET['rbl']);
			//cache data redis ---------------------------------------------------------------------------
			if (Cache::has('getsiswarombel'.$rbl)){ $data= Cache::get('getsiswarombel'.$rbl); }
			else{
				$data = User_siswa::where('ssaRblId',$rbl)
				->orderBy("ssaFirstName")
				->get();
				Cache::put('getsiswarombel'.$rbl, $data, ChaceJam() );
			}
			//cache data redis ---------------------------------------------------------------------------
		}
		else{
			$data=[];
		}
		
		$params = [
			'title'	=>'Absensi Mapel Manual',
			'label' =>'ABSENSI MAPEL MANUAL',
			'getSekolah' => $this->getSekolah(),
			'getSiswa' =>$data,
			'getJadwal' => $this->getJadwalMapel(),
			
		];
		return view('guru/Mapelabsensi/mapel_absen_manual')->with($params);
	}

	//insert guru melakukan tambah data absen
	function InsertMapelAbsenManualGuru(Request $request){
	
		$namahari = date('l', strtotime($request->tgl));
		$tgl = date('Y-m-d', strtotime($request->tgl));
		$tahunajaran = $this->getTahunAjaran();
		//get jadwal mapel
		$jadwal = Master_mapel_jadwal::find($request->mapel);
		$rombel = decrypt_url($request->rbl);
		$mapel = $request->mapel;
		$datasiswa = $request->cekpilih;
		$dataStatus = $request->status;
		$dataKtr = $request->ktr;
		
		$skl = decrypt_url($request->skl);
		$semster = $this->getSemester();

		if(!empty($datasiswa)){
			//$jml = count($datasiswa);
			$sudahada=0; $belumada=0;
			foreach($datasiswa as $data){
				$cek = Absen_mapel::where('abmpSsaUsername',$data)
				->where('abmpMajdId',$request->mapel)
				->where('abmpTgl',$tgl)
				->count();
				if($cek > 0){
					$sudahada++;
				}
				else{
					foreach($dataStatus as $key => $val){
						if($data == $key){
							$dataKehadiran = $val;
						}
					}
					foreach($dataKtr as $key2 => $val2){
						if($data == $key2){
							$dataaKeterangan = $val2;
						}
					}
					$data2[]=array(
						'abmpSklId' => $skl,
						'abmpRblId' => $rombel,
						'abmpMajdId' => $mapel,
						'abmpTajrKode' =>$tahunajaran,
						'abmpSmKode'	=> $semster,
						'abmpSsaUsername' => $data,
						'abmpTgl' => $tgl,
						'abmpHari' => $namahari,
						'abmpJamin' =>$jadwal->majdJamMulai,
						'abmpJamout' =>$jadwal->majdJamAkhir,
						'abmpAkKode' =>$dataKehadiran, //status kehaidran siswa
						'abmpKeterangan' => $dataaKeterangan,
						'abmpUserAksi' =>2,
						);
						$belumada++;
				
					//cek apakah sudah ada absensi sekolah
					$cekAbsenSekolah  = Absen_finger_siswa::where('afsSsaUsername',$data)
					->where('afsDatetime',$tgl)->count();
					if($cekAbsenSekolah > 0 ){ }else{
						//$status = CekKehadiranJamMasuk($jadwal->majdJamMulai);
						//karna COVID-19 jadi sistem absen sekolah di buat hadir semua
						$status=$dataKehadiran;
						$dataAbsenSekolah[]=array(
							'afsSklId' =>$skl,
							'afsSsaUsername' =>$data,
							'afsRblId'	=>$rombel,
							'afsAkId'		=>$status,
							'afsDatetime' => $tgl,
							'afsIn' => $jadwal->majdJamMulai,
							'afsOut' => $jadwal->majdJamAkhir,
							'afsHari' => $namahari,
							'afsJenis' => 2,
							'afsSemester' =>$semster,
							'afsTahunAjaran' =>$tahunajaran,
							'afsKeterangan' => $dataaKeterangan,

						);
					}

				}

			} //end foreach
			//cek apakah array data2 kosong 
			if(empty($data2)){
				Cache::forget('rekap_absen_siswa'.$rombel.$mapel);
				Cache::forget('total_absen_siswa'.$rombel.$mapel);
				$response = ['error'=> $sudahada.' Siswa Sudah Absen'];
				return response()->json($response,202);
			}
			else{
				$add = Absen_mapel::insert($data2);
				if($add){
					if(!empty($dataAbsenSekolah)){
						Absen_finger_siswa::insert($dataAbsenSekolah);
					}
					$response = ['success'=>'Berhasil Tambah Absen '.$belumada.' Siswa | '.$sudahada.' Sudah Ada Absen'];
					return response()->json($response,200);
				}
				else{
					$response = ['error'=>'Gagal Tambah Absen Siswa'];
					return response()->json($response,202);
				}
			}

		} //end if empty
		else{
			$response = ['error'=>'Silahkan Pilih Siswa Terlebih Dahulu'];
			return response()->json($response,202);
		}
		
	} //end function
// cari absensi mapel izin ------------------------------------------------------------
	function MapelAbsenManualGuruIzin(){
		if(!empty($_GET['rbl'])){
			$rbl = decrypt_url($_GET['rbl']);
			//cache data redis ---------------------------------------------------------------------------
			if (Cache::has('getsiswarombel'.$rbl)){ $data= Cache::get('getsiswarombel'.$rbl); }
			else{
				$data = User_siswa::where('ssaRblId',$rbl)
				->orderBy("ssaFirstName")
				->get();
				Cache::put('getsiswarombel'.$rbl, $data, ChaceJam() );
			}
			//cache data redis ---------------------------------------------------------------------------
		}
		else{
			$data=[];
		}
		
		$params = [
			'title'	=>'Absensi Mapel Manual',
			'label' =>'ABSENSI MAPEL MANUAL',
			'getSekolah' => $this->getSekolah(),
			'getSiswa' =>$data,
			'getJadwal' => $this->getJadwalMapel(),
			
		];
		return view('guru/Mapelabsensi/mapel_absen_manual_izin')->with($params);
	}
	//untuk proses izin absen mata pelajaran
	function InsertMapelAbsenIzinGuru(Request $request){
		$namahari = date('l', strtotime($request->tgl));
		$tgl = date('Y-m-d', strtotime($request->tgl));
		$tahunajaran = $this->getTahunAjaran();
		$status_absen = $request->status;
		$keterangan = $request->keterangan;

		//get jadwal mapel
		$jadwal = Master_mapel_jadwal::find($request->mapel);
		$rombel = decrypt_url($request->rbl);
		$mapel = $request->mapel;
		$datasiswa = $request->cekpilih;
		$skl = decrypt_url($request->skl);
		$semster = $this->getSemester();

		if(!empty($datasiswa)){
			//$jml = count($datasiswa);
			$sudahada=0; $belumada=0;
			foreach($datasiswa as $data){
				$cek = Absen_mapel::where('abmpSsaUsername',$data)
				->where('abmpMajdId',$request->mapel)
				->where('abmpTgl',$tgl)->first();

				//cek apakah absensi izin mapel sudaha da
				if(!empty($cek)){
					$sudahada++;
					Absen_mapel::where('abmpId',$cek->abmpId)->update(array(
						'abmpSklId' => $skl,
						'abmpRblId' => $rombel,
						'abmpMajdId' => $mapel,
						'abmpTajrKode' =>$tahunajaran,
						'abmpSmKode'	=> $semster,
						'abmpSsaUsername' => $data,
						'abmpTgl' => $tgl,
						'abmpHari' => $namahari,
						'abmpJamin' =>$jadwal->majdJamMulai,
						'abmpJamout' =>$jadwal->majdJamAkhir,
						'abmpAkKode' => $status_absen, //status kehaidran siswa
						'abmpKeterangan' => $keterangan,
						'abmpUserAksi' =>2,
					));
				}
				else{
					$data2[]=array(
						'abmpSklId' => $skl,
						'abmpRblId' => $rombel,
						'abmpMajdId' => $mapel,
						'abmpTajrKode' =>$tahunajaran,
						'abmpSmKode'	=> $semster,
						'abmpSsaUsername' => $data,
						'abmpTgl' => $tgl,
						'abmpHari' => $namahari,
						'abmpJamin' =>$jadwal->majdJamMulai,
						'abmpJamout' =>$jadwal->majdJamAkhir,
						'abmpAkKode' => $status_absen, //status kehaidran siswa
						'abmpKeterangan' => $keterangan,
						'abmpUserAksi' =>2,
						);
						$belumada++;
				
					//cek apakah sudah ada absensi sekolah
					$cekAbsenSekolah  = Absen_finger_siswa::where('afsSsaUsername',$data)
					->where('afsDatetime',$tgl)->first();
					if(!empty($cekAbsenSekolah)){ 
						//jangan lakukan update ke absensi finger print atau sekolah melalui absensi mapel
						//jika suda ada absensi sekolah makan lakukan upadate
						// Absen_finger_siswa::where('afsId',$cekAbsenSekolah->afsId)->update(array(
						// 	'afsSklId' =>$skl,
						// 	'afsSsaUsername' =>$data,
						// 	'afsRblId'	=>$rombel,
						// 	'afsAkId'		=>$status_absen,
						// 	'afsDatetime' => $tgl,
						// 	'afsIn' => $jadwal->majdJamMulai,
						// 	'afsOut' => $jadwal->majdJamAkhir,
						// 	'afsHari' => $namahari,
						// 	'afsJenis' => 2,
						// 	'afsSemester' =>$semster,
						// 	'afsTahunAjaran' =>$tahunajaran,
						// ));
					}else{
						//$status = CekKehadiranJamMasuk($jadwal->majdJamMulai);
						//karna COVID-19 jadi sistem absen sekolah di buat hadir semua
						$dataAbsenSekolah[]=array(
							'afsSklId' =>$skl,
							'afsSsaUsername' =>$data,
							'afsRblId'	=>$rombel,
							'afsAkId'		=>$status_absen,
							'afsDatetime' => $tgl,
							'afsIn' => $jadwal->majdJamMulai,
							'afsOut' => $jadwal->majdJamAkhir,
							'afsHari' => $namahari,
							'afsJenis' => 2,
							'afsSemester' =>$semster,
							'afsTahunAjaran' =>$tahunajaran,
							'afsKeterangan' =>$keterangan,

						);
					}

				}

			} //end foreach
			//cek apakah array data2 kosong 
			if(empty($data2)){
				Cache::forget('rekap_absen_siswa'.$rombel.$mapel);
				Cache::forget('total_absen_siswa'.$rombel.$mapel);
				$response = ['success'=> 'Absen '.$sudahada.' Siswa Sudah Di Update'];
				return response()->json($response,200);
			}
			else{
				$add = Absen_mapel::insert($data2);
				if($add){
					if(!empty($dataAbsenSekolah)){
						Absen_finger_siswa::insert($dataAbsenSekolah);
					}
					$response = ['success'=>'Berhasil Tambah Absen '.$belumada.' Siswa | '.$sudahada.' Sudah Ada Absen'];
					return response()->json($response,200);
				}
				else{
					$response = ['error'=>'Gagal Tambah Absen Siswa'];
					return response()->json($response,202);
				}

			}

		} //end if empty
		else{
			$response = ['error'=>'Silahkan Pilih Siswa Terlebih Dahulu'];
			return response()->json($response,202);
		}
	}


//rekap absen mapel ---------------------------------------------------------------------

	
	function RincianMapelAbsenGuru(){
		//rincian absensi siswa mapel jadwal guru per hari
		$params = [
			'title'	=>'Rincian Absensi Mapel ',
			'label' =>'RINCIAN ABSENSI MAPEL',
			'getSekolah' => $this->getSekolah(),
			'getJadwal' => $this->getJadwalMapel(),
			
		];
		return view('guru/Mapelabsensi/rekap_mapel_absen')->with($params);
	}

	function TotalMapelAbsenGuruBulan(){
		//total absen perbulan 31 hari mapel jadwal guru LUKAHITS
		$params = [
			'title'	=>'Rekap Absensi Mapel Bulan',
			'label' =>'REKAP ABSENSI MAPEL BULAN',
			'getSekolah' => $this->getSekolah(),
			'getJadwal' => $this->getJadwalMapel(),
			
		];
		return view('guru/Mapelabsensi/total_mapel_absen')->with($params);
	}
	function TotalALLMapelAbsenGuru(){
		//total absen perbulan 31 hari mapel jadwal guru LUKAHITS
		$params = [
			'title'	=>'Rekap All Mapel Absen ',
			'label' =>'REKAP ALL MAPEL ABSEN',
			'getSekolah' => $this->getSekolah(),
			'getJadwal' => $this->getJadwalMapelOne(),
			
		];
		return view('guru/Mapelabsensi/total_all_absen')->with($params);
	}

	function JsonRekapAbsenMapel(Request $request){
		//pada menu rincian absensi mapel 
		$tahunajaran = $this->getTahunAjaran();
		$semster = $this->getSemester();
		$skl = decrypt_url($request->input('skl'));
    $rbl = decrypt_url($request->input('amp;rbl'));
    $mpl = decrypt_url($request->input('amp;mpl'));
		$bln = decrypt_url($request->input('amp;bln'));
		//cache data redis ---------------------------------------------------------------------------
		// if (Cache::has('rekap_absen_siswa'.$rbl.$mpl)){ $data= Cache::get('rekap_absen_siswa'.$rbl.$mpl); }
		// else{ 
			$data = Absen_mapel::where('abmpRblId',$rbl)
			->where('abmpMajdId',$mpl)
			->where('abmpTajrKode',$tahunajaran)
			->where('abmpSmKode',$semster)
			->whereMonth('abmpTgl', $bln)
			->with('master_mapel_jadwal')
			->with('master_rombel')
			->with('user_siswa')
			->with('absen_kategori')
			->with('master_kelas')
			->get();
		// 	Cache::put('rekap_absen_siswa'.$rbl.$mpl, $data, ChaceMenit() );
		// }

		$dt= DataTables::of($data)
		->addColumn('no','')
		->addColumn('nama_mapel',function ($data) { 
			return $data->master_mapel_jadwal->majdNama;
		})
		->addColumn('nama_siswa',function ($data) { 
			return $data->user_siswa->ssaFirstName.' '.$data->user_siswa->ssaLastName;
		})
		->addColumn('username',function ($data) { 
			return $data->user_siswa->ssaUsername;
		})
		->addColumn('rombel',function ($data) { 
			return $data->master_rombel->master_kelas->klsKode.' '.$data->master_rombel->rblNama;
		})
		->addColumn('tanggal',function ($data) { 
			$tgl = date('d-m-Y', strtotime($data->abmpTgl));
			return $tgl;
		})
		->addColumn('hari',function ($data) { 
			return hariIndo($data->abmpHari);
		})
		->addColumn('status',function ($data) { 
			return getStatusAbsensi($data->abmpAkKode);
		})
		->addColumn('usercek',function ($data) { 
			if($data->abmpUserAksi == 1){
				$user = '<span class="badge badge-primary">Siswa</span>';
			}
			elseif($data->abmpUserAksi == 2){
				$user = '<span class="badge badge-success">Guru</span>';
			}
			else{
				$user = '<span class="badge badge-warning">Sistem</span>';
			}
			return $user;
		})
		->addColumn('keterangan',function ($data) { 
			return $data->abmpKeterangan;
		})
		->rawColumns(['usercek','status','keterangan']);
		return $dt->make();
		
	}
	function JsonTotalAbsenMapel(Request $request){
		//pada menu total absensi mapel 
		$tahunajaran = $this->getTahunAjaran();
		$semster = $this->getSemester();
		$skl = decrypt_url($request->input('skl'));
    $rbl = decrypt_url($request->input('amp;rbl'));
    $mpl = decrypt_url($request->input('amp;mpl'));
		$bln = decrypt_url($request->input('amp;bln'));
		//cache data redis ---------------------------------------------------------------------------
		// if (Cache::has('total_absen_siswa'.$rbl.$mpl)){ $data= Cache::get('total_absen_siswa'.$rbl.$mpl); }
		// else{ 
			$data = Absen_mapel::where('abmpRblId',$rbl)
				->where('abmpMajdId',$mpl)
				->where('abmpTajrKode',$tahunajaran)
				->where('abmpSmKode',$semster)
				->whereMonth('abmpTgl', $bln)
				->with('master_mapel_jadwal')
				->with('master_rombel')
				->with('user_siswa')
				->with('absen_kategori')
				->selectRaw("*,
					SUM(CASE WHEN abmpAkKode='K' THEN 1 ELSE 0 END) AS KEGIATAN,
					SUM(CASE WHEN abmpAkKode='U' THEN 1 ELSE 0 END) AS ULANGAN,
					SUM(CASE WHEN abmpAkKode='L' THEN 1 ELSE 0 END) AS LIBUR,
					SUM(CASE WHEN abmpAkKode='H' THEN 1 ELSE 0 END) AS HADIR,
					SUM(CASE WHEN abmpAkKode='A' THEN 1 ELSE 0 END) AS ALPHA,
					SUM(CASE WHEN abmpAkKode='B' THEN 1 ELSE 0 END) AS BOLOS,
					SUM(CASE WHEN abmpAkKode='I' THEN 1 ELSE 0 END) AS IZIN,
					SUM(CASE WHEN abmpAkKode='T' THEN 1 ELSE 0 END) AS TERLAMBAT,
					SUM(CASE WHEN abmpAkKode='S' THEN 1 ELSE 0 END) AS SAKIT
					")
				->groupBy('abmpSsaUsername')
				->get();
		// 		Cache::put('total_absen_siswa'.$rbl.$mpl, $data, ChaceMenit() );
		// }
		
		$dt= DataTables::of($data)
		->addColumn('no','')
		->addColumn('rombel',function ($data) { 
			return $data->master_rombel->master_kelas->klsKode.' '.$data->master_rombel->rblNama;
		})
		->addColumn('nama_siswa',function ($data) { 
			return $data->user_siswa->ssaFirstName.' '.$data->user_siswa->ssaLastName;
		})
		->addColumn('total',function ($data) { 
			$total = ($data->HADIR + $data->KEGIATAN + $data->ULANGAN + $data->TERLAMBAT );
			return $total; 
		})
		
		;
		return $dt->make();

	}
	function JsonTotalAllAbsenMapel(Request $request){
		//pada menu total absensi mapel 
		$tahunajaran = $this->getTahunAjaran();
		$semster = $this->getSemester();
		$skl = decrypt_url($request->input('skl'));
    $rbl = decrypt_url($request->input('amp;rbl'));
    $mpl = decrypt_url($request->input('amp;mpl'));
		$bln = decrypt_url($request->input('amp;bln'));
		//cache data redis ---------------------------------------------------------------------------
		// if (Cache::has('total_absen_siswa'.$rbl.$mpl)){ $data= Cache::get('total_absen_siswa'.$rbl.$mpl); }
		// else{ 
			$data = Absen_mapel::where('abmpRblId',$rbl)
				//->where('abmpMajdId',$mpl)
				->where('abmpTajrKode',$tahunajaran)
				->where('abmpSmKode',$semster)
				->whereMonth('abmpTgl', $bln)
				->with('master_mapel_jadwal')
				->with('master_rombel')
				->with('user_siswa')
				->with('absen_kategori')
				->selectRaw("*,
					SUM(CASE WHEN abmpAkKode='K' THEN 1 ELSE 0 END) AS KEGIATAN,
					SUM(CASE WHEN abmpAkKode='U' THEN 1 ELSE 0 END) AS ULANGAN,
					SUM(CASE WHEN abmpAkKode='L' THEN 1 ELSE 0 END) AS LIBUR,
					SUM(CASE WHEN abmpAkKode='H' THEN 1 ELSE 0 END) AS HADIR,
					SUM(CASE WHEN abmpAkKode='A' THEN 1 ELSE 0 END) AS ALPHA,
					SUM(CASE WHEN abmpAkKode='B' THEN 1 ELSE 0 END) AS BOLOS,
					SUM(CASE WHEN abmpAkKode='I' THEN 1 ELSE 0 END) AS IZIN,
					SUM(CASE WHEN abmpAkKode='T' THEN 1 ELSE 0 END) AS TERLAMBAT,
					SUM(CASE WHEN abmpAkKode='S' THEN 1 ELSE 0 END) AS SAKIT
					")
				->groupBy('abmpSsaUsername')
				->get();
		// 		Cache::put('total_absen_siswa'.$rbl.$mpl, $data, ChaceMenit() );
		// }
		
		$dt= DataTables::of($data)
		->addColumn('no','')
		->addColumn('rombel',function ($data) { 
			return $data->master_rombel->master_kelas->klsKode.' '.$data->master_rombel->rblNama;
		})
		->addColumn('nama_siswa',function ($data) { 
			return $data->user_siswa->ssaFirstName.' '.$data->user_siswa->ssaLastName;
		})
		->addColumn('total',function ($data) { 
			$total = ($data->HADIR + $data->KEGIATAN + $data->ULANGAN + $data->TERLAMBAT );
			return $total; 
		})
		
		;
		return $dt->make();

	}

	/*End Menu Absensi ------------------------------*/ 

	/*Menu Rekap Absensi ---------------------------*/
	function RekapMapelAbsenGuruBulan(){
		//rekap absen rincial perbulan 31 hari mapel jadwal guru
		$params = [
			'title'	=>'Rekap Absensi Mapel Bulan',
			'label' =>'REKAP ABSENSI MAPEL BULAN',
			'getSekolah' => $this->getSekolah(),
			'getJadwal' => $this->getJadwalMapel(),
			
		];
		return view('guru/Mapelabsensi/rekap/rekap_mapel_absen_bulan')->with($params);
	}

	function CetakAbsenMapelDetail(Request $request){
		//cetak absen mepel detail
		$tahunajaran = $this->getTahunAjaran();
		$semster = $this->getSemester();
		$skl = decrypt_url($request->skl);
		$rbl = decrypt_url($request->input('rbl'));
		$mpl = decrypt_url($request->input('mapel'));
		$bln = decrypt_url($request->input('bln'));
		$mapel = Master_mapel_jadwal::find($mpl);
		$namaskl = Master_sekolah::find($skl);
		$namarombel = Master_rombel::with('master_kelas')->find($rbl);
		//nonaktifkan strict pada config/database.php
		//cache data redis ---------------------------------------------------------------------------
		if (Cache::has('cetak_rekap_absen'.$rbl.$mpl.$bln.$tahunajaran.$semster)){ 
			$data= Cache::get('cetak_rekap_absen'.$rbl.$mpl.$bln.$tahunajaran.$semster); 
		}
		else{ 
			$data = Absen_mapel::where('abmpRblId',$rbl)
			->where('abmpMajdId',$mpl)
			->where('abmpTajrKode',$tahunajaran)
			->where('abmpSmKode',$semster)
			->whereMonth('abmpTgl', $bln)
			->with('master_mapel_jadwal')
			->with('master_rombel')
			->with('user_siswa')
			->with('absen_kategori')
			->with('master_kelas')
			// ->join('user_siswa', 'ssaUsername', '=', 'abmpSsaUsername')
			->selectRaw("*,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(abmpTgl)=1,abmpAkKode,NULL))) AS tgl1,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(abmpTgl)=2,abmpAkKode,NULL))) AS tgl2,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(abmpTgl)=3,abmpAkKode,NULL))) AS tgl3,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(abmpTgl)=4,abmpAkKode,NULL))) AS tgl4,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(abmpTgl)=5,abmpAkKode,NULL))) AS tgl5,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(abmpTgl)=6,abmpAkKode,NULL))) AS tgl6,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(abmpTgl)=7,abmpAkKode,NULL))) AS tgl7,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(abmpTgl)=8,abmpAkKode,NULL))) AS tgl8,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(abmpTgl)=9,abmpAkKode,NULL))) AS tgl9,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(abmpTgl)=10,abmpAkKode,NULL))) AS tgl10,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(abmpTgl)=11,abmpAkKode,NULL))) AS tgl11,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(abmpTgl)=12,abmpAkKode,NULL))) AS tgl12,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(abmpTgl)=13,abmpAkKode,NULL))) AS tgl13,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(abmpTgl)=14,abmpAkKode,NULL))) AS tgl14,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(abmpTgl)=15,abmpAkKode,NULL))) AS tgl15,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(abmpTgl)=16,abmpAkKode,NULL))) AS tgl16,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(abmpTgl)=17,abmpAkKode,NULL))) AS tgl17,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(abmpTgl)=18,abmpAkKode,NULL))) AS tgl18,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(abmpTgl)=19,abmpAkKode,NULL))) AS tgl19,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(abmpTgl)=20,abmpAkKode,NULL))) AS tgl20,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(abmpTgl)=21,abmpAkKode,NULL))) AS tgl21,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(abmpTgl)=22,abmpAkKode,NULL))) AS tgl22,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(abmpTgl)=23,abmpAkKode,NULL))) AS tgl23,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(abmpTgl)=24,abmpAkKode,NULL))) AS tgl24,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(abmpTgl)=25,abmpAkKode,NULL))) AS tgl25,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(abmpTgl)=26,abmpAkKode,NULL))) AS tgl26,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(abmpTgl)=27,abmpAkKode,NULL))) AS tgl27,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(abmpTgl)=28,abmpAkKode,NULL))) AS tgl28,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(abmpTgl)=29,abmpAkKode,NULL))) AS tgl29,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(abmpTgl)=30,abmpAkKode,NULL))) AS tgl30,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(abmpTgl)=31,abmpAkKode,NULL))) AS tgl31,
				SUM(CASE WHEN abmpAkKode='K' THEN 1 ELSE 0 END) AS KEGIATAN,
				SUM(CASE WHEN abmpAkKode='U' THEN 1 ELSE 0 END) AS ULANGAN,
				SUM(CASE WHEN abmpAkKode='L' THEN 1 ELSE 0 END) AS LIBUR,
				SUM(CASE WHEN abmpAkKode='H' THEN 1 ELSE 0 END) AS HADIR,
				SUM(CASE WHEN abmpAkKode='A' THEN 1 ELSE 0 END) AS ALPHA,
				SUM(CASE WHEN abmpAkKode='B' THEN 1 ELSE 0 END) AS BOLOS,
				SUM(CASE WHEN abmpAkKode='I' THEN 1 ELSE 0 END) AS IZIN,
				SUM(CASE WHEN abmpAkKode='T' THEN 1 ELSE 0 END) AS TERLAMBAT,
				SUM(CASE WHEN abmpAkKode='S' THEN 1 ELSE 0 END) AS SAKIT
				")
			->groupBy('abmpSsaUsername')
			->get();
			Cache::put('cetak_rekap_absen'.$rbl.$mpl.$bln.$tahunajaran.$semster, $data, ChaceJam() );
		}
			$params = [
				'judul' =>'REKAPITULASI ABSEN SISWA',
				'sekolah' =>$namaskl->sklNama,
				'ajaran'	=>'TAHUN PELAJARAN ' .$this->getTahunAjaranNama(),
				'rombel'	=> $namarombel->master_kelas->klsNama.' '.$namarombel->rblNama,
				'absen' => $data,
				'mapel'	=>$mapel->majdNama,
				'bulan'	=> bulanIndo($bln),
				

			];
		
		return view('guru/Mapelabsensi/cetak_absen_mapel_detail')->with($params);
	}
	/* End Menu Rekap Absensi ---------------------------*/

//Absnesi Sekolah Sisa pada Bagian Guru -------------------------------------------------------------------
	function LihatAbsenSekolah(){
		$params = [
      'title' =>'Absen Finger Siswa',
      'label' =>'DATA ABSENS FINGER SISWA ',
      'getSekolah' => $this->getSekolah(),
      'getRombel' => $this->getRombel(),
      'getBulanTahunAbsen' => $this->getBulanTahunAbsen(),

    ];
    return view('guru/absen_sekolah/view_absen')->with($params);
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
        // ->addColumn('jurusan',function ($data) { 
        //     return $data->master_jurusan->jrsSlag;
        //   })
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

	public function ViewRekapAbsenFinger(Request $request){
		if(empty($request->input('skl'))){
			$data = [];
		}
		else{
			$skl = decrypt_url($request->input('skl'));
			$rbl = decrypt_url($request->input('rbl'));
			$thn = decrypt_url($request->input('thn'));
			$bln = decrypt_url($request->input('bln'));
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
      ->selectRaw("*,
          
          SUM(CASE WHEN afsAkId='K' THEN 1 ELSE 0 END) AS KEGIATAN,
          SUM(CASE WHEN afsAkId='U' THEN 1 ELSE 0 END) AS ULANGAN,
          SUM(CASE WHEN afsAkId='L' THEN 1 ELSE 0 END) AS LIBUR,
          SUM(CASE WHEN afsAkId='H' THEN 1 ELSE 0 END) AS HADIR,
          SUM(CASE WHEN afsAkId='A' THEN 1 ELSE 0 END) AS ALPHA,
          SUM(CASE WHEN afsAkId='B' THEN 1 ELSE 0 END) AS BOLOS,
          SUM(CASE WHEN afsAkId='I' THEN 1 ELSE 0 END) AS IZIN,
          SUM(CASE WHEN afsAkId='T' THEN 1 ELSE 0 END) AS TERLAMBAT,
          SUM(CASE WHEN afsAkId='S' THEN 1 ELSE 0 END) AS SAKIT
          ")
        ->groupBy('afsSsaUsername')
        ->get();
			
		}
		


    $params = [
      'title' =>'Absen Finger Siswa',
      'label' =>'DATA ABSENS FINGER SISWA ',
			'absen' => $data,
      'getSekolah' => $this->getSekolah(),
      'getRombel' => $this->getRombel(),
      'getBulanTahunAbsen' => $this->getBulanTahunAbsen(),

    ];
    return view('guru/absen_sekolah/view_rekap_absen_sekolah')->with($params);
  }
  public function CetakViewRekapAbsenFinger(Request $request){
    $skl = decrypt_url($request->input('skl'));
    $rbl = decrypt_url($request->input('rbl'));
    $thn = decrypt_url($request->input('thn'));
    $bln = decrypt_url($request->input('bln'));
    $namaskl = Master_sekolah::find($skl);
    $kepalaSekolah = User_guru::where('ugrSklId',$skl)
    ->where('ugrTugasTambahan','KEPSEK')->first();
		$namarombel = Master_rombel::with('master_kelas')->find($rbl);

    if (Cache::has('cetak_rekap_absen_finger'.$skl.$rbl.$thn.$bln)){ $data= Cache::get('cetak_rekap_absen_finger'.$skl.$rbl.$thn.$bln); }
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
      ->selectRaw("*,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(afsDatetime)=1,afsAkId,NULL))) AS tgl1,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(afsDatetime)=2,afsAkId,NULL))) AS tgl2,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(afsDatetime)=3,afsAkId,NULL))) AS tgl3,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(afsDatetime)=4,afsAkId,NULL))) AS tgl4,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(afsDatetime)=5,afsAkId,NULL))) AS tgl5,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(afsDatetime)=6,afsAkId,NULL))) AS tgl6,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(afsDatetime)=7,afsAkId,NULL))) AS tgl7,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(afsDatetime)=8,afsAkId,NULL))) AS tgl8,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(afsDatetime)=9,afsAkId,NULL))) AS tgl9,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(afsDatetime)=10,afsAkId,NULL))) AS tgl10,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(afsDatetime)=11,afsAkId,NULL))) AS tgl11,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(afsDatetime)=12,afsAkId,NULL))) AS tgl12,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(afsDatetime)=13,afsAkId,NULL))) AS tgl13,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(afsDatetime)=14,afsAkId,NULL))) AS tgl14,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(afsDatetime)=15,afsAkId,NULL))) AS tgl15,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(afsDatetime)=16,afsAkId,NULL))) AS tgl16,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(afsDatetime)=17,afsAkId,NULL))) AS tgl17,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(afsDatetime)=18,afsAkId,NULL))) AS tgl18,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(afsDatetime)=19,afsAkId,NULL))) AS tgl19,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(afsDatetime)=20,afsAkId,NULL))) AS tgl20,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(afsDatetime)=21,afsAkId,NULL))) AS tgl21,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(afsDatetime)=22,afsAkId,NULL))) AS tgl22,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(afsDatetime)=23,afsAkId,NULL))) AS tgl23,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(afsDatetime)=24,afsAkId,NULL))) AS tgl24,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(afsDatetime)=25,afsAkId,NULL))) AS tgl25,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(afsDatetime)=26,afsAkId,NULL))) AS tgl26,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(afsDatetime)=27,afsAkId,NULL))) AS tgl27,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(afsDatetime)=28,afsAkId,NULL))) AS tgl28,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(afsDatetime)=29,afsAkId,NULL))) AS tgl29,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(afsDatetime)=30,afsAkId,NULL))) AS tgl30,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(afsDatetime)=31,afsAkId,NULL))) AS tgl31,
          SUM(CASE WHEN afsAkId='K' THEN 1 ELSE 0 END) AS KEGIATAN,
          SUM(CASE WHEN afsAkId='U' THEN 1 ELSE 0 END) AS ULANGAN,
          SUM(CASE WHEN afsAkId='L' THEN 1 ELSE 0 END) AS LIBUR,
          SUM(CASE WHEN afsAkId='H' THEN 1 ELSE 0 END) AS HADIR,
          SUM(CASE WHEN afsAkId='A' THEN 1 ELSE 0 END) AS ALPHA,
          SUM(CASE WHEN afsAkId='B' THEN 1 ELSE 0 END) AS BOLOS,
          SUM(CASE WHEN afsAkId='I' THEN 1 ELSE 0 END) AS IZIN,
          SUM(CASE WHEN afsAkId='T' THEN 1 ELSE 0 END) AS TERLAMBAT,
          SUM(CASE WHEN afsAkId='S' THEN 1 ELSE 0 END) AS SAKIT
          ")
        ->groupBy('afsSsaUsername')
        ->get();
        
        Cache::put('cetak_rekap_absen_finger'.$skl.$rbl.$thn.$bln, $data, ChaceJam() );
    }
    if(empty($kepalaSekolah->ugrGelarBelakang)){
      $fullname = $kepalaSekolah->ugrFirstName.' '.$kepalaSekolah->ugrLastName.', '.$kepalaSekolah->ugrGelarBelakang;
    }
    else{
      $fullname = $kepalaSekolah->ugrGelarDepan.' '.$kepalaSekolah->ugrFirstName.' '.$kepalaSekolah->ugrLastName.', '.$kepalaSekolah->ugrGelarBelakang;
    }
    $params = [
      'judul' =>'REKAPITULASI ABSENSI SEKOLAH SISWA',
      'sekolah' =>$namaskl->sklNama,
      'ajaran'	=>'TAHUN PELAJARAN ' .$this->getTahunAjaranNama(),
      'rombel'	=> $namarombel->master_kelas->klsNama.' '.$namarombel->rblNama,
      'absen' => $data,
      'bulan'	=> bulanIndo($bln),
      'kecamatan'	=> 'Way Jepara',
      'kepsek'    =>'Kepala Sekolah',
      'tgl' => tgl_indo(date('Y-m-d')),
      'namaKepsek'  => $fullname,
      'tahun'  => $thn,

    ];
    return view('guru/absen_sekolah/cetak_absen_sekolah')->with($params);
    

  }



} //end CguruAbsensi Conttroller
