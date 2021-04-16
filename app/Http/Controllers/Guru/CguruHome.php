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
use DataTables;
use Auth;//menjalankan printah auth
use App\User_siswa;
use App\User_guru;
use App\Master_sekolah;
use App\Master_agama;
use App\Master_jurusan;
use App\Profile_siswa;
use App\Profile_guru;
use App\Penghasilan;
use App\Master_smp;
use App\Master_status_keadaan;
use App\Transportasi;
use App\Tahun_ajaran;
use App\Anggota_rombel;
use App\Master_wali_kelas;
use App\Log_aksi_user;
use App\Master_jabatan;
use App\Master_jenis_ptk;
use App\Master_status_kepegawaian;
use App\Master_jenjang_pendidikan;
use App\Tingkat_pendidikan;
use App\Semester;
use App\Master_kelas;




class CguruHome extends Controller
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
	
	function getJabatan()
  {
    $data = new Master_jabatan();
    return $data->getJabatan($this->getSkl());
  }
  
  function getPtk()
  {
    $data = new Master_jenis_ptk();
    return $data->getJenisPtk();
  }

	function getJurusan()
  {
  	$jurusan = new Master_jurusan();
    return $jurusan->getJurusan($this->getSkl());
  }
  function getStatusPegawai()
  {
    $data = new Master_status_kepegawaian();
    return $data->getStatusPegawai();
	}
	
  function getAgama()
  {
    $data = new Master_agama();
    return $data->getAgama();
  }
  function getJenjangPendidikan()
  {
    $data = new Master_jenjang_pendidikan();
    return $data->getJenjangPendidikan();
	}


	
	public function index()
	{
		
		$params = [
			'title'	=>'HOME SISWA',
		];
		return view('guru/home')->with($params);
	}
	//data siswa ---------------------------------------------------------------------
	function GuruDataSiswa(){
		$params = [
			'title'	=>'Data Siswa',
			'label' =>'DATA SISWA',
			'getSekolah' => $this->getSekolah(),
		];
		return view('guru/data_siswa')->with($params);
	}
	function GuruCariDataSiswa(){ // url guru-cari-data-siswa
		
		if(empty($_GET['siswa'])){
			$id =null;
			$data=[];
		}else{
			$id = Crypt::decrypt($_GET['siswa']);
			$data = User_siswa::find($id);
		}
	
		$params = [
			'title'	=>'Cari Siswa',
			'label' =>'Cari SISWA',
			'getSekolah' => $this->getSekolah(),
      
			'datasiswa' =>$data,
		];
		return view('guru/cari_siswa')->with($params);
	}

	function GuruDataSiswaJson(Request $request)
  {
		$rbl = Crypt::decrypt($request->input('rbl'));
		if(empty($request->input('rbl'))){
			$data2=[];
		}
		else{
			//cache data redis ---------------------------------------------------------------------------
			if (Cache::has('datasiswaguru'.$rbl)){ $data2 = Cache::get('datasiswaguru'.$rbl); }
			else{ 
					//$skl = decrypt_url($request->input('skl'));
				$rbl = Crypt::decrypt($request->input('rbl'));
				$data = User_siswa::where('ssaRblId',$rbl)
				->with('profile_siswa')
				->with('anggota_rombel')
				->with('master_jurusan')
				->get();

					foreach ($data as $value) {
						if(!empty($value->ssaFotoProfile)){
							$img = asset('storage/images/siswa/100/'.$value->ssaFotoProfile);
						}
						else{
							$img = asset('image/noimage.png');
						}

						$cek = array(
							'foto' =>$img,
							'username' =>$value->ssaUsername,
							'namasiswa' =>$value->ssaFirstName.' '.$value->ssaLastName,
							'jurusan' =>$value['master_jurusan']['jrsSlag'],
							'updateat' =>$value->ssaUpdated,
							'status' =>$value->ssaIsActive,
							'jsk' => !empty($value['profile_siswa']['psJsk']) ? $value['profile_siswa']['psJsk'] : '<span class="badge badge-warning">KOSONG</span>',
							'tpl' => !empty($value['profile_siswa']['psTpl']) ? $value['profile_siswa']['psTpl'] : '<span class="badge badge-warning">KOSONG</span>',
							'tgl' => !empty($value['profile_siswa']['psTgl']) ? $value['profile_siswa']['psTgl'] : '<span class="badge badge-warning">KOSONG</span>',
							'nis' => !empty($value['profile_siswa']['psNis']) ? $value['profile_siswa']['psNis'] : '<span class="badge badge-warning">KOSONG</span>',
							'nisn' => !empty($value['profile_siswa']['psNisn']) ? $value['profile_siswa']['psNisn'] : '<span class="badge badge-warning">KOSONG</span>',
							'nik' => !empty($value['profile_siswa']['psNik']) ? $value['profile_siswa']['psNik'] : '<span class="badge badge-warning">KOSONG</span>',
							'alamat' => !empty($value['profile_siswa']['psAlamat']) ? $value['profile_siswa']['psAlamat'] : '<span class="badge badge-warning">KOSONG</span>',
							'asalsmp' => !empty($value['profile_siswa']['master_smp']['smpNama']) ? $value['profile_siswa']['master_smp']['smpNama'] : '<span class="badge badge-warning">KOSONG</span>',
							'hp' => !empty($value['profile_siswa']['psHp']) ? $value['profile_siswa']['psHp'] : '<span class="badge badge-warning">KOSONG</span>',
							'ayah' => !empty($value['profile_siswa']['psNamaAyah']) ? $value['profile_siswa']['psNamaAyah'] : '<span class="badge badge-warning">KOSONG</span>',
							'hpayah' => !empty($value['profile_siswa']['psHpAyah']) ? $value['profile_siswa']['psHpAyah'] : '<span class="badge badge-warning">KOSONG</span>',
							'ibu' => !empty($value['profile_siswa']['psNamaIbu']) ? $value['profile_siswa']['psNamaIbu'] : '<span class="badge badge-warning">KOSONG</span>',
							'hpibu' => !empty($value['profile_siswa']['psHpIbu']) ? $value['profile_siswa']['psHpIbu'] : '<span class="badge badge-warning">KOSONG</span>',
							'ktr' => !empty($value['profile_siswa']['psKeterangan']) ? $value['profile_siswa']['psKeterangan'] : null,
							'agama' => !empty($value->ssaAgama) ? $value->ssaAgama : '<span class="badge badge-warning">KOSONG</span>',
							'namarombel' => !empty($value->anggota_rombel['master_rombel']['master_kelas']['klsKode']) ? $value->anggota_rombel['master_rombel']['master_kelas']['klsKode'].$value->anggota_rombel['master_rombel']['rblNama'] : '<span class="badge badge-warning">KOSONG</span>',
						);
						$data2[] = $cek;
					}   

					Cache::put('datasiswaguru'.$rbl, $data2, ChaceMenit() );
      	}
      
			}
		$dt= DataTables::of($data2)
			->rawColumns(['namasiswa','jsk','tpl','tgl','nis','nisn','nik','alamat','asalsmp','hp','ayah','hpayah','ibu','hpibu','ktr','agama','namarombel']);
    return $dt->make(); 
  }
	//data siswa ---------------------------------------------------------------------
	
	//data guru ----------------------------------------------------------------------
	function GuruDataAkun(){
		
		// $data = User_guru::find(decrypt_url(GetIdGuru()));
		$params = [
			'title'	=>'Data Akun Guru',
			'label' =>'DATA AKUN GURU',
		];
		return view('guru/akun')->with($params);
	}
	function GuruUpdatePassword(Request $request){
		$idd = decrypt_url($request->id);

		$guru = User_guru::find($idd);
    $guru->ugrPassword  = Hash::make($request->newpassword);
    $guru->ugrUpdated = date("Y-m-d H:i:s");
    $guru->ugrUpdatedBy = Auth::user()->ugrId;
    if($guru->save()){ 
			Cache::forget('user_guru'.Auth::user()->ugrSklId);
			Cache::forget('guru_skl'.Auth::user()->ugrSklId);
			//catat aksi ke dalam log database
      $dataArray = array(
        'laIdGuru' =>Auth::user()->ugrId,
        'laNamaUser' => FullNamaGuru(),
        'laNamaAksi' =>'Mengganti Password',
        'laDateTIme' =>date("Y-m-d H:i:s"),
      );
      Log_aksi_user::insert($dataArray);
      $response = ['save'=>'Berhasil Ganti Password'];
    }
    else{ $response = ['error'=>'Gagal Ganti Password']; }
    
    return response()->json($response,200);

			 
	}
	function GuruDataProfile(){
		$data = User_guru::find(decrypt_url(GetIdGuru()));
		$params = [
			'title'	=>'Data Akun Guru',
			'label' =>'DATA AKUN GURU',
			'getSekolah' => $this->getSekolah(),
      'getJabatan' => $this->getJabatan(),
      'getPtk' => $this->getPtk(),
      'getPegawai' =>$this->getStatusPegawai(),
      'getAgama' =>$this->getAgama(),
      'getPendidikan' =>$this->getJenjangPendidikan(),
			'guru'	=>$data,
		];
		return view('guru/editguru')->with($params);
	}

	function GuruUpdateDataProfile(Request $request){

			$guru = User_guru::find(decrypt_url(GetIdGuru()));
      $guru->ugrFirstName = strtoupper(request()->firstname);
      $guru->ugrLastName = strtoupper(request()->lastname);
      $guru->ugrHp = request()->nohp;
      $guru->ugrWa = request()->nowa;
      $guru->ugrEmail = request()->email;
      $guru->ugrUpdated = date("Y-m-d H:i:s");
      $guru->ugrUpdatedBy = Auth::user()->ugrId;

      //Profile Guru
      $profil = Profile_guru::where('prgUgrUsername',request()->ugrUsername)->first();
      $profil->prgUgrUsername = request()->ugrUsername;
      $profil->prgMpjKode = request()->pendidikan;
      $profil->prgAgama = request()->agama;
      $profil->prgJsk = request()->jsk;
      $profil->prgTgl = Carbon::parse($request->tgl)->translatedFormat('Y-m-d');//strtotime ('Y-m-d',; 
      $profil->prgTpl = strtoupper(request()->tpl);
			$profil->prgNik = request()->nik;
			$profil->prgNip = request()->nip;
			$profil->prgAlamat = strtoupper(request()->alamat);
      $profil->prgRt = request()->rt;
      $profil->prgRw = request()->rw;
      $profil->prgDesa = strtoupper(request()->desa);
      $profil->prgKabupaten = strtoupper(request()->kabut);
      $profil->prgKecamatan = strtoupper(request()->keca);
      $profil->prgProvinsi = strtoupper(request()->provinsi);
      $profil->prgNamaIbu = strtoupper(request()->ibu);
      $profil->prgNamaAyah = strtoupper(request()->ayah);

      $profil->prgNoIjazah = request()->ijazah;
      $profil->prgNamaKampus = strtoupper(request()->kampus);
      $profil->prgTglLulus = date('Y-m-d',strtotime(request()->tgl_lulus));
			$profil->prgIpk = request()->ipk;
			$profil->prgFalkultas = strtoupper(request()->falkultas);
			$profil->prgBidangStudi = strtoupper(request()->bidangstudy);
			$profil->prgKependidikan = request()->kependidikan;
      
      $profil->prgTglMasuk = request()->tgl1;
      $profil->prgBlnMasuk = request()->bln1;
      $profil->prgTahunMasuk = request()->thn1;
      //$profil->prgEmailGSE = request()->jsk;

      if($guru->save()){ 
        if($profil->save()){ 
          Cache::forget('user_guru'.$this->getSkl());
					Cache::forget('guru_skl'.$this->getSkl());
					//catat aksi ke dalam log database
					$dataArray = array(
						'laIdGuru' =>Auth::user()->ugrId,
						'laNamaUser' => FullNamaGuru(),
						'laNamaAksi' =>'Mengupdate Data Profile Guru',
						'laDateTIme' =>date("Y-m-d H:i:s"),
					);
					Log_aksi_user::insert($dataArray);
          $response = ['save'=>'Berhasil Update Profile Guru'];
        }
        else{
          $response = ['error'=>'Gagal Update Profile Guru'];
        }
      }
      else{ $response = ['error'=>'Gagal Update Akun Guru'];}
    
    return response()->json($response,200);

	}


	//jadwal mapel absensi ---------------------------------------------------------------
	function GuruJadwalMapelAbsen(){
		$params = [
			'title'	=>'Jadwal Mapel Absensi',
			'label' =>'DATA JADWAL MAPEL ABSENSI',
			'getSekolah' => $this->getSekolah(),
			'getKelas'	=> $this->getLevelKelas(),
		];
		return view('guru/Mapelabsensi/jadwal_mapel_absensi')->with($params);
	}



}
