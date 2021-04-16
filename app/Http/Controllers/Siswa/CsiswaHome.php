<?php

//membaca lokasi Controller dalam Folder
namespace App\Http\Controllers\Siswa;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; //menjalankan printah Hash Enkripsi
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use DataTables;
use Auth;//menjalankan printah auth
use App\User_siswa;
use App\Master_sekolah;
use App\Master_agama;
use App\Master_jurusan;
use App\Profile_siswa;
use App\Penghasilan;
use App\Master_smp;
use App\Master_status_keadaan;
use App\Transportasi;
use App\Tahun_ajaran;
use App\Anggota_rombel;
use App\Master_wali_kelas;
use App\Log_aksi_user;
use App\Master_mapel_jadwal;
use App\Master_mapel_jadwal_rombel;
use App\Absen_mapel;
use App\Absen_finger_siswa;
use App\Semester;
use App\Master_jam_sekolah;

class CsiswaHome extends Controller
{
	public function __construct()
  {
    $this->middleware('auth:siswa');
	}
	function getSkl()
  {
  	$idskl = Auth::user()->ssaSklId; 
  	return $idskl;
	}
	function getIdSiswa()
  {
  	$id = Auth::user()->ssaId; 
  	return $id;
  }
  function getIdJrsSiswa()
  {
  	$id = Auth::user()->ssaJrsId; 
  	return $id;
  }
  
  function getRombelSiswa()
  {
  	$data = Auth::user()->ssaRblId; 
  	return $data;
  }
  
  
  function getDataRombel()
  {
    //mengabil data rombel bersarakna id rombel siswa
    //cache data redis ---------------------------------------------------------------------------
    if (Cache::has('getDataRombelSiswa'.$this->getRombelSiswa())){ $data = Cache::get('getDataRombelSiswa'.$this->getRombelSiswa()); }
    else{ 
      $data = Anggota_rombel::where('arbRblId',$this->getRombelSiswa())
      ->with('master_rombel')
      ->with('master_kelas')
      ->with('master_jurusan')
      ->first();
      Cache::put('getDataRombelSiswa'.$this->getRombelSiswa(), $data, ChaceMenit() );
    }
    return $data;
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
  function getTahunAjaran()
  {
    if(Cache::has('tahunajaran')){ $data = Cache::get('tahunajaran'); }
    else{ 
      $data = Tahun_ajaran::where('tajrIsActive',1)->first();
      $chace = Cache::put('tahunajaran', $data, ChaceJam());
    }
    return $data;
  }
  
//----------------------------------------------------------------------------
	public function index()
	{
		$params = [
			'title'	=>'HOME SISWA',
		];
		return view('siswa/siswa_home')->with($params);
	}
	function EditProfile(){
    //cache data redis ---------------------------------------------------------------------------
      if (Cache::has('datasiswa'.$this->getIdSiswa())){ $data = Cache::get('datasiswa'.$this->getIdSiswa()); }
      else{ 
        $data = User_siswa::find($this->getIdSiswa());
        $chace = Cache::put('datasiswa'.$this->getIdSiswa(), $data, ChaceMenit() );
      }
    //cache data redis --------------------------------------------------------------------------- 
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
      'idsiswa' =>$this->getIdSiswa(),
      'siswa' =>$data,
    ];
		return view('siswa/edit')->with($params);
  }
  //update data siswa -------------------------------------------------------
  public function UpdateSiswa(Request $request)
  {
      
      $siswa = User_siswa::find($this->getIdSiswa());
      //$siswa->ssaUsername = $request->ssaUsername;
      $siswa->ssaFirstName = strtoupper(request()->firstname);
      $siswa->ssaLastName = strtoupper(request()->lastname);
      // $siswa->ssaSklId = request()->skl;
      // $siswa->ssaJrsId = request()->jrs;
      $siswa->ssaEmail = request()->email;
      $siswa->ssaRole = 14;
      $siswa->ssaStatusKeadaan = $request->ktrkeadaan;
      $siswa->ssaAgama  = $request->agama;
      $siswa->ssaUpdated = date("Y-m-d H:i:s");
      $siswa->ssaHp = $request->hpsiswa;
      $siswa->ssaWa = $request->wasiswa;
      $siswa->ssaUpdatedBy = Auth::user()->ssaId; 

      $username=$request->ssaUsername;

      if($siswa->save()){
        $profil = Profile_siswa::where('psSsaUsername',$username)->first();
        $profil->psJsk  = $request->jsk;
        $profil->psTpl  = strtoupper($request->tpl);
        $profil->psTgl  = date('Y-m-d',strtotime ($request->tgl));
        // $profil->psNis  = $request->nis;
        // $profil->psNisn= $request->nisn;
        $profil->psNik= $request->nik;
        $profil->psNoKK= $request->kk;
        $profil->psAlamat = strtoupper($request->alamatsiswa);
        $profil->psJarak = $request->jarak;
        $profil->psTransport = $request->transpot;
        $profil->psNoKKS = $request->nokks;
        $profil->psNoPKH = $request->nopkh;
        $profil->psNoKip = $request->nokip;
        // $profil->psKeteranganStatusMasuk = $request->ktrpindah;
        // $profil->psKeterangan = $request->ktrsiswa;
        $profil->psHobi = strtoupper($request->hobi);
        $profil->psTinggiBadan = $request->tingibadan;
        //----------------------------------
        $profil->psRt = $request->rt;
        $profil->psRw = $request->rw;
        $profil->psDesa = strtoupper($request->desa);
        $profil->psKecamatan = strtoupper($request->keca);
        $profil->psKabupaten = strtoupper($request->kabut);
        $profil->psProvinsi = strtoupper($request->provinsi);

        // DATA AYAH
        $profil->psNamaAyah = strtoupper($request->namaayah);
        $profil->psNikAyah = $request->nikayah;
        $profil->psTplAyah = strtoupper($request->tplayah);
        $profil->psTglAyah = $request->tglayah;
        $profil->psPendidikanAyah = $request->pdkayah;
        $profil->psPekerjaanAyah = $request->pkrayah;
        $profil->psPenghasilanAyah = $request->phsayah;
        $profil->psAlamatAyah = strtoupper($request->alamatayah);
        $profil->psHpAyah = $request->hpayah;
        // DATA IBU
        $profil->psNamaIbu  = strtoupper($request->namaibu);
        $profil->psNikIbu = $request->nikibu;
        $profil->psTplIbu = strtoupper($request->tplibu);
        $profil->psTglIbu = $request->tglibu;
        $profil->psPendidikanIbu = $request->pdkibu;
        $profil->psPekerjaanIbu = $request->pkribu;
        $profil->psPenghasilanIbu = $request->phsibu;
        $profil->psAlamatIbu = strtoupper($request->alamatibu);
        $profil->psHpIbu  = $request->hpibu;
        //DATA WALI
        $profil->psNamaWali  = strtoupper($request->namawali);
        $profil->psNikWali = $request->nikwali;
        $profil->psTplWali = strtoupper($request->tplwali);
        $profil->psTglWali = $request->tglwali;
        $profil->psPendidikanWali = $request->pdkwali;
        $profil->psPekerjaanWali = $request->pkrwali;
        $profil->psPenghasilanWali = $request->phswali;
        $profil->psAlamatWali = strtoupper($request->alamatwali);
        $profil->psHpWali  = $request->hpwali;
        //DATA SMP
        $profil->psAsalSmp  = $request->smp;
        $profil->psNpsnSmp  = $request->npsnsmp;
        $profil->psNoUjianSmp  = $request->noujiannsmp;
        if($profil->save()){ 
          Cache::forget('datasiswa'.$this->getIdSiswa());
          $dataArray = array(
            'laIdSiswa' =>$this->getIdSiswa(),
            'laNamaUser' =>strtoupper(request()->firstname).' '.strtoupper(request()->lastname),
            'laNamaAksi' =>'Melakukan Update Data Profile',
            'laDateTIme' =>date("Y-m-d H:i:s"),
          );
          Log_aksi_user::insert($dataArray);

          $response = ['save'=>'Berhasil Update Data Siswa'];

        }else{ $response = ['error'=>'Gagal Update Data Siswa']; }
      }
      else{ $response = ['error'=>'Gagal Tambah Akun Siswa']; }
      return response()->json($response,200);
  }
//Jadwal----------------------------------------------------------------------
  function JadwalSiswa(){
    $params = [
      'title' =>'Jadwal Siswa',
      'label' =>'JADWAL SISWA ',
    ];
    return view('siswa/jadwal')->with($params);
  }
  function AnggotaKelas()
  {
    
    //ambil data dari get data rombel yang berelasi eloquen
    $kelas = $this->getDataRombel()->master_rombel->master_kelas->klsKode;
    $rombel = $this->getDataRombel()->master_rombel->rblNama;
    $jurusan = $this->getDataRombel()->master_jurusan->jrsNama;
    $thn = $this->getTahunAjaran();
    $sekolah = $this->getSekolah()->first();
    $tahunajaran = "Tahun Pelajaran ".$thn->tajrNama;
    $params = [
      'title' =>'Anggota Kelas',
      'label' =>'ANGGOTA KELAS ',
      'tahun_pelajara' =>$tahunajaran,
      'sekolah' =>$sekolah->sklNama,
      'rombel' => 'KELAS '.$kelas.' '.$rombel,
      'jurusan' =>$jurusan,
    ];
    
    return view('siswa/kelas')->with($params);
    
  }
  function UploadFoto(){
    $params = [
      'title' =>'Jadwal Siswa',
      'label' =>'JADWAL SISWA ',
    ];
    return view('siswa/uploadfoto')->with($params);
  }
  function WaliKelas(){
    //tampilkan data wali kelas
    $wali = Master_wali_kelas::where('wakasRblId',$this->getRombelSiswa())->first();
    $params = [
      'title' =>'wali kelas',
      'label' =>'WALI KELAS',
      'wakas' =>$wali,
      'ups' =>'<b>Data Wali Kelas Belum Bisa Tampil </b>',
    ];
    return view('siswa/walikelas')->with($params);
  }
  function DataSekolah()
  {
    //tampilkan data sekolah
    $dataskl = Master_sekolah::find($this->getSkl());
    $params = [
      'title' =>'Data Sekolah',
      'label' =>'DATA SEKOLAH',
      'sekolah' =>$dataskl,
      'ups' =>'<b>Data Sekolah Belum Bisa Tampil </b>',
    ];
    return view('siswa/sekolah')->with($params);
  }

  function Password(){
    $params = [
      'title' =>'Password Siswa',
      'label' =>'<b>Ganti Password Siswa</b>',
    ];
    return view('siswa/password')->with($params);
  }
  function UpdatePassword(Request $request){
   
    $siswa = User_siswa::find($this->getIdSiswa());
    $siswa->ssaPassword  = Hash::make($request->newpassword);
    $siswa->ssaUpdated = date("Y-m-d H:i:s");
    $siswa->ssaUpdatedBy = Auth::user()->ssaId;
    if($siswa->save()){ 
      Cache::forget('user_siswa');
      $dataArray = array(
        'laIdSiswa' =>$this->getIdSiswa(),
        'laNamaUser' =>strtoupper(Auth::user()->ssaFirstName).' '.strtoupper(Auth::user()->ssaLastName),
        'laNamaAksi' =>'Mengganti Password',
        'laDateTIme' =>date("Y-m-d H:i:s"),
      );
      Log_aksi_user::insert($dataArray);
      $response = ['save'=>'Berhasil Ganti Password'];
    }
    else{ $response = ['error'=>'Gagal Ganti Password']; }
    
    return response()->json($response,200);

  }

  function jsonSiswaAnggotaKelas()
  {
    $rombel = $this->getRombelSiswa();
    //cache data redis ---------------------------------------------------------------------------
    if (Cache::has('anggota.rombel'.$rombel)){ $data = Cache::get('anggota.rombel'.$rombel); }
    else{ 
      $data2 = User_siswa::where('ssaIsActive',IsAcktive())
      ->where('arbRblId',$rombel)
      ->with('profile_siswa')
      ->with('master_jurusan')
      ->with('master_rombel')
      ->with('anggota_rombel')
      ->orderBy('ssaFirstName','ASC')
      ->leftjoin('anggota_rombel', 'ssaUsername', '=', 'arbSsaId')
      ->get();

      foreach ($data2 as $value) {
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
          'jsk' => !empty($value['profile_siswa']['psJsk']) ? $value['profile_siswa']['psJsk'] : '<span class="badge badge-warning">BELUM DI ISI</span>',
          'asalsmp' => !empty($value['profile_siswa']['master_smp']['smpNama']) ? $value['profile_siswa']['master_smp']['smpNama'] : '<span class="badge badge-warning">BELUM DI ISI</span>',
          'hp' => !empty($value->ssaHp) ? $value->ssaHp : '<span class="badge badge-warning">BELUM DI ISI</span>',
          'hpwa' => !empty($value->ssaWa) ? $value->ssaWa : '<span class="badge badge-warning">BELUM DI ISI</span>',
          'agama' => !empty($value->ssaAgama) ? $value->ssaAgama : '<span class="badge badge-warning">BELUM DI ISI</span>',
          'namarombel' => !empty($value->anggota_rombel['master_rombel']['master_kelas']['klsKode']) ? $value->anggota_rombel['master_rombel']['master_kelas']['klsKode'].$value->anggota_rombel['master_rombel']['rblNama'] : '<span class="badge badge-warning">BELUM DI ISI</span>',
        );
        $data[] = $cek;
      }
      Cache::put('anggota.rombel'.$rombel, $data, ChaceJam());    

    }
    
    //cache data redis ---------------------------------------------------------------------------
      
    $dt = DataTables::of($data)
    ->rawColumns(['agama','hp','hpwa','asalsmp']);
    return $dt->make(true); 
    
   
  }
//absensi --------------------------------------------------------------------------
  function AbsensiMapel(){
    $koderombel = $this->getDataRombel()->master_rombel->rblKode;
    $idrombel = $this->getDataRombel()->master_rombel->rblId;
    $hari = date("d-m-Y");
    $namahari = date('l', strtotime($hari));
    
    //cache data redis ---------------------------------------------------------------------------
    if (Cache::has('jadwal.mapel.rombel'.$idrombel)){ $data = Cache::get('jadwal.mapel.rombel'.$idrombel); }
    else{ 
      $data = Master_mapel_jadwal::where('majdRblKode',$koderombel)
      ->where('majdHari',$namahari)
      ->where('majdTahunAjaran',$this->getTahunAjaran()->tajrKode)
      ->with('user_guru')
      ->get();
      Cache::put('jadwal.mapel.rombel'.$idrombel, $data, ChaceJam()); 
    }
    
    $params = [
      'title' =>'Absensi Mapel',
      'label' =>'<b>ABSENSI MAPEL</b>',
      'getjadwal'  =>$data,
    ];

    return view('siswa/absen_mapel')->with($params);
  }
  function AbsensiSekolah(){
    $koderombel = $this->getDataRombel()->master_rombel->rblKode;
    $idrombel = $this->getDataRombel()->master_rombel->rblId;
    $hari = date("d-m-Y");
    $namahari = date('l', strtotime($hari));
    
    
    
    $params = [
      'title' =>'Absen Sekolah',
      'label' =>'<bABSENSI>ABSEN SEKOLAH</bABSENSI>',
      'labelkehadiran' =>'<b>Absensi Kehadiran Sekolah</b>',
      'pesankehadiran' =>'Silahkan Klik Tombol di Bawah ini <br>Untuk Melakukan Absen',
      'btnkehadiran' =>'<b>ABSEN KEHADIRAN</b>',
      
    ];

    return view('siswa/absen_sekolah')->with($params);
  }
  function TotalAbsensiSekolah(){
    $params = [
      'title' =>'Total Absen Sekolah',
      'label' =>'<b>TOTAL ABSEN SEKOLAH</b>',
    ];
    return view('siswa/total_absen_sekolah')->with($params);

  }

  function InsertAbsensiMapelSiswa(Request $request){
    $namahari = date('l');
    $tgl = date('Y-m-d');
    $jamsekarang = date("H:i");
    $tahunajaran = $this->getTahunAjaran();
    $username = Username();
    $mapel = $request->mapel;
    $jadwal = Master_mapel_jadwal::find($mapel);
    $rombel = $this->getRombelSiswa();
    $bulan = date('n');

    $cek = Absen_mapel::where('abmpSsaUsername',$username)
				->where('abmpMajdId',$mapel)
        ->where('abmpTgl',$tgl);
    //cek apakah sudah melakukan absen pada hari ini
    if(!empty($cek->first())){
      $jam = $cek->first()->abmpJamin;
    }
    
    if($cek->count() > 0){
      $response = ['warning'=>'Anda Sudah Melakukan Absen Mapel '.$jadwal->majdNama.' Pada Jam '.$jam];
      return response()->json($response,200);
    }
    else{
      //jika blm melakukan absen pada hari ini proses insert
      $data2[]=array(
        'abmpSklId' => $this->getSkl(),
        'abmpRblId' => $rombel,
        'abmpMajdId' => $mapel,
        'abmpTajrKode' =>$tahunajaran->tajrKode,
        'abmpSmKode'	=>$this->getSemester(),
        'abmpSsaUsername' => $username,
        'abmpTgl' => $tgl,
        'abmpHari' => $namahari,
        'abmpJamin' =>$jamsekarang,
        'abmpJamout' =>$jadwal->majdJamAkhir,
        'abmpAkKode' =>'H',
        'abmpUserAksi' =>1,
        );
      $add = Absen_mapel::insert($data2);
      if($add){
        //jika berhasil isnert absen
        //hapus cache
        Cache::forget('rekap_absen_siswa'.$rombel.$mapel);
        Cache::forget('total_absen_siswa'.$rombel.$mapel);
        Cache::forget('total_absen_siswa_sekolah'.$username.$bulan);

        $response = ['success'=>'Berhasil Absen Mapel '.$jadwal->majdNama];
        return response()->json($response,200);
      }
      else{
        //jika gaal insert absen
        $response = ['error'=>'Gagal Absen '];
        return response()->json($response,202);
      }
    }

  }
  //tambah abseni sekolah ---------------------------
  function InsertAbsensisekolahSiswa(){
    $namahari = date('l');
    $tgl = date('Y-m-d');
    $jamsekarang = date("H:i");
    $tahunajaran = $this->getTahunAjaran()->tajrKode;
    $semster = $this->getSemester();
    $username = Username();
    $idsiswa = $this->getIdSiswa();
    $rombel = $this->getRombelSiswa();
    $skl = $this->getSkl();

    $tglabsenpesan = date('d-m-Y', strtotime($tgl));

    //get status Kehadiran siswa berdasarkan jam masuk siswa
    $status = CekKehadiranJamMasuk($jamsekarang);
    $bulan = date('n');
    
    $cek = Absen_finger_siswa::where('afsDatetime',$tgl)
    ->where('afsSsaUsername',$username);

    if(!empty($cek->first())){
      $jam = $cek->first()->afsIn;
    }

    if($cek->count() > 0){
      //jika ada absen maka tampilkan pesan sudah absen
      $response = ['warning'=>'Anda Sudah Melakukan Absen Pada Tanggal '.$tglabsenpesan.' Jam '.$jamsekarang];
      return response()->json($response,200);
    }
    else{
      //jika belum ada absen maka insert data absen siswa
      
      $absen = new Absen_finger_siswa();
      $absen->afsSklId = $skl;
      $absen->afsSsaUsername = $username;
      $absen->afsRblId = $rombel;
      $absen->afsAkId = $status;
      $absen->afsDatetime = $tgl;
      $absen->afsIn = $jamsekarang;
      $absen->afsHari = $namahari;
      $absen->afsJenis = 3;
      $absen->afsSemester = $semster;
      $absen->afsTahunAjaran = $tahunajaran;
      
        if($absen->save()){
          Cache::forget('absen_sekolah'.$idsiswa.$bulan);
          Cache::forget('total_absen_siswa_sekolah'.$username.$bulan);
          $response = ['success'=>'Berhasil Absen Pada '.$tglabsenpesan ];
          return response()->json($response,200);
        }
        else{
          $response = ['error'=>'Gagal Absen'];
          return response()->json($response,202);
        }
    }
   

  }

  function JsonLogAbsensiSekolah(){
   
    if(empty($_GET['bln'])){
      $data = [];
    }
    else{
      $bln = decrypt_url($_GET['bln']);
      //$idrombel = $this->getDataRombel()->master_rombel->rblId;
      $username = Username();
      $tahunajaran = $this->getTahunAjaran()->tajrKode;
      $semster = $this->getSemester();
      
      //cache data redis ---------------------------------------------------------------------------
		  if (Cache::has('absen_sekolah'.$username.$bln)){ $data= Cache::get('absen_sekolah'.$username.$bln); }
		  else{ 
        $data = Absen_finger_siswa::where('afsSsaUsername',$username)
        ->whereMonth('afsDatetime', $bln)
        ->where('afsTahunAjaran',$tahunajaran)
        ->where('afsSemester',$semster)
        ->with('user_siswa')
        ->get();
        Cache::put('absen_sekolah'.$username.$bln, $data, ChaceJam() );
      }
    }
    $dt= DataTables::of($data)
    ->addColumn('namasiswa',function ($data) { 
			return FullNamaSiswa();
    })
    ->addColumn('username',function ($data) { 
			return $data->afsSsaUsername;
    })
    ->addColumn('tanggal',function ($data) { 
			$tgl = date('d-m-Y', strtotime($data->afsDatetime));
			return $tgl;
		})
		->addColumn('hari',function ($data) { 
			return hariIndo($data->afsHari);
    })
    ->addColumn('status',function ($data) { 
			return getStatusAbsensi($data->afsAkId);
    })
    ->rawColumns(['status']);
    return $dt->make();
    
  }
  function JsonTotalAbsenSekolah(Request $request){
    if(empty($_GET['bln'])){
      $data = [];
      $dt= DataTables::of($data);
    }
    else{
      $bln = decrypt_url($_GET['bln']);
      //$idrombel = $this->getDataRombel()->master_rombel->rblId;
      $username = Username();
      $tahunajaran = $this->getTahunAjaran()->tajrKode;
      $semster = $this->getSemester();
      //cache data redis ---------------------------------------------------------------------------
      if (Cache::has('total_absen_siswa_sekolah'.$username.$bln)){ $data= Cache::get('total_absen_siswa_sekolah'.$username.$bln); }
      else{ 
        $data = Absen_finger_siswa::where('afsSsaUsername',$username)
        ->whereMonth('afsDatetime', $bln)
        ->where('afsTahunAjaran',$tahunajaran)
        ->where('afsSemester',$semster)
        ->with('master_rombel')
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
          Cache::put('total_absen_siswa_sekolah'.$username.$bln, $data, ChaceJam() );
      }
		
      $dt= DataTables::of($data)
      ->addColumn('no','')
      ->addColumn('rombel',function ($data) { 
        return $data->master_rombel->master_kelas->klsKode.' '.$data->master_rombel->rblNama;
      })
      ->addColumn('nama_siswa',function ($data) { 
        return FullNamaSiswa();
      })
      ->addColumn('total',function ($data) { 
        $total = ($data->HADIR + $data->KEGIATAN + $data->ULANGAN + $data->TERLAMBAT );
        return $total; 
      });
    }
		return $dt->make();

	}


	
}
