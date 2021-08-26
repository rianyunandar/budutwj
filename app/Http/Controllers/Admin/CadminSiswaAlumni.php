<?php

//membaca lokasi Controller dalam Folder
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash; //menjalankan printah Hash Enkripsi
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Auth;//menjalankan printah auth
use DB;
use Excel;
use DataTables;
use App\User_siswa;
use App\User_siswa_alumni;
use App\User_siswa_hapus;
use App\Profile_siswa_alumni;
use App\Profile_siswa_hapus;
use App\Master_sekolah;
use App\Master_agama;
use App\Master_jurusan;
use App\Profile_siswa;
use App\Penghasilan;
use App\Master_smp;
use App\Master_status_keadaan;
use App\Transportasi;
use App\Imports\ImportDataSiswa;
use App\Imports\ImportUpdateDataSiswa;
use App\Log_aksi_user;
use App\Setting;

class CadminSiswaAlumni extends Controller
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
  
  function getAgama()
  {
    $data = new Master_agama();
    return $data->getAgama();
  }
  function getJurusan()
  {
  	$jurusan = new Master_jurusan();
    return $jurusan->getJurusan($this->getSkl());
  }
  
  function getSmp()
  {
    if (Cache::has('master_smp')){ $smp = Cache::get('master_smp'); }
    else{ 
      $smp = Master_smp::get();
      $chace = Cache::put('master_smp', $smp, ChaceJam());
    }
    return $smp;
  }
 
 
  function HapusCache($id=null){
    Cache::forget('user_siswa'.$this->getSkl());
    Cache::forget('user_siswa_off'.$this->getSkl());
    Cache::forget('api_json_siswa'.$id);
  }
  function getTahunAngkatan(){
    $data = User_siswa::select('ssaTahunAngkata')
    ->groupby('ssaTahunAngkata')
    ->get();
    return $data;
  }
  function getTahunAngkatanAlumni(){
    $data = User_siswa_alumni::select('ssaTahunAngkata')
    ->groupby('ssaTahunAngkata')
    ->get();
    return $data;
  }


	public function index()
	{
		
		//return view('crew/crew_home')->with($params);
  }

  function CountSiswaTahunAngkatan()
  {
   
    foreach ($this->getTahunAngkatan() as $data){
      $val = User_siswa::where('ssaTahunAngkata', $data->ssaTahunAngkata)->count();
      $array[] = array(
        $data->ssaTahunAngkata => $val
      ); 
     
    }
    return $array;
  }
  function CountSiswaTahunAngkatanAlumni()
  {
    $jmlTahun = $this->getTahunAngkatanAlumni();
    if(count($jmlTahun) > 0){
      foreach ($jmlTahun as $data){
        $val = User_siswa_alumni::where('ssaTahunAngkata', $data->ssaTahunAngkata)->count();
        $array[] = array(
          $data->ssaTahunAngkata => $val
        ); 
       
      }
      return $array;
    }
    else{
      return $array=[];
    }
    
   
   
    
  }


	public function add()
	{
		
		$params = [
			'title'	=>'Tambah Alumni Siswa',
			'label'	=>'FORM TAMBAH ALUMNI SISWA ',
      'angkatan' =>$this->getTahunAngkatan(),
      'jm_angkatan'   => $this->CountSiswaTahunAngkatan(),
      'jm_alumni'   => $this->CountSiswaTahunAngkatanAlumni()
		];
		return view('crew/siswa_alumni/add')->with($params);
	}
  public function allAlumni(){
    $params = [
      'title' =>'Semua Data Alumni',
      'label' =>'<b>LIHAT SEMUA DATA ALUMNI</b>',
    ];
    return view('crew/siswa_alumni/view_all')->with($params);
  }

  function Insert(Request $request)
  {
    $tahunAngkatan = $request->thn;
    
    $data = User_siswa::where('ssaTahunAngkata',$tahunAngkatan)
    ->with('master_jurusan')
    ->with('master_rombel')
    ->with('profile_siswa',(function($query){
      $query->with('master_smp');
    }))
    ->get();
    
    $data3=$data4=$data5=$data6=array();
    $userBerhasil=$userGagal=$profileBerhasil=$profileGagal=0;
    foreach($data as $datas){
      //cek apakah sudah ada data siswa
      $cek = User_siswa_alumni::find($datas->ssaId);
      if(empty($cek)){
        //cek nama smp
        if(empty($datas->profile_siswa->master_smp->smpNama)){
          $smp = null;
        }
        else{
          $smp = $datas->profile_siswa->master_smp->smpNama;
        }
        //insert baru
        
        //data Akun
        $data3['ssaId'] = $datas->ssaId;
        $data3['ssaSklId'] = $datas->ssaSklId; 
        // $data3['ssaJrsId'] = $datas->ssaJrsId; 
        // $data3['ssaRblId'] = $datas->ssaRblId; 
        $data3['ssaRole'] = $datas->ssaRole; 
        $data3['ssaOrtuId'] = $datas->ssaOrtuId; 
        $data3['ssaKode'] = $datas->ssaKode; 
        $data3['ssaTahunAngkata'] = $datas->ssaTahunAngkata; 
        $data3['ssaStatusKeadaan'] = $datas->ssaStatusKeadaan;
        $data3['ssaAgama'] = $datas->ssaAgama;
        $data3['ssaUsername'] = $datas->ssaUsername;
        $data3['ssaPassword'] = $datas->ssaPassword;
        $data3['ssaFirstName'] = $datas->ssaFirstName;
        $data3['ssaLastName'] = $datas->ssaLastName;
        $data3['ssaFullName'] = $datas->ssaFullName;
        $data3['ssaEmail'] = $datas->ssaEmail;
        $data3['ssaQrCode'] = $datas->ssaQrCode;
        $data3['ssaStatusMasuk'] = $datas->ssaStatusMasuk;
        $data3['ssaPrakerind'] = $datas->ssaPrakerind;
        $data3['ssaFotoProfile'] = $datas->ssaFotoProfile;
        $data3['ssaHp'] = $datas->ssaHp;
        $data3['ssaWa'] = $datas->ssaWa;
        $data3['ssaIsActive'] = $datas->ssaIsActive;
        $data3['ssaIsDeleted'] = $datas->ssaIsDeleted;
        $data3['ssaCreated'] = $datas->ssaCreated;
        $data3['ssaUpdated'] = $datas->ssaUpdated;
        $data3['ssaDeleted'] = $datas->ssaDeleted;
        $data3['remember_token'] = $datas->remember_token;
        $data3['ssaNamaSmp'] = $smp;
        $data3['ssaNamaJurusan'] = $datas->master_jurusan->jrsSlag;
        $data3['ssaNamaRombel'] = $datas->master_rombel->rblNama;

        //data Profile
        $data4[]=$data3;
        $userBerhasil++;

        //cek jika username tidak kosong makan tampung data profile siswa
        if(!empty($datas->ssaUsername)){
          //$data5['psId']=$datas->profile_siswa->psId;
          $data5['psSsaUsername']=$datas->profile_siswa->psSsaUsername;
          $data5['psJsk']=$datas->profile_siswa->psJsk;
          $data5['psTpl']=$datas->profile_siswa->psTpl;
          $data5['psTgl']=$datas->profile_siswa->psTgl;
          $data5['psNis']=$datas->profile_siswa->psNis;
          $data5['psNisn']=$datas->profile_siswa->psNisn;
          $data5['psNik']=$datas->profile_siswa->psNik;
          $data5['psNoKK']=$datas->profile_siswa->psNoKK;
          $data5['psHobi']=$datas->profile_siswa->psHobi;
          $data5['psTinggiBadan']=$datas->profile_siswa->psTinggiBadan;
          $data5['psJarak']=$datas->profile_siswa->psJarak;
          $data5['psTransport']=$datas->profile_siswa->psTransport;
          $data5['psAlamat']=$datas->profile_siswa->psAlamat;
          $data5['psRt']=$datas->profile_siswa->psRt;
          $data5['psRw']=$datas->profile_siswa->psRw;
          $data5['psDesa']=$datas->profile_siswa->psDesa;
          $data5['psKecamatan']=$datas->profile_siswa->psKecamatan;
          $data5['psKabupaten']=$datas->profile_siswa->psKabupaten;
          $data5['psProvinsi']=$datas->profile_siswa->psProvinsi;
          $data5['psNoKKS']=$datas->profile_siswa->psNoKKS;
          $data5['psNoPKH']=$datas->profile_siswa->psNoPKH;
          $data5['psNoKip']=$datas->profile_siswa->psNoKip;
          $data5['psAsalSmp']=$datas->profile_siswa->psAsalSmp;
          $data5['psNpsnSmp']=$datas->profile_siswa->psNpsnSmp;
          $data5['psNoUjianSmp']=$datas->profile_siswa->psNoUjianSmp;
          $data5['psKeteranganStatusMasuk']=$datas->profile_siswa->psKeteranganStatusMasuk;
          $data5['psIdPrakerind']=$datas->profile_siswa->psIdPrakerind;
          $data5['psKeterangan']=$datas->profile_siswa->psKeterangan;
          
          $data5['psNamaAyah']=$datas->profile_siswa->psNamaAyah;
          $data5['psAlamatAyah']=$datas->profile_siswa->psAlamatAyah;
          $data5['psNikAyah']=$datas->profile_siswa->psNikAyah;
          $data5['psTplAyah']=$datas->profile_siswa->psTplAyah;
          $data5['psTglAyah']=$datas->profile_siswa->psTglAyah;
          $data5['psPendidikanAyah']=$datas->profile_siswa->psPendidikanAyah;
          $data5['psHpAyah']=$datas->profile_siswa->psHpAyah;
          $data5['psWaAyah']=$datas->profile_siswa->psWaAyah;
          $data5['psPekerjaanAyah']=$datas->profile_siswa->psPekerjaanAyah;
          $data5['psPenghasilanAyah']=$datas->profile_siswa->psPenghasilanAyah;

          $data5['psNamaIbu']=$datas->profile_siswa->psNamaIbu;
          $data5['psAlamatIbu']=$datas->profile_siswa->psAlamatIbu;
          $data5['psTplIbu']=$datas->profile_siswa->psTplIbu;
          $data5['psTglIbu']=$datas->profile_siswa->psTglIbu;
          $data5['psNikIbu']=$datas->profile_siswa->psNikIbu;
          $data5['psPendidikanIbu']=$datas->profile_siswa->psPendidikanIbu;
          $data5['psHpIbu']=$datas->profile_siswa->psHpIbu;
          $data5['psWaIbu']=$datas->profile_siswa->psWaIbu;
          $data5['psPekerjaanIbu']=$datas->profile_siswa->psPekerjaanIbu;
          $data5['psPenghasilanIbu']=$datas->profile_siswa->psPenghasilanIbu;

          $data5['psNamaWali']=$datas->profile_siswa->psNamaWali;
          $data5['psAlamatWali']=$datas->profile_siswa->psAlamatWali;
          $data5['psTplWali']=$datas->profile_siswa->psTplWali;
          $data5['psTglWali']=$datas->profile_siswa->psTglWali;
          $data5['psNikWali']=$datas->profile_siswa->psNikWali;
          $data5['psPendidikanWali']=$datas->profile_siswa->psPendidikanWali;
          $data5['psPekerjaanWali']=$datas->profile_siswa->psPekerjaanWali;
          $data5['psHpWali']=$datas->profile_siswa->psHpWali;
          $data5['psWaWali']=$datas->profile_siswa->psWaWali;
          $data5['psPenghasilanWali']=$datas->profile_siswa->psPenghasilanWali;

          //tampung data array dalam array
          $data6[]=$data5;
          $profileBerhasil++;
        }
        else{
          $profileGagal++;
        }

      }
      else{
        $userGagal++;
      }
    } //end foreaceh

    if(!empty($data4)){
      $insert = User_siswa_alumni::insert($data4);
      if($insert){
        $insert2 = Profile_siswa_alumni::insert($data6);
        if($insert2){
          $response = [
            'user_berhasil'     => $userBerhasil,
            'user_gagal'        => $userGagal,
            'profile_berhasil'  => $profileBerhasil,
            'profile_gagal'     => $profileGagal,
            'success'           =>'Berhasil',
          ];
          return response()->json($response,200);
        }
        else{
          $response = ['error'=>'Opsss ada yang gagal saat pindah profile siswa !!!'];
          return response()->json($response,503);
        }
      }
      else{
        $response = ['error'=>'Opsss ada yang gagal saat pindah user akun Siswa !!! '];
        return response()->json($response,503);
      }
      
    }
    else{
      $response = ['error'=>'Opsss Data Kosong'];
      return response()->json($response,503);
    }
    
  }



  function JsonDataAlumni(Request $request)
  {
    //cache data redis ---------------------------------------------------------------------------
      if (Cache::has('data_alumni'.$this->getSkl())){ $data = Cache::get('data_alumni'.$this->getSkl()); }
      else{ 
        if(empty($this->getSkl())){  
          $data = User_siswa_alumni::where('ssaIsActive',1)
          ->with('profile_siswa_alumni')
          ->get();
        }
        else{
          $data = User_siswa_alumni::where('ssaIsActive',1)
          ->where('ssaSklId',$this->getSkl())
          ->with('profile_siswa_alumni')
          ->get();
        }   
        $cek = Cache::put('data_alumni'.$this->getSkl(), $data, ChaceJam()); 
      }
   
    //cache data redis ---------------------------------------------------------------------------
    if(count($data) > 0 ){

      foreach ($data as $value) {
        $cek = array(
          'username' =>$value->ssaUsername,
          'idsiswa' => Crypt::encrypt($value->ssaId),
          'namasiswa' =>$value->ssaFirstName.' '.$value->ssaLastName,
          'namarombel' =>$value->ssaNamaRombel,
          'jurusan' =>$value->ssaNamaJurusan,
          'updateat' =>$value->ssaUpdated,
          'status' =>$value->ssaIsActive,
          'foto' => FotoSiswaSmall($value->ssaFotoProfile),
          'jsk' => !empty($value['profile_siswa_alumni']['psJsk']) ? $value['profile_siswa_alumni']['psJsk'] : '<span class="badge badge-warning">KOSONG</span>',
          'tpl' => !empty($value['profile_siswa_alumni']['psTpl']) ? $value['profile_siswa_alumni']['psTpl'] : '<span class="badge badge-warning">KOSONG</span>',
          'tgl' => !empty($value['profile_siswa_alumni']['psTgl']) ? $value['profile_siswa_alumni']['psTgl'] : '<span class="badge badge-warning">KOSONG</span>',
          'nis' => !empty($value['profile_siswa_alumni']['psNis']) ? $value['profile_siswa_alumni']['psNis'] : '<span class="badge badge-warning">KOSONG</span>',
          'nisn' => !empty($value['profile_siswa_alumni']['psNisn']) ? $value['profile_siswa_alumni']['psNisn'] : '<span class="badge badge-warning">KOSONG</span>',
          'nik' => !empty($value['profile_siswa_alumni']['psNik']) ? $value['profile_siswa_alumni']['psNik'] : '<span class="badge badge-warning">KOSONG</span>',
          'alamat' => !empty($value['profile_siswa_alumni']['psAlamat']) ? $value['profile_siswa_alumni']['psAlamat'] : '<span class="badge badge-warning">KOSONG</span>',
          'asalsmp' => !empty($value['profile_siswa_alumni']['master_smp']['smpNama']) ? $value['profile_siswa_alumni']['master_smp']['smpNama'] : '<span class="badge badge-warning">KOSONG</span>',
          'hp' => !empty($value['ssaHp']) ? $value['ssaHp'] : '<span class="badge badge-warning">KOSONG</span>',
          'wa' => !empty($value['ssaWa']) ? $value['ssaWa'] : '<span class="badge badge-warning">KOSONG</span>',
          'ayah' => !empty($value['profile_siswa_alumni']['psNamaAyah']) ? $value['profile_siswa_alumni']['psNamaAyah'] : '<span class="badge badge-warning">KOSONG</span>',
          'hpayah' => !empty($value['profile_siswa_alumni']['psHpAyah']) ? $value['profile_siswa_alumni']['psHpAyah'] : '<span class="badge badge-warning">KOSONG</span>',
          'ibu' => !empty($value['profile_siswa_alumni']['psNamaIbu']) ? $value['profile_siswa_alumni']['psNamaIbu'] : '<span class="badge badge-warning">KOSONG</span>',
          'hpibu' => !empty($value['profile_siswa_alumni']['psHpIbu']) ? $value['profile_siswa_alumni']['psHpIbu'] : '<span class="badge badge-warning">KOSONG</span>',
          'ktr' => !empty($value['profile_siswa_alumni']['psKeterangan']) ? $value['profile_siswa_alumni']['psKeterangan'] : null,
          'agama' => !empty($value->ssaAgama) ? $value->ssaAgama : '<span class="badge badge-warning">KOSONG</span>',
         
        );
        $data2[] = $cek;
      }  
    }
    else{
      $data2 = [];
     
    } 

      $dt = DataTables::of($data2)
        
        ->rawColumns(['namasiswa','foto','idsiswa','jsk','tpl','tgl','nis','nisn','nik','alamat','asalsmp','hp','ayah','hpayah','ibu','hpibu','ktr','agama','namarombel']);
      return $dt->make(true);  
   
    
  }
  
//Hapus data siswa berdasarkan tahun angkatan dan membuat artsip data di tabel user_siswa_hapus
  function hapusSiswaAngkatan(){
    $params = [
			'title'	=>'Hapus Data Siswa',
			'label'	=>'HAPUS DATA SISWA ',
      'angkatan' =>$this->getTahunAngkatan(),
      'jm_angkatan'   => $this->CountSiswaTahunAngkatan(),
      'jm_alumni'   => $this->CountSiswaTahunAngkatanAlumni()
		];
		return view('crew/siswa_alumni/hapus')->with($params);
  }
  function hapusSiswaByAngkatan(Request $request){
    $tahunAngkatan = $request->thn;
    
    $data = User_siswa::where('ssaTahunAngkata',$tahunAngkatan)
    ->with('master_jurusan')
    ->with('master_rombel')
    ->with('profile_siswa',(function($query){
      $query->with('master_smp');
    }))
    ->get();
    
    $data3=$data4=$data5=$data6=array();
    $userBerhasil=$userGagal=$profileBerhasil=$profileGagal=0;
    foreach($data as $datas){
      //cek apakah sudah ada data siswa
      $cek = User_siswa_hapus::find($datas->ssaId);
      if(empty($cek)){
        //cek nama smp
        if(empty($datas->profile_siswa->master_smp->smpNama)){
          $smp = null;
        }
        else{
          $smp = $datas->profile_siswa->master_smp->smpNama;
        }
        //insert baru
        
        //data Akun
        $data3['ssaId'] = $datas->ssaId;
        $data3['ssaSklId'] = $datas->ssaSklId; 
        $data3['ssaJrsId'] = $datas->ssaJrsId; 
        $data3['ssaRblId'] = $datas->ssaRblId; 
        $data3['ssaRole'] = $datas->ssaRole; 
        $data3['ssaOrtuId'] = $datas->ssaOrtuId; 
        $data3['ssaKode'] = $datas->ssaKode; 
        $data3['ssaTahunAngkata'] = $datas->ssaTahunAngkata; 
        $data3['ssaStatusKeadaan'] = $datas->ssaStatusKeadaan;
        $data3['ssaAgama'] = $datas->ssaAgama;
        $data3['ssaUsername'] = $datas->ssaUsername;
        $data3['ssaPassword'] = $datas->ssaPassword;
        $data3['ssaFirstName'] = $datas->ssaFirstName;
        $data3['ssaLastName'] = $datas->ssaLastName;
        $data3['ssaFullName'] = $datas->ssaFullName;
        $data3['ssaEmail'] = $datas->ssaEmail;
        $data3['ssaQrCode'] = $datas->ssaQrCode;
        $data3['ssaStatusMasuk'] = $datas->ssaStatusMasuk;
        $data3['ssaPrakerind'] = $datas->ssaPrakerind;
        $data3['ssaFotoProfile'] = $datas->ssaFotoProfile;
        $data3['ssaHp'] = $datas->ssaHp;
        $data3['ssaWa'] = $datas->ssaWa;
        $data3['ssaIsActive'] = $datas->ssaIsActive;
        $data3['ssaIsDeleted'] = $datas->ssaIsDeleted;
        $data3['ssaCreated'] = $datas->ssaCreated;
        $data3['ssaUpdated'] = $datas->ssaUpdated;
        $data3['ssaDeleted'] = $datas->ssaDeleted;
        $data3['remember_token'] = $datas->remember_token;
        $data3['ssaNamaSmp'] = $smp;
        $data3['ssaNamaJurusan'] = $datas->master_jurusan->jrsSlag;
        $data3['ssaNamaRombel'] = $datas->master_rombel->rblNama;

        //data Profile
        $data4[]=$data3;
        $userBerhasil++;

        //cek jika username tidak kosong makan tampung data profile siswa
        if(!empty($datas->ssaUsername)){
          $data5['psId']=$datas->profile_siswa->psId;
          $data5['psSsaUsername']=$datas->profile_siswa->psSsaUsername;
          $data5['psJsk']=$datas->profile_siswa->psJsk;
          $data5['psTpl']=$datas->profile_siswa->psTpl;
          $data5['psTgl']=$datas->profile_siswa->psTgl;
          $data5['psNis']=$datas->profile_siswa->psNis;
          $data5['psNisn']=$datas->profile_siswa->psNisn;
          $data5['psNik']=$datas->profile_siswa->psNik;
          $data5['psNoKK']=$datas->profile_siswa->psNoKK;
          $data5['psHobi']=$datas->profile_siswa->psHobi;
          $data5['psTinggiBadan']=$datas->profile_siswa->psTinggiBadan;
          $data5['psJarak']=$datas->profile_siswa->psJarak;
          $data5['psTransport']=$datas->profile_siswa->psTransport;
          $data5['psAlamat']=$datas->profile_siswa->psAlamat;
          $data5['psRt']=$datas->profile_siswa->psRt;
          $data5['psRw']=$datas->profile_siswa->psRw;
          $data5['psDesa']=$datas->profile_siswa->psDesa;
          $data5['psKecamatan']=$datas->profile_siswa->psKecamatan;
          $data5['psKabupaten']=$datas->profile_siswa->psKabupaten;
          $data5['psProvinsi']=$datas->profile_siswa->psProvinsi;
          $data5['psNoKKS']=$datas->profile_siswa->psNoKKS;
          $data5['psNoPKH']=$datas->profile_siswa->psNoPKH;
          $data5['psNoKip']=$datas->profile_siswa->psNoKip;
          $data5['psAsalSmp']=$datas->profile_siswa->psAsalSmp;
          $data5['psNpsnSmp']=$datas->profile_siswa->psNpsnSmp;
          $data5['psNoUjianSmp']=$datas->profile_siswa->psNoUjianSmp;
          $data5['psKeteranganStatusMasuk']=$datas->profile_siswa->psKeteranganStatusMasuk;
          $data5['psIdPrakerind']=$datas->profile_siswa->psIdPrakerind;
          $data5['psKeterangan']=$datas->profile_siswa->psKeterangan;
          
          $data5['psNamaAyah']=$datas->profile_siswa->psNamaAyah;
          $data5['psAlamatAyah']=$datas->profile_siswa->psAlamatAyah;
          $data5['psNikAyah']=$datas->profile_siswa->psNikAyah;
          $data5['psTplAyah']=$datas->profile_siswa->psTplAyah;
          $data5['psTglAyah']=$datas->profile_siswa->psTglAyah;
          $data5['psPendidikanAyah']=$datas->profile_siswa->psPendidikanAyah;
          $data5['psHpAyah']=$datas->profile_siswa->psHpAyah;
          $data5['psWaAyah']=$datas->profile_siswa->psWaAyah;
          $data5['psPekerjaanAyah']=$datas->profile_siswa->psPekerjaanAyah;
          $data5['psPenghasilanAyah']=$datas->profile_siswa->psPenghasilanAyah;

          $data5['psNamaIbu']=$datas->profile_siswa->psNamaIbu;
          $data5['psAlamatIbu']=$datas->profile_siswa->psAlamatIbu;
          $data5['psTplIbu']=$datas->profile_siswa->psTplIbu;
          $data5['psTglIbu']=$datas->profile_siswa->psTglIbu;
          $data5['psNikIbu']=$datas->profile_siswa->psNikIbu;
          $data5['psPendidikanIbu']=$datas->profile_siswa->psPendidikanIbu;
          $data5['psHpIbu']=$datas->profile_siswa->psHpIbu;
          $data5['psWaIbu']=$datas->profile_siswa->psWaIbu;
          $data5['psPekerjaanIbu']=$datas->profile_siswa->psPekerjaanIbu;
          $data5['psPenghasilanIbu']=$datas->profile_siswa->psPenghasilanIbu;

          $data5['psNamaWali']=$datas->profile_siswa->psNamaWali;
          $data5['psAlamatWali']=$datas->profile_siswa->psAlamatWali;
          $data5['psTplWali']=$datas->profile_siswa->psTplWali;
          $data5['psTglWali']=$datas->profile_siswa->psTglWali;
          $data5['psNikWali']=$datas->profile_siswa->psNikWali;
          $data5['psPendidikanWali']=$datas->profile_siswa->psPendidikanWali;
          $data5['psPekerjaanWali']=$datas->profile_siswa->psPekerjaanWali;
          $data5['psHpWali']=$datas->profile_siswa->psHpWali;
          $data5['psWaWali']=$datas->profile_siswa->psWaWali;
          $data5['psPenghasilanWali']=$datas->profile_siswa->psPenghasilanWali;

          //tampung data array dalam array
          $data6[]=$data5;
          $profileBerhasil++;
        }
        else{
          $profileGagal++;
        }

      }
      else{
        $userGagal++;
      }
    } //end foreaceh

    if(!empty($data4)){
      $insert = User_siswa_hapus::insert($data4);
      if($insert){
        $insert2 = Profile_siswa_hapus::insert($data6);
        //hapus langsung data siswa pada tabel user siswa setelah di pindah ke tabel arsip hapus
        User_siswa::where('ssaTahunAngkata', $tahunAngkatan)->delete();

        if($insert2){
          $response = [
            'user_berhasil'     => $userBerhasil,
            'user_gagal'        => $userGagal,
            'profile_berhasil'  => $profileBerhasil,
            'profile_gagal'     => $profileGagal,
            'success'           =>'Berhasil',
          ];
          return response()->json($response,200);
        }
        else{
          $response = ['error'=>'Opsss ada yang gagal saat pindah profile siswa !!!'];
          return response()->json($response,503);
        }
      }
      else{
        $response = ['error'=>'Opsss ada yang gagal saat pindah user akun Siswa !!! '];
        return response()->json($response,503);
      }
      
    }
    else{
      $response = ['error'=>'Opsss Data Kosong'];
      return response()->json($response,503);
    }
  }
  
	
} //end CadminSiswaAlumni
