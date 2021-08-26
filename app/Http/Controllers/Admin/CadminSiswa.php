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

class CadminSiswa extends Controller
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
  function getPenghasilan()
  {
    if (Cache::has('penghasilan')){ $penghasilan = Cache::get('penghasilan'); }
    else{ 
      $penghasilan = Penghasilan::get();
      $chace = Cache::put('penghasilan', $penghasilan, ChaceJam());
    }
    return $penghasilan;
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

  function getKeadaan()
  {
    if (Cache::has('master_keadaan'.$this->getSkl())){ $data = Cache::get('master_keadaan'.$this->getSkl()); }
    else{ 
      $data = Master_status_keadaan::get();
      $chace = Cache::put('master_keadaan'.$this->getSkl(), $data, ChaceJam());
    }
    return $data;
  }
  function getTraspot()
  {
    if (Cache::has('transpot'.$this->getSkl())){ $data = Cache::get('transpot'.$this->getSkl()); }
    else{ 
      $data = Transportasi::get();
      $chace = Cache::put('transpot'.$this->getSkl(), $data, ChaceJam());
    }
    return $data;
  }
  function HapusCache($id=null){
    Cache::forget('user_siswa'.$this->getSkl());
    Cache::forget('user_siswa_off'.$this->getSkl());

    //hapus api json siswa berdasarkan id sekolah yg di proses data siswanya
    Cache::forget('api_json_siswa'.$id);

  }


	public function index()
	{
		
		//return view('crew/crew_home')->with($params);
  }
  
	public function add()
	{
		
		$params = [
			'title'	=>'Tambah Siswa',
			'label'	=>'FORM TAMBAH AKUN SISWA ',
			'getSekolah' => $this->getSekolah(),
			'getAgama' => $this->getAgama(),
			'getJurusan' => $this->getJurusan(),
		];
		return view('crew/siswa/add')->with($params);
	}
  public function editSiswa($id)
  {
    $idd = Crypt::decrypt($id);
    $datasiswa = User_siswa::find($idd);
    $params = [
      'title' =>'Edit Siswa',
      'label' =>'FORM EDIT SISWA ',
      'getSekolah' => $this->getSekolah(),
      'getAgama' => $this->getAgama(),
      'getJurusan' => $this->getJurusan(),
      'getPenghasilan' => $this->getPenghasilan(),
      'getSmp' => $this->getSmp(),
      'getKeadaan' => $this->getKeadaan(),
      'getTraspot' => $this->getTraspot(),
      'idsiswa' =>$id,
      'siswa' =>$datasiswa,
    ];
    return view('crew/siswa/edit')->with($params);
  }
  
	public function Insert(Request $request)
	{
		$pesan =[
        'ssaUsername.required' =>'Username Tidak Boleh Kosong',
        'unique' => 'Username Siswa Sudah Terdaftar Di Database',
      ];

      $validator = Validator::make(request()->all(), [
        'ssaUsername' => 'required|unique:user_siswa',
      ],$pesan);

    if($validator->fails()) {
      $response = $validator->messages();
    }
    else{	
    	$siswa = new User_siswa();
    	$siswa->ssaUsername = request()->ssaUsername;
    	$siswa->ssaPassword = Hash::make(request()->password);
    	$siswa->ssaFirstName = strtoupper(request()->firstname);
    	$siswa->ssaLastName = strtoupper(request()->lastname);
    	$siswa->ssaSklId = request()->skl;
    	$siswa->ssaJrsId = request()->jrs;
    	$siswa->ssaRole = 14;
      $siswa->ssaAgama  = $request->agama;
      $siswa->ssaHp = $request->hpsiswa;
      $siswa->ssaWa = $request->wasiswa;
    	$siswa->ssaCreatedBy = Auth::user()->admId;

    	$profil = new Profile_siswa();
    	$profil->psSsaUsername = $request->ssaUsername;
    	$profil->psJsk	= $request->jsk;
    	$profil->psTpl	= $request->tpl;
    	$profil->psTgl	= $request->tgl;
    	$profil->psNis	= $request->nis;
    	$profil->psNisn= $request->nisn;
    	$profil->psAsalSmp	= $request->asal_smp;
    	$profil->psAlamat	= $request->alamatsiswa;
    	$profil->psNamaAyah	= $request->ayah;
    	$profil->psNamaIbu	= $request->ibu;
    	$profil->psHpAyah	= $request->hpayah;
    	$profil->psHpIbu	= $request->hpibu;


    	if($siswa->save()){
    		if($profil->save()){ 
          $this->HapusCache($request->skl); //hapus cache
          $dataArray = array(
            'laIdAdmin' => Auth::user()->admId,
            'laNamaUser' =>strtoupper(FullNama()),
            'laNamaAksi' =>'Melakukan Tambah Data Siswa '.strtoupper(request()->firstname).' '.strtoupper(request()->lastname),
            'laDateTIme' =>date("Y-m-d H:i:s"),
          );
          Log_aksi_user::insert($dataArray);

    			$response = ['save'=>'Berhasil Tambah Siswa'];

    		}else{ $response = ['error'=>'Gagal Tambah Profil Siswa']; }
    	}
    	else{ $response = ['error'=>'Gagal Tambah Siswa']; }
	  }
	   
   	return response()->json($response,200);
	}
  public function Update($id,Request $request)
  {
      $idd = Crypt::decrypt($id); $username=$request->ssaUsername;
      $siswa = User_siswa::find($idd);
      $siswa->ssaUsername = $request->ssaUsername;
      $siswa->ssaFirstName = strtoupper(request()->firstname);
      $siswa->ssaLastName = strtoupper(request()->lastname);
      $siswa->ssaSklId = request()->skl;
      $siswa->ssaJrsId = request()->jrs;
      $siswa->ssaEmail = request()->email;
      $siswa->ssaRole = 14;
      $siswa->ssaStatusKeadaan = $request->ktrkeadaan;
      $siswa->ssaAgama  = $request->agama;
      $siswa->ssaHp = $request->hpsiswa;
      $siswa->ssaWa = $request->wasiswa;
      $siswa->ssaUpdated = date("Y-m-d H:i:s");
      $siswa->ssaUpdatedBy = Auth::user()->admId;
      $siswa->ssaIsActive = $request->status_akun ;

      if($siswa->save()){
        $profil = Profile_siswa::where('psSsaUsername',$username)->first();
        $profil->psJsk  = $request->jsk;
        $profil->psTpl  = $request->tpl;
        $profil->psTgl  = date('Y-m-d',strtotime ($request->tgl));
        $profil->psNis  = $request->nis;
        $profil->psNisn= $request->nisn;
        $profil->psNik= $request->nik;
        $profil->psNoKK= $request->kk;
        $profil->psAlamat = $request->alamatsiswa;
        $profil->psJarak = $request->jarak;
        $profil->psTransport = $request->transpot;
        $profil->psNoKKS = $request->nokks;
        $profil->psNoPKH = $request->nopkh;
        $profil->psNoKip = $request->nokip;
        $profil->psKeteranganStatusMasuk = $request->ktrpindah;
        $profil->psKeterangan = $request->ktrsiswa;
        $profil->psHobi = $request->hobi;
        $profil->psTinggiBadan = $request->tingibadan;
        //----------------------------------
        $profil->psRt = $request->rt;
        $profil->psRw = $request->rw;
        $profil->psDesa = $request->desa;
        $profil->psKecamatan = $request->keca;
        $profil->psKabupaten = $request->kabut;
        $profil->psProvinsi = $request->provinsi;

        // DATA AYAH
        $profil->psNamaAyah = $request->namaayah;
        $profil->psNikAyah = $request->nikayah;
        $profil->psTplAyah = $request->tplayah;
        $profil->psTglAyah = $request->tglayah;
        $profil->psPendidikanAyah = $request->pdkayah;
        $profil->psPekerjaanAyah = $request->pkrayah;
        $profil->psPenghasilanAyah = $request->phsayah;
        $profil->psAlamatAyah = $request->alamatayah;
        $profil->psHpAyah = $request->hpayah;
        // DATA IBU
        $profil->psNamaIbu  = $request->namaibu;
        $profil->psNikIbu = $request->nikibu;
        $profil->psTplIbu = $request->tplibu;
        $profil->psTglIbu = $request->tglibu;
        $profil->psPendidikanIbu = $request->pdkibu;
        $profil->psPekerjaanIbu = $request->pkribu;
        $profil->psPenghasilanIbu = $request->phsibu;
        $profil->psAlamatIbu = $request->alamatibu;
        $profil->psHpIbu  = $request->hpibu;
        //DATA WALI
        $profil->psNamaWali  = $request->namawali;
        $profil->psNikWali = $request->nikwali;
        $profil->psTplWali = $request->tplwali;
        $profil->psTglWali = $request->tglwali;
        $profil->psPendidikanWali = $request->pdkwali;
        $profil->psPekerjaanWali = $request->pkrwali;
        $profil->psPenghasilanWali = $request->phswali;
        $profil->psAlamatWali = $request->alamatwali;
        $profil->psHpWali  = $request->hpwali;
        //DATA SMP
        $profil->psAsalSmp  = $request->smp;
        $profil->psNpsnSmp  = $request->npsnsmp;
        $profil->psNoUjianSmp  = $request->nosmp;

        if($profil->save()){ 
          $this->HapusCache($request->skl); //hapus cache
          $dataArray = array(
            'laIdAdmin' => Auth::user()->admId,
            'laNamaUser' =>strtoupper(FullNama()),
            'laNamaAksi' =>'Melakukan Update Data Siswa '.strtoupper(request()->firstname).' '.strtoupper(request()->lastname),
            'laDateTIme' =>date("Y-m-d H:i:s"),
          );
          Log_aksi_user::insert($dataArray);

          $response = ['save'=>'Berhasil Update Data Siswa'];

        }else{ $response = ['error'=>'Gagal Update Data Siswa']; }
      }
      else{ $response = ['error'=>'Gagal Tambah Akun Siswa']; }
      return response()->json($response,200);
  }

	public function jsonSiswa()
	{
    //cache data redis ---------------------------------------------------------------------------
    if (Cache::has('user_siswa')){ $data = Cache::get('user_siswa'.$this->getSkl()); }
    else{ 
      if(empty($this->getSkl())){  
        $data = User_siswa::where('ssaIsActive',1)->with('master_jurusan')->get();
      }
      else{
        $data = User_siswa::where('ssaSklId',$this->getSkl())
        ->where('ssaIsActive',1)
        ->with('master_jurusan')
        ->get(); 
      }

      Cache::put('user_siswa'.$this->getSkl(), $data, ChaceJam());
    }
    //cache data redis ---------------------------------------------------------------------------
    
		$dt= DataTables::of($data)
		->addColumn('no','')
    ->addColumn('idsiswa',function ($data) { return Crypt::encrypt($data->ssaId);});

		return $dt->make(true); 
	}
  public function jsonAllSiswa(Request $request)
  {
    //cache data redis ---------------------------------------------------------------------------
    if (Cache::has('user_siswa_all'.$this->getSkl())){ $data = Cache::get('user_siswa_all'.$this->getSkl()); }
    else{ 
      if(empty($this->getSkl())){  
        $data = User_siswa::where('ssaIsActive',1)
        ->with('master_jurusan')
        ->with('profile_siswa')
        ->with('anggota_rombel')->get();
      }
      else{
        $data = User_siswa::where('ssaIsActive',1)
        ->where('ssaSklId',$this->getSkl())
        ->with('master_jurusan')
        ->with('profile_siswa')
        ->with('anggota_rombel')->get();
      }   
      $cek = Cache::put('user_siswa_all'.$this->getSkl(), $data, ChaceJam()); 
    }
    //cache data redis ---------------------------------------------------------------------------
    
      foreach ($data as $value) {
        $cek = array(
          'username' =>$value->ssaUsername,
          'idsiswa' => Crypt::encrypt($value->ssaId),
          'namasiswa' =>$value->ssaFirstName.' '.$value->ssaLastName,
          'jurusan' =>$value['master_jurusan']['jrsSlag'],
          'updateat' =>$value->ssaUpdated,
          'status' =>$value->ssaIsActive,
          'foto' => FotoSiswaSmall($value->ssaFotoProfile),
          'jsk' => !empty($value['profile_siswa']['psJsk']) ? $value['profile_siswa']['psJsk'] : '<span class="badge badge-warning">KOSONG</span>',
          'tpl' => !empty($value['profile_siswa']['psTpl']) ? $value['profile_siswa']['psTpl'] : '<span class="badge badge-warning">KOSONG</span>',
          'tgl' => !empty($value['profile_siswa']['psTgl']) ? $value['profile_siswa']['psTgl'] : '<span class="badge badge-warning">KOSONG</span>',
          'nis' => !empty($value['profile_siswa']['psNis']) ? $value['profile_siswa']['psNis'] : '<span class="badge badge-warning">KOSONG</span>',
          'nisn' => !empty($value['profile_siswa']['psNisn']) ? $value['profile_siswa']['psNisn'] : '<span class="badge badge-warning">KOSONG</span>',
          'nik' => !empty($value['profile_siswa']['psNik']) ? $value['profile_siswa']['psNik'] : '<span class="badge badge-warning">KOSONG</span>',
          'alamat' => !empty($value['profile_siswa']['psAlamat']) ? $value['profile_siswa']['psAlamat'] : '<span class="badge badge-warning">KOSONG</span>',
          'asalsmp' => !empty($value['profile_siswa']['master_smp']['smpNama']) ? $value['profile_siswa']['master_smp']['smpNama'] : '<span class="badge badge-warning">KOSONG</span>',
          'hp' => !empty($value['ssaHp']) ? $value['ssaHp'] : '<span class="badge badge-warning">KOSONG</span>',
          'wa' => !empty($value['ssaWa']) ? $value['ssaWa'] : '<span class="badge badge-warning">KOSONG</span>',
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

    $dt = DataTables::of($data2)
        
        ->rawColumns(['namasiswa','foto','idsiswa','jsk','tpl','tgl','nis','nisn','nik','alamat','asalsmp','hp','ayah','hpayah','ibu','hpibu','ktr','agama','namarombel']);
    return $dt->make(true);  
    //   ->addColumn('namasiswa',function ($data) { 
    //     return $data->ssaFirstName.' '.$data->ssaLastName;
    //   })
    //   ->addColumn('jsk',function ($data) { 
    //     return $data['profile_siswa']['psJsk'];
    //   })
    //   ->addColumn('tpl',function ($data) { 
    //     return $data['profile_siswa']['psTpl'];
    //   })
    //   ->addColumn('tgl',function ($data) { 
    //     return $data['profile_siswa']['psTgl'];
    //   })
    //   ->addColumn('nis',function ($data) { 
    //     return $data['profile_siswa']['psNis'];
    //   })
    //   ->addColumn('nisn',function ($data) { 
    //     return $data['profile_siswa']['psNisn'];
    //   })
    //   ->addColumn('nik',function ($data) { 
    //     return $data['profile_siswa']['psNik'];
    //   })
    //   ->addColumn('alamat',function ($data) { 
    //     return $data['profile_siswa']['psAlamat'];
    //   })
    //   ->addColumn('asalsmp',function ($data) { 
    //     return $data['profile_siswa']['master_smp']['smpNama'];
    //   })
    //   ->addColumn('hp',function ($data) { 
    //     return $data['profile_siswa']['psHp'];
    //   })
    //   ->addColumn('ayah',function ($data) { 
    //     return $data['profile_siswa']['psNamaAyah'];
    //   })
    //   ->addColumn('hpayah',function ($data) { 
    //     return $data['profile_siswa']['psHpAyah'];
    //   })
    //   ->addColumn('ibu',function ($data) { 
    //     return $data['profile_siswa']['psNamaIbu'];
    //   })
    //    ->addColumn('hpibu',function ($data) { 
    //     return $data['profile_siswa']['psHpIbu'];
    //   })
    //    ->addColumn('ktr',function ($data) { 
    //     return $data['profile_siswa']['psKeterangan'];
    //   })
    //   ->addColumn('agama',function ($data) { 
    //     return $data['profile_siswa']['master_agama']['agmKode'];
    //   })
    //   ->addColumn('namarombel',function ($data) { 
    //     return $data->anggota_rombel['master_rombel']['master_kelas']['klsKode'].$data->anggota_rombel['master_rombel']['rblNama'];
    //   })
    //   ->rawColumns(['namasiswa','jsk','tpl','tgl','nis','nisn','nik','alamat','asalsmp','hp','ayah','hpayah','ibu','hpibu','ktr','agama','namarombel']);
     
    
    
  }
  function jsonSiswaOff(){
    //cache data redis ---------------------------------------------------------------------------
    if (Cache::has('user_siswa_off'.$this->getSkl())){ $data = Cache::get('user_siswa_off'.$this->getSkl()); }
    else{ 
      if(empty($this->getSkl())){  
        $data3 = User_siswa::where('ssaIsActive',0)
        ->with('master_jurusan')
        ->with('profile_siswa')
        ->with('master_status_keadaan')
        ->with('anggota_rombel')->get();
      }
      else{
        $data2 = User_siswa::where('ssaIsActive',0)
        ->where('ssaSklId',$this->getSkl())
        ->with('master_jurusan')
        ->with('profile_siswa')
        ->with('master_status_keadaan')
        ->with('anggota_rombel')->get();
      }
      if(empty($data3)){
        $data=[]; //jika tidak ada data maka kasih data array kosong
      }
      else{
        $data = $data3;
      }   
      $cek = Cache::put('user_siswa_off'.$this->getSkl(), $data, ChaceJam()); 
    }
    //cache data redis ---------------------------------------------------------------------------
    
      foreach ($data as $value) {
        $cek = array(
          'username' =>$value->ssaUsername,
          'idsiswa' => Crypt::encrypt($value->ssaId),
          'namasiswa' =>$value->ssaFirstName.' '.$value->ssaLastName,
          'jurusan' =>$value['master_jurusan']['jrsSlag'],
          'updateat' =>$value->ssaUpdated,
          'status' =>$value->ssaIsActive,
          'status_keadaan' =>$value->master_status_keadaan->mstNama,
          'foto' => FotoSiswaSmall($value->ssaFotoProfile),
          'catatan' => $value->profile_siswa->psKeteranganStatusMasuk,
          'jsk' => !empty($value['profile_siswa']['psJsk']) ? $value['profile_siswa']['psJsk'] : '<span class="badge badge-warning">KOSONG</span>',
          'tpl' => !empty($value['profile_siswa']['psTpl']) ? $value['profile_siswa']['psTpl'] : '<span class="badge badge-warning">KOSONG</span>',
          'tgl' => !empty($value['profile_siswa']['psTgl']) ? $value['profile_siswa']['psTgl'] : '<span class="badge badge-warning">KOSONG</span>',
          'nis' => !empty($value['profile_siswa']['psNis']) ? $value['profile_siswa']['psNis'] : '<span class="badge badge-warning">KOSONG</span>',
          'nisn' => !empty($value['profile_siswa']['psNisn']) ? $value['profile_siswa']['psNisn'] : '<span class="badge badge-warning">KOSONG</span>',
          'nik' => !empty($value['profile_siswa']['psNik']) ? $value['profile_siswa']['psNik'] : '<span class="badge badge-warning">KOSONG</span>',
          'alamat' => !empty($value['profile_siswa']['psAlamat']) ? $value['profile_siswa']['psAlamat'] : '<span class="badge badge-warning">KOSONG</span>',
          'asalsmp' => !empty($value['profile_siswa']['master_smp']['smpNama']) ? $value['profile_siswa']['master_smp']['smpNama'] : '<span class="badge badge-warning">KOSONG</span>',
          'hp' => !empty($value['ssaHp']) ? $value['ssaHp'] : '<span class="badge badge-warning">KOSONG</span>',
          'wa' => !empty($value['ssaWa']) ? $value['ssaWa'] : '<span class="badge badge-warning">KOSONG</span>',
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

    $dt = DataTables::of($data2)
    ->rawColumns(['namasiswa','foto','idsiswa','jsk','tpl','tgl','nis','nisn','nik','alamat','asalsmp','hp','ayah','hpayah','ibu','hpibu','ktr','agama','namarombel']);
    return $dt->make(true);  
  }
	public function lihatSiswa()
	{
		$params = [
			'title'	=>'Add Siswa',
			'label'	=>'<i class="icon-reading mr-2"></i> LIHAT DATA SISWA',
		];
		return view('crew/siswa/view')->with($params);
	}
  public function allSiswa()
  {
    $params = [
      'title' =>'Semua Data Siswa',
      'label' =>'<b>LIHAT SEMUA DATA SISWA</b>',
    ];
    return view('crew/siswa/view_all')->with($params);
  }
  function LihatSiswaOff(){
    $params = [
      'title' =>'Data Siswa Off',
      'label' =>'<b>LIHAT SEMUA DATA SISWA OFF</b>',
    ];
    return view('crew/siswa/view_siswa_off')->with($params);
  }

  public function deleteSiswa($id)
  {
    $idd = Crypt::decrypt($id);
    $idsiswa= User_siswa::find($idd);
    $idsiswa->ssaIsActive = 0;
    $idsiswa->ssaIsDeleted = 0;
    $idsiswa->ssaDeleted = date("Y-m-d H:i:s");
    $idsiswa->ssaDeletedBy = Auth::user()->admId;
    if($idsiswa->save()){
      $this->HapusCache($idsiswa->ssaSklId); //hapus cache
      $dataArray = array(
        'laIdAdmin' => Auth::user()->admId,
        'laNamaUser' =>strtoupper(FullNama()),
        'laNamaAksi' =>'Melakukan Hapus Siswa '.$idsiswa->ssaFirstName.' '.$idsiswa->ssaLastName,
        'laDateTIme' =>date("Y-m-d H:i:s"),
      );
      Log_aksi_user::insert($dataArray);
      return response()->json([
        'success' => 'Data Berhasil Di Hapus'
      ]);
    }
    else{
      return response()->json([
        'error' => 'Gagal Hapus Data!'
      ]);
    }
  }
  //import data siswa
  public function formImportSiswa()
  {
    $params = [
      'title' =>'Import Data Siswa',
      'label' =>'<b>FORM IMPORT DATA SISWA </b>',
    ];
    return view('crew/siswa/form_import_data_siswa')->with($params);
  }

  public function ImportDataSiswa(Request $request)
  {
    $file = $request->file('import_data_siswa');
    $extensi = $file->getClientOriginalExtension();

    if($extensi == 'xls' or $extensi == 'xlsx' ){
      $import = new ImportDataSiswa();
      Excel::import($import,$file);
      $insert = get_object_vars($import); //mengubah objek ke data array
      if($insert['hasil'] ==1){
        $response = ['save'=>'Berhasil Upload Data '.$insert['jm'].' Siswa'];
      }
      elseif ($insert['hasil'] == 0) {
        $response = ['error'=>'Gagal Upload Data Siswa'];
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
  function ImportUpdateDataSiswa(Request $request){
    $key = $request->nama_kolom_key;
    $kolom = $request->nama_kolom; //nama kolom dari inputan
    $file = $request->file('import_update_data_siswa');
    $extensi = $file->getClientOriginalExtension();
    if($extensi == 'xls' or $extensi == 'xlsx' ){
      $import = new ImportUpdateDataSiswa($kolom,$key);
      Excel::import($import,$file);
      $insert = get_object_vars($import); //mengubah objek ke data array

      if($insert['hasil'] ==1){
        $response = ['saveupdate'=>'Berhasil Update Data '.$insert['jm'].' Siswa'];
      }
      elseif ($insert['hasil'] == 0) {
        $response = ['errorupdate'=>'Gagal Update Data Siswa'];
      }
      else{
        $response = ['errorrupdate'=>'Sistem Error'];
      }


    }
    else{
      $response = ['errorrupdate'=>'Cek File, Pastikan Ber extensi atau Format XLSX'];
    }
    return redirect('crew/form-import-siswa')->with($response);    
    
  }
  function RincianSiswa(){
    $params = [
			'title'	=>'Rincian Data Siswa',
			'label'	=>'<i class="icon-reading mr-2"></i> Rincian Data Siswa ',
		];
		return view('crew/siswa/rincian_siswa')->with($params);
  }
  function RincianAgamaSiswa(){
    $params = [
			'title'	=>'Data Agama',
      'label'	=>'<i class="icon-reading mr-2"></i> Data Agama Siswa ',
      'getAgama' => $this->getAgama(),
		];
		return view('crew/siswa/agama_siswa')->with($params);
  }
  function RincianTranspotSiswa(){
    $params = [
      'title'	=>'Data Transportasi',
      'label'	=>'<i class="icon-reading mr-2"></i> Data Transportasi Siswa ',
      'getTraspot' => $this->getTraspot(),
    ];
    return view('crew/siswa/transpot_siswa')->with($params);
  }
  function resetPasswordSiswa(Request $request)
  {
    $set = Setting::first();
    $idd = Crypt::decrypt($request->id);
    $siswa = User_siswa::find($idd);
    $siswa->ssaPassword  = Hash::make($set->setResetPassSiswa);
    $siswa->ssaUpdated = date("Y-m-d H:i:s");
    $siswa->ssaUpdatedBy = Auth::user()->admId;
    
    if($siswa->save()){
      $this->HapusCache($siswa->ssaSklId); //hapus cache
      $dataArray = array(
        'laIdAdmin' => Auth::user()->admId,
        'laNamaUser' =>strtoupper(FullNama()),
        'laNamaAksi' =>'Melakukan Reset Password '.$siswa->ssaFirstName.' '.$siswa->ssaLastName,
        'laDateTIme' =>date("Y-m-d H:i:s"),
      );
      Log_aksi_user::insert($dataArray);

      return response()->json([
        'success' => 'Akun Berhasil Di Reset Passwordnya '.$set->setResetPassSiswa
      ]);
    }
    else{
      return response()->json([
        'error' => 'Opsss Gagal !'
      ]);
    }
  }
	
}
