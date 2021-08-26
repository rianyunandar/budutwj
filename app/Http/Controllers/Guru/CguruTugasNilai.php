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
use App\User_siswa;
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
use App\El_tugas_nilai;
use App\Master_mapel;
use App\Semester;
use App\Tahun_ajaran;

use App\Log_aksi_user;


class CguruTugasNilai extends Controller
{
	public function __construct()
  {
		$this->middleware('auth:guru');
		
	}
	
	function GetIdGuru(){
		return  Auth::user()->ugrId; 

	}
	function getSekolah()
  {
    $data = new Master_sekolah();
    return $data->getSekolah();
	}
	function getKodeSemesterAktif(){
		$data = Semester::where('smIsActive',1)->first();
		return $data->smKode;
	}
	function getTahunAjaranAktif(){
		$data = Tahun_ajaran::where('tajrIsActive',1)->first();
		return $data->tajrKode;
	}
	
	// function getLevelKelas()
  // {
  //   if (Cache::has('master_kelas')){ $data = Cache::get('master_kelas'); }
  //   else{ 
  //     $data = Master_kelas::all();
  //     Cache::put('master_kelas', $data, ChaceJam());
  //   }
  //   return $data;
	// }
	// function getMapel($id=null)
  // {
	// 	//panggil mapel bisa berdasarkan kode atau semua
  //   $data = new Master_mapel();
  //   return $data->getMapelByKode($id);
	// }
	function cariRombelKode($id){
		$data = Master_rombel::find($id);
		return $data->rblKode;
	}
	function cariRombelId($id){
		$data = Master_rombel::where('rblKode',$id)->first();
		return $data->rblId;
	}
	function getKodeKelas($id){
		$data = Master_rombel::with('master_kelas')->find($id);
		return $data->master_kelas->klsKode;
	}

	function getJadwalMapel(){
		if(!empty($_GET['skl'])){
			//$skl=decrypt_url($_GET['skl']);
			if(!empty($_GET['rbl'])){ 
				$rbl = decrypt_url($_GET['rbl']);
				$kodeRombel = $this->cariRombelKode($rbl);
				$data = El_tugas_anggota_rombel::where('tgsarUgrId',$this->GetIdGuru())
				->where('tgsarRblKode',$kodeRombel)
				->with('El_tugas')
				->get();
			}
			else{
				// $data = Master_mapel_jadwal::where('majdUgrId',$this->GetIdGuru())
				// ->where('majdSklId',$skl)
				// ->get();
			}
		}else{
			$data = [];
		}
		
		return $data;
		
	}

//batas get function untuk pemanggilan data
// ----------------------------------------------------------------------------------------------------------------
	function addTugasNilai(){
		if(!empty($_GET['tugas'])){
			$rbl = decrypt_url($_GET['rbl']);
			$tugas = decrypt_url($_GET['tugas']);
			//cache data redis ---------------------------------------------------------------------------
			if (Cache::has('getnilaitugas'.$rbl.$tugas)){ $data= Cache::get('getnilaitugas'.$rbl.$tugas); }
			else{
				$datas = User_siswa::where('ssaRblId',$rbl)
				->orderBy("ssaFirstName")
				->get();
				
				//looping data siswa untuk cari nilai tugas siswa
				foreach($datas as $datasiswa){
					//$dataTugas = El_tugas::find($tugas);
					$nilai = El_tugas_nilai::where('tgsnilaiIdSiswa',$datasiswa->ssaUsername)
					->where('tgsnilaiIdTugas',$tugas)
					->first();
					
					$dataArray[] = array(
						'username' 	=> $datasiswa->ssaUsername,
						'namasiswa'	=> $datasiswa->ssaFirstName.' '.$datasiswa->ssaLastName,
						'nilai'			=> !empty($nilai->tgsnilaiTugas) ? $nilai->tgsnilaiTugas : 0, 
					);

				}//end foreach
			
				$data = $dataArray;

				Cache::put('getnilaitugas'.$rbl.$tugas, $data, ChaceJam() );
			}
			//cache data redis ---------------------------------------------------------------------------
		}
		else{
			$data=[];
		}

		if(!empty($_GET['tugas'])){
			$tugas = decrypt_url($_GET['tugas']);
			$dataTugas = El_tugas::with('mapel')->find($tugas);
		}
		else{
			$dataTugas = [];
		}

		$params = [
			'title'	=>'Tambah Niali Tugas',
			'label' =>'TAMBAH NILAI TUGAS',
			'getSekolah' => $this->getSekolah(),
			'getSiswa' =>$data,
			'getTugas'	=> $dataTugas,
			'getListTugas' => $this->getJadwalMapel(),
		];
		return view('guru/tugas_nilai/add_tugas_nilai')->with($params);
	}
	function insertNilaiTugas(Request $request){
		$skl = decrypt_url($request->skl);
		$rbl = decrypt_url($request->rbl);
		$tugas = decrypt_url($request->tugas);
		$datasiswa = $request->cekpilih; // array username siswa
		$nilai = $request->nilai; // array Nilai
		$koderombel = $this->cariRombelKode($rbl);
		//dd($koderombel);

		if(!empty($datasiswa)){
			$sudahada=0; $belumada=0;
			// $where = []; $dataNilaiTugasArray = [];
			foreach($datasiswa as $data){
				//---------------------------------------------
				//looping nilai
				foreach($nilai as $key => $val){
					//cek berdasarkan username siswa
					if($data == $key){
						$dataNilai = $val;
					}
				}
				//---------------------------------------------
				$where = [
					//key untuk where db
					'tgsnilaiIdSiswa'   => $data,
					//'tgsnilaiIdGuru'   => Auth::user()->id,
					'tgsnilaiIdTugas'   => $tugas,
				];
				$dataNilaiTugasArray = [
					'tgsnilaiIdSiswa'   	=> $data,
					'tgsnilaiIdGuru'   		=> $this->GetIdGuru(),
					'tgsnilaiIdTugas'   	=> $tugas,
					'tgsnilaiKodeRombel'  => $koderombel,
					'tgsnilaiTugas'   		=> $dataNilai,
					'tgsnilaiKelas'   		=> $this->getKodeKelas($rbl),
					'tgsnilaiSemester'   	=> $this->getKodeSemesterAktif(),
					'tgsnilaiTahunJaran'  => $this->getTahunAjaranAktif(),
					
				];
				//update jika suda ada | tambah jika data belum ada
				$saveData = El_tugas_nilai::updateOrCreate($where, $dataNilaiTugasArray);
				
				$sudahada++;
			} //end foreach data 
			//dd($where);
			Cache::forget('getnilaitugas'.$rbl.$tugas);
			if($saveData){
				$response = ['success'=> $sudahada.' Nilai Tugas Sudah Di Tambah'];
				return response()->json($response,200);
			}
			else{
				$response = ['error'=>'Gagal Simpan Nilai Tugas'];
			return response()->json($response,202);
			}


		} //end if empty
		else{
			//eror jika siswa belum di pilih
			$response = ['error'=>'Silahkan Pilih Siswa Terlebih Dahulu'];
			return response()->json($response,202);
		}

		
	}

//rekap nilai semua tugas siswa ---------------------------------------------------------------------------
	function getDataTugas(){
		//get semua daftar tugas yang ada dan bisa di gubakan untuk menampilkan pada daftar tabel
		$dataTugas = El_tugas_nilai::where('tgsnilaiIdGuru', $this->GetIdGuru())
		->where('tgsnilaiTahunJaran',$this->getTahunAjaranAktif())
		->with('el_tugas')
		->select('tgsnilaiIdTugas')
		->groupby('tgsnilaiIdTugas')
		->get();
		return $dataTugas;
	}
	function totalNialaiTugas($idsiswa){
		//get semua tugal dan nilai yang ada dan bisa di gubakan untuk menampilkan pada daftar tabel
		$dataTugas = $this->getDataTugas();
		
		foreach($dataTugas as $tugas){
			$cekNilai = El_tugas_nilai::where('tgsnilaiIdSiswa',$idsiswa )
			->where('tgsnilaiIdTugas',$tugas->tgsnilaiIdTugas)
			->where('tgsnilaiTahunJaran',$this->getTahunAjaranAktif())
			->first();
				if(!empty($cekNilai)){
					$dataNilai[] = array(
						// $cekNilai->tgsnilaiIdTugas => $cekNilai->tgsnilaiTugas,
						$cekNilai->tgsnilaiTugas,
						
					);
				}
				else{
					$dataNilai[] = array(
						0,
						
					);
				}
			
		}
		
		return $dataNilai;
	}

	function rekapTugasNilai(){
		$rombel = El_tugas_nilai::where('tgsnilaiIdGuru', $this->GetIdGuru())
		->where('tgsnilaiTahunJaran',$this->getTahunAjaranAktif())
		->groupby('tgsnilaiKodeRombel')
		->get();
		
		if(!empty($_GET['rbl'])){
			// -------------------------------------------------------------------------------
			$rbl = decrypt_url($_GET['rbl']);
			$idRombel = $this->cariRombelId($rbl);		
			$datasiswa = User_siswa::where('ssaRblId',$idRombel)
			->where('ssaIsActive',1)->get();

			foreach($datasiswa as $siswa){
				
				$nilai = $this->totalNialaiTugas($siswa->ssaUsername);
				$dataArrayRekap[] = array(
					'username' 	=> $siswa->ssaUsername,
					'nama_siswa' 	=> $siswa->ssaFirstName.' '.$siswa->ssaLastName,
					'nilai'			=> $nilai,
				);
					
			}
			//dd($dataArrayRekap);
			
			// -------------------------------------------------------------------------------
			//looping data tugas untuk list data tugas
			$no=1;
			foreach($this->getDataTugas() as $tugas){
				$dataArrayTugas[] = array(
					'tugas'				=> 'TUGAS '.$no++ ,
					'nama_tugas'	=> $tugas->el_tugas->tugasJudul,
					'mapel'				=> $tugas->el_tugas->tugasMapelNama ,
					'kode_mapel'	=> $tugas->el_tugas->tugasMapelKode,
				);
			}	
			$rombelNilai = El_tugas_nilai::where('tgsnilaiIdGuru', $this->GetIdGuru())
			->where('tgsnilaiTahunJaran',$this->getTahunAjaranAktif())
			->where('tgsnilaiKodeRombel',$rbl)
			->first();	
			// -------------------------------------------------------------------------------
		}


		$params = [
			'title'	=>'Rekap Niali Tugas',
			'label' =>'REKAP NILAI TUGAS',
			'rombel' => $rombel,
			'rombelNilai' => !empty($rombelNilai) ? $rombelNilai  : null,
			'dataNilaiSiswa'	=> !empty($dataArrayRekap) ? $dataArrayRekap  : null,
			'dataTugas'		=> !empty($dataArrayTugas) ? $dataArrayTugas  : null ,
			
		];
		return view('guru/tugas_nilai/rekap_tugas_nilai')->with($params);
	}





}
