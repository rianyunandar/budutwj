<?php

//membaca lokasi Controller dalam Folder
namespace App\Http\Controllers\Api;



use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; //menjalankan printah Hash Enkripsi
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cache;
use App\User_siswa;
use App\Master_sekolah;
use App\Master_jurusan;
use App\Master_kelas;
use App\Master_rombel;
use App\Setting;
use App\User_guru;

class ApiUser extends Controller
{
	function Toket(){
		$token = Setting::first();
		return $token->setTokenApi;
	}

// Data Siswa ---------------------------------------------------------------------------------
	public function getUser($token,$id)
	{
		$sekolah = Master_sekolah::firstWhere('sklKode', $id);
		$idskl = $sekolah->sklId;
		if($token == $this->Toket()){
			if (Cache::has('api_json_siswa'.$idskl)){ $data = Cache::get('api_json_siswa'.$idskl); }
    	else{ 
				$datasiswa = User_siswa::where('ssaSklId', $idskl)
				->where('ssaIsActive',1)
				->with('profile_siswa')
				->with('master_sekolah')
				->with('master_jurusan')
				->with('master_rombel')
				->with('anggota_rombel')
				->with('master_status_keadaan')
				->get();
				foreach ($datasiswa as $val) {

					$data[] = array(
						'username'				=>$val['ssaUsername'],
						'full_nama' 			=>$val['ssaFirstName'].' '.$val['ssaLastName'],
						'kelas'						=>$val['anggota_rombel']['master_rombel']['master_kelas']['klsNama'],
						'koderombel'			=>$val['master_rombel']['rblKode'],
						'rombel'					=>$val['anggota_rombel']['master_rombel']['master_kelas']['klsNama'].$val['anggota_rombel']['master_rombel']['rblNama'],
						'jurusan'					=>$val['master_jurusan']['jrsSlag'],
						'agama'						=>$val['ssaAgama'],
						'tahun_angkatan'	=>$val['ssaTahunAngkata'],

						//data siswa
						'jsk'							=>$val['profile_siswa']['psJsk'],
						'tempat_lahir'		=>$val['profile_siswa']['psTpl'],
						'tangal_lahir'		=> date('d-m-Y',strtotime($val['profile_siswa']['psTgl'])),
						
						'nis'							=>$val['profile_siswa']['psNis'],
						'nisn'						=>$val['profile_siswa']['psNisn'],
						'nik'							=>$val['profile_siswa']['psNik'],
						'nokk'						=>$val['profile_siswa']['psNoKK'],
						'hpsiswa'					=>$val['profile_siswa']['psHp'],
						'hobi'						=>$val['profile_siswa']['pshobi'],
						'tinggi_badan'		=>$val['profile_siswa']['psTinggiBadan'],
						'jarak'						=>$val['profile_siswa']['psJarak'],
						'transport'				=>$val['profile_siswa']['psTransport'],
						
						'alamat'					=>$val['profile_siswa']['psAlamat'],
						'rt'							=>$val['profile_siswa']['psRt'],
						'rw'							=>$val['profile_siswa']['psRw'],
						'desa'						=>$val['profile_siswa']['psDesa'],
						'kecamatan'				=>$val['profile_siswa']['psKecamatan'],
						'kabupaten'				=>$val['profile_siswa']['psKabupaten'],
						'provinsi'				=>$val['profile_siswa']['psProvinsi'],

						'nokks'						=>$val['profile_siswa']['psNoKKS'],
						'nopkh'						=>$val['profile_siswa']['psNoPKH'],
						'nokip'						=>$val['profile_siswa']['psNoKip'],

						//data smp
						'asal_smp'				=> !empty($val['profile_siswa']['master_smp']) ? $val['profile_siswa']['master_smp']['smpNama'] : null,
						'npsn'						=> !empty($val['profile_siswa']['master_smp']) ? $val['profile_siswa']['psNpsnSmp'] : null,
						'status'					=> !empty($val['master_status_keadaan']['mstNama']) ? $val['master_status_keadaan']['mstNama'] : null,

						//data orang tua
						'ayah'						=>$val['profile_siswa']['psNamaAyah'],
						'hpayah'						=>$val['profile_siswa']['psHpAyah'],
						'ibu'							=>$val['profile_siswa']['psNamaIbu'],
						'hpibu'						=>$val['profile_siswa']['psHpIbu'],
						'wali'						=>$val['profile_siswa']['psNamaWali'],
						'hpwali'						=>$val['profile_siswa']['psHpWali'],
						'kode_sekolah' 		=>$sekolah->sklKode,

					);
				} //end foreach
				//86400 = 24 jam
				Cache::put('api_json_siswa'.$idskl, $data, WaktuChaceRedis(86400)); 

			} //endif cache
      
      $response = [
        'status'=>'oke',
        'pesan'=>'success',
        'data'=>$data,
      ];
		}
		else{
			$response = [
        'status'=>'error',
        'pesan'=>'Token Tidak Cocok',
        'data'=>null,
      ];
		}
		
		return response()->json($response,200);
	}
	//siswa off
	public function getUserOff($token,$id)
	{
		$sekolah = Master_sekolah::firstWhere('sklKode', $id);
		$idskl = $sekolah->sklId;
		if($token == $this->Toket()){
			if (Cache::has('api_json_siswa_off'.$idskl)){ $data = Cache::get('api_json_siswa_off'.$idskl); }
    	else{ 
				$datasiswa = User_siswa::where('ssaSklId', $idskl)
				->where('ssaIsActive',0)
				->with('profile_siswa')
				->with('master_sekolah')
				->with('master_jurusan')
				->with('master_rombel')
				->with('anggota_rombel')
				->with('master_status_keadaan')
				->get();
				foreach ($datasiswa as $val) {

					$data[] = array(
						'username'				=>$val['ssaUsername'],
						'full_nama' 			=>$val['ssaFirstName'].' '.$val['ssaLastName'],
						'kelas'						=>$val['anggota_rombel']['master_rombel']['master_kelas']['klsNama'],
						'koderombel'			=>$val['master_rombel']['rblKode'],
						'rombel'					=>$val['anggota_rombel']['master_rombel']['master_kelas']['klsNama'].$val['anggota_rombel']['master_rombel']['rblNama'],
						'jurusan'					=>$val['master_jurusan']['jrsSlag'],
						'agama'						=>$val['ssaAgama'],
						'tahun_angkatan'	=>$val['ssaTahunAngkata'],

						//data siswa
						'jsk'							=>$val['profile_siswa']['psJsk'],
						'tempat_lahir'		=>$val['profile_siswa']['psTpl'],
						'tangal_lahir'		=> date('d-m-Y',strtotime($val['profile_siswa']['psTgl'])),
						
						'nis'							=>$val['profile_siswa']['psNis'],
						'nisn'						=>$val['profile_siswa']['psNisn'],
						'nik'							=>$val['profile_siswa']['psNik'],
						'nokk'						=>$val['profile_siswa']['psNoKK'],
						'hpsiswa'					=>$val['profile_siswa']['psHp'],
						'hobi'						=>$val['profile_siswa']['pshobi'],
						'tinggi_badan'		=>$val['profile_siswa']['psTinggiBadan'],
						'jarak'						=>$val['profile_siswa']['psJarak'],
						'transport'				=>$val['profile_siswa']['psTransport'],
						
						'alamat'					=>$val['profile_siswa']['psAlamat'],
						'rt'							=>$val['profile_siswa']['psRt'],
						'rw'							=>$val['profile_siswa']['psRw'],
						'desa'						=>$val['profile_siswa']['psDesa'],
						'kecamatan'				=>$val['profile_siswa']['psKecamatan'],
						'kabupaten'				=>$val['profile_siswa']['psKabupaten'],
						'provinsi'				=>$val['profile_siswa']['psProvinsi'],

						'nokks'						=>$val['profile_siswa']['psNoKKS'],
						'nopkh'						=>$val['profile_siswa']['psNoPKH'],
						'nokip'						=>$val['profile_siswa']['psNoKip'],

						//data smp
						'asal_smp'				=> !empty($val['profile_siswa']['master_smp']) ? $val['profile_siswa']['master_smp']['smpNama'] : null,
						'npsn'						=> !empty($val['profile_siswa']['master_smp']) ? $val['profile_siswa']['psNpsnSmp'] : null,
						'status'					=> $val['master_status_keadaan']['mstNama'],

						//data orang tua
						'ayah'						=>$val['profile_siswa']['psNamaAyah'],
						'hpayah'						=>$val['profile_siswa']['psHpAyah'],
						'ibu'							=>$val['profile_siswa']['psNamaIbu'],
						'hpibu'						=>$val['profile_siswa']['psHpIbu'],
						'wali'						=>$val['profile_siswa']['psNamaWali'],
						'hpwali'						=>$val['profile_siswa']['psHpWali'],
						'kode_sekolah' 		=>$sekolah->sklKode,

					);
				} //end foreach
				//86400 = 24 jam
				Cache::put('api_json_siswa_off'.$idskl, $data, WaktuChaceRedis(86400)); 

			} //endif cache
      
      $response = [
        'status'=>'oke',
        'pesan'=>'success',
        'data'=>$data,
      ];
		}
		else{
			$response = [
        'status'=>'error',
        'pesan'=>'Token Tidak Cocok',
        'data'=>null,
      ];
		}
		
		return response()->json($response,200);
	}
	
// Data Jurusan dan Rombel --------------------------------------------------------------------
	function CountAnggotaRombelJsk($idrombel){
		$laki = 0;
		$prempuan =0;
		$total =0;
		$data = User_siswa::where('ssaRblId',$idrombel)
		->where('ssaIsActive',1)
		->with('profile_siswa')
		->get();
		foreach($data as $val){
			$total++;
			if($val->profile_siswa->psJsk  == 'L'){
				$laki++;
			}
			else{
				$prempuan++;
			}
		}
		$dataArray=array(
			'jumlah'	=> $total,
			'jumlah_p' => $prempuan,
			'jumlah_l'	=> $laki,

		);
		return $dataArray;
	}
	//hitung jumlah siswa pada jurusan dan p,l
	function CountAnggotaJurusanJsk($id){
		$laki=$prempuan=$total =0;
		$data = User_siswa::where('ssaJrsId',$id)
		->where('ssaIsActive',1)
		->with('profile_siswa')
		->get();

		foreach($data as $val){
			$total++;
			if($val->profile_siswa->psJsk == 'L'){
				$laki++;
			}
			else{
				$prempuan++;
			}
			
		}
		$dataArray=array(
			'jumlah'	=> $total,
			'jumlah_p' => $prempuan,
			'jumlah_l'	=> $laki,

		);
		return $dataArray;
	}
	//json api rombel dan total,p,l
	public function getRombel($token,$id){
		$sekolah = Master_sekolah::firstWhere('sklKode', $id);
		$idskl = $sekolah->sklId;
		if($token == $this->Toket()){
			$datarombel = Master_rombel::where('rblSklId', $idskl)
			->with('master_kelas')
			->with('master_jurusan')
			->with('master_wali_kelas')
			->with('user_guru')
			->orderBy('rblKode','desc')
			->get();
			
			foreach($datarombel as $val){
				if(!empty($val->master_wali_kelas)){
					$guru = $val->master_wali_kelas->user_guru->ugrGelarDepan.' '.
					$val->master_wali_kelas->user_guru->ugrFirstName.' '.$val->master_wali_kelas->user_guru->ugrLastName
					.', '.$val->master_wali_kelas->user_guru->ugrGelarBelakang;
				}
				else{
					$guru = null;
				}
				$jumlahrbl = $this->CountAnggotaRombelJsk($val->rblId);
				
				$data[] = array(
					'kode_rombel' =>$val->rblKode,
					'kode_jurusan' =>$val->master_jurusan->jrsSlag,
					'nama_rombel' =>$val->master_kelas->klsKode.$val->rblNama,
					'wali_kelas' =>$guru,
					'jumlah_total' => $jumlahrbl['jumlah'],
					'jumlah_l' => $jumlahrbl['jumlah_l'],
					'jumlah_p' => $jumlahrbl['jumlah_p'],
				);
				
			}

			$response = [
				'status'=>'oke',
				'pesan'=>'success',
				'data'=>$data ,
			];
		}
		else{
			$response = [
        'status'=>'error',
        'pesan'=>'Token Tidak Cocok',
        'data'=>null,
      ];
		}

		return response()->json($response,200);

	}
	//json api jurusan dan total,p,l
	public function getJurusan($token,$id){
		$sekolah = Master_sekolah::firstWhere('sklKode', $id);
		$idskl = $sekolah->sklId;
		if($token == $this->Toket()){
			$datajurusan = Master_jurusan::where('jrsSklId', $idskl)->get();

			foreach($datajurusan as $val){
				$jrs = $this->CountAnggotaJurusanJsk($val->jrsId);	
				$data[] =array(
					'kode_jurusan' => $val->jrsSlag,
					'nama_jurusan' => $val->jrsNama,
					'jumlah_total'	=>$jrs['jumlah'],
					'jumlah_laki'	=>$jrs['jumlah_l'],
					'jumlah_prempuan'	=>$jrs['jumlah_p'],
				);
				
			}

			$response = [
				'status'=>'oke',
				'pesan'=>'success',
				'data'=>$data ,
			];
		}
		else{
			$response = [
        'status'=>'error',
        'pesan'=>'Token Tidak Cocok',
        'data'=>null,
      ];
		}

		return response()->json($response,200);

	}
// Data Guru --------------------------------------------------------------------
	public function getGuru($token,$id){
		if($token == $this->Toket()){
		    if($id == "all"){
    			$dataguru = User_guru::with('profile_guru')
    			->with('master_sekolah')
    			->with('master_jabatan')
    			->with('master_jenis_ptk')
    			->with('master_status_kepegawaian')
    			->get();
		    }
		    else{
		        $sekolah = Master_sekolah::firstWhere('sklKode', $id);
		        $idskl = $sekolah->sklId;
    		    $dataguru = User_guru::where('ugrSklId',$idskl)
    		    ->with('profile_guru')
    			->with('master_sekolah')
    			->with('master_jabatan')
    			->with('master_jenis_ptk')
    			->with('master_status_kepegawaian')
    			->get();
		    }
			foreach($dataguru as $val){
				
				//bagian nama ------------------------------------------------------------------
				$namaBelakang = trim($val->ugrLastName);
				$namaDepan = trim($val->ugrFirstName);
				$gelarDepan = $val->ugrGelarDepan;
				$gelarBelakang = $val->ugrGelarBelakang;

				//jika full gelar
				if(!empty($val->ugrGelarBelakang) && !empty($val->ugrGelarDepan)){
					$nama = $gelarDepan.' '.$namaDepan.' '.$namaBelakang.','.$gelarBelakang;
				}
				//jika hanya gelar belakang
				elseif(empty($val->ugrGelarDepan) && !empty($val->ugrGelarBelakang)){
					if(empty($namaBelakang)){
						$nama = $namaDepan.', '.$gelarBelakang;
					}
					else{
						$nama = $namaDepan.' '.$namaBelakang.', '.$gelarBelakang;
					}
					
				}
				//jika hanya gelar depan
				elseif(empty($val->ugrGelarBelakang) && !empty($val->ugrGelarDepan)){
					$nama = $gelarDepan.' '.$namaDepan.' '.$namaBelakang;
				}
				//jika tidak ada gelar
				else{
					$nama =  $namaDepan.' '.$namaBelakang;
				}
				//bagian nama ------------------------------------------------------------------

				$data[]=array(
					//data guru
					'kode_sekolah'	=>$val->master_sekolah->sklKode,
					'username' 			=>$val->ugrUsername,
					'nama_guru'			=>$nama,
					'tugas_tambahan' => !empty($val->ugrTugasTambahan) ? $val->master_jabatan->mjbNama : null,
					'jenis_ptk'			=>!empty($val->ugrPtkKode) ? $val->master_jenis_ptk->ptkNama : null,
					'kepegawaian'		=> !empty($val->ugrMskpKode) ? $val->master_status_kepegawaian->mskpNama : null,
					'jsk'						=>$val->profile_guru->prgJsk,
					'tempat_lahir'	=>$val->profile_guru->prgTpl,
					'tanggal_lahir'	=> date('d-m-Y',strtotime($val->profile_guru->prgTgl)),
					'agama'					=>$val->profile_guru->prgAgama,
					'nuptk'					=>$val->profile_guru->prgNuptk,
					'no_hp'					=>$val->ugrHp,
					'no_wa'					=>$val->ugrWa,

					
					//alamat
					'alamat'				=>$val->profile_guru->prgAlmat,
					'rt'						=>$val->profile_guru->prgRt,
					'rw'						=>$val->profile_guru->prgRw,
					'dusun'					=>$val->profile_guru->prgDusun,
					'desa'					=>$val->profile_guru->prgDesa,
					'kabupaten'			=>$val->profile_guru->prgKabupaten,
					'kecamatan'			=>$val->profile_guru->prgKecamatan,
					'provinsi'			=>$val->profile_guru->prgProvinsi,

					//data Ayaha
					'ayah'					=>$val->profile_guru->prgNamaAyah,
					'ibu'						=>$val->profile_guru->prgNamaIbu,

					//data kampus
					'nama_kampus'		=>$val->profile_guru->prgNamaKampus,
					'falkultas'			=>$val->profile_guru->prgFalkultas,
					'bidang_studi'	=>$val->profile_guru->prgBidangStudi,
					//'kependidikan'	=>$val->profile_guru->prgKependidikan,
					'no_ijasah'			=>$val->profile_guru->prgNoIjazah,
					'tgl_lulus'			=>$val->profile_guru->prgTglLulus,
					'ipk'						=>$val->profile_guru->prgIpk,

					//data masuk
					'tanggal_masuk'	=>$val->profile_guru->prgTglMasuk,
					'bulan_masuk'		=>$val->profile_guru->prgBlnMasuk,
					'tahun_masuk'		=>$val->profile_guru->prgTahunMasuk,
					'tmt'						=>$val->profile_guru->prgTmt,
					'sk_pengangkatan'	=>$val->profile_guru->prgSkPengangkatan,
					
				);

			}

			$response = [
				'status'=>'oke',
				'pesan'=>'success',
				'data'=>$data ,
			];
		}
		else{
			$response = [
        'status'=>'error',
        'pesan'=>'Token Tidak Cocok',
        'data'=>null,
      ];
		}
		return response()->json($response,200);
		
	}
	
}
