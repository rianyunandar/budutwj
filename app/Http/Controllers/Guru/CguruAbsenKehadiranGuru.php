<?php

//membaca lokasi Controller dalam Folder
namespace App\Http\Controllers\Guru;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use DataTables;
use Auth;//menjalankan printah auth
use App\Master_sekolah;
use App\Tahun_ajaran;
use App\Semester;
use App\Absen_hadir_guru;


class CguruAbsenKehadiranGuru extends Controller
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

  function getSekolah()
  {
    $data = new Master_sekolah();
    return $data->getSekolah();
	}

//----------------------------------------------------------------------------------------
	function viewAbsenGuru(){
			$params = [
				'title'	=>'ABSEN GURU',
				'label' =>'ABSENSI KEHADIRAN GURU',
				'getSekolah' => $this->getSekolah(),
			];
			return view('guru/absen_kehadiran_guru/absen_sekolah')->with($params);
	}
	function insert(Request $request){
		$jenisAbsen =  $request->idabsen;
		$idskl = $this->getSkl();
		//ubah pakai tahun bukan tahun ajaran
		$tahunajaran = $this->getTahunAjaran();
		$semster = $this->getSemester();

		$namahari = date('l');
		$tahun = date('Y');
    $tgl = date('Y-m-d');
		$jamsekarang = date("H:i");

		$userGuru = Auth::user()->ugrUsername;

		$cek = Absen_hadir_guru::where('hgUserGuru',$userGuru)
		->where('hgSmKode',$semster)
		->where('hgTajrKode',$tahunajaran)
		->where('hgTgl',$tgl)
		// ->where('hgJenisAbsen',$jenisAbsen)
		->first();

		if($jenisAbsen == 1){
			$statusabsen ="Masuk";
		}
		else{
			$statusabsen ="Pulang";
		}

		if(!empty($cek)){
			
			if(empty($cek->hgJamOut)){
				if($jenisAbsen == 2){
					Absen_hadir_guru::where('hgId',$cek->hgId)->update(array(
						'hgJamOut' => $jamsekarang,
					
					));
					$response = ['success'=>'Berhasil Melakukan Absen '.$statusabsen.' Hari ini' ];
					return response()->json($response,200);
				}
				else{
					$response = ['warning'=>'Sudah Melakukan Absen '.	$statusabsen.' Hari ini' ];
					return response()->json($response,200);
				}
				
			}
			//jika ada absen ada tpi jam masuk kosong
			else if(empty($cek->hgJamIn)){
				if($jenisAbsen == 1){
					Absen_hadir_guru::where('hgId',$cek->hgId)->update(array(
						'hgJamIn' => $jamsekarang,
					
					));
					$response = ['success'=>'Berhasil Melakukan Absen '.$statusabsen.' Hari ini' ];
					return response()->json($response,200);
				}
				else{
					$response = ['warning'=>'Sudah Melakukan Absen '.	$statusabsen.' Hari ini' ];
					return response()->json($response,200);
				}
			}
			//jika tidak semuanya
			else{
				$response = ['warning'=>'Sudah Melakukan Absen '.	$statusabsen.' Hari ini' ];
				return response()->json($response,200);
			}
			
			
		}
		else{
			//jika jenis absen pulang
			if($jenisAbsen == 1){
				$data = new Absen_hadir_guru();
				$data->hgSklId = $idskl;
				$data->hgSmKode = $semster;
				$data->hgTajrKode = $tahunajaran;
				$data->hgTahun 	= $tahun;
				$data->hgUserGuru = $userGuru;
				$data->hgNamaDepan = NamaDepanGuru();
				$data->hgNamaBelakang = NamaBelakangGuru();
				$data->hgNamaFull = FullNamaGuru();
				$data->hgTgl = $tgl;
				$data->hgHari = $namahari;
				$data->hgJamIn = $jamsekarang;
				$data->hgKodeAbsen = 'H';
			//	$data->hgJenisAbsen = $jenisAbsen;
				$data->hgKeterangan = "";
				$data->hgCreatedByGuru = $this->GetIdGuru();
				$data->hgUserAksi = 1;
			}
			else{
				$data = new Absen_hadir_guru();
				$data->hgSklId = $idskl;
				$data->hgSmKode = $semster;
				$data->hgTajrKode = $tahunajaran;
				$data->hgTahun 	= $tahun;
				$data->hgUserGuru = $userGuru;
				$data->hgNamaDepan = NamaDepanGuru();
				$data->hgNamaBelakang = NamaBelakangGuru();
				$data->hgNamaFull = FullNamaGuru();
				$data->hgTgl = $tgl;
				$data->hgHari = $namahari;
				$data->hgJamOut = $jamsekarang;
				$data->hgKodeAbsen = 'H';
				//$data->hgJenisAbsen = $jenisAbsen;
				$data->hgKeterangan = "";
				$data->hgCreatedByGuru = $this->GetIdGuru();
				$data->hgUserAksi = 1;
			}
		

			if($data->save()){
				$response = ['success'=>'Berhasil Melakukan Absen '.$statusabsen.' Hari ini' ];
				return response()->json($response,200);
			}
			else{
				$response = ['error'=>'Gagal Absen'];
				return response()->json($response,202);
			}

		}

	}
	function JsonLogAbsensiGuruSekolah(){
   
    if(empty($_GET['bln'])){
      $data = [];
    }
    else{
      $bln = decrypt_url($_GET['bln']);
      //$idrombel = $this->getDataRombel()->master_rombel->rblId;
      $username = Auth::user()->ugrUsername;
      $tahunajaran = $this->getTahunAjaran();
      $semster = $this->getSemester();
      
      //cache data redis ---------------------------------------------------------------------------
		  if (Cache::has('absen_sekolah_guru'.$username.$bln)){ $data= Cache::get('absen_sekolah_guru'.$username.$bln); }
		  else{ 
        $data = Absen_hadir_guru::where('hgUserGuru',$username)
        ->whereMonth('hgTgl', $bln)
        ->where('hgTajrKode',$tahunajaran)
        ->where('hgSmKode',$semster)
        ->get();
        Cache::put('absen_sekolah_guru'.$username.$bln, $data, ChaceJam() );
      }
    }
    $dt= DataTables::of($data)
    ->addColumn('tanggal',function ($data) { 
			$tgl = date('d-m-Y', strtotime($data->hgTgl));
			return $tgl;
		})
		->addColumn('hari',function ($data) { 
			return hariIndo($data->hgHari);
    })
    ->rawColumns(['tanggal','hari']);
    return $dt->make();
	
  }
	
 

} //end CguruAbsensi Conttroller
