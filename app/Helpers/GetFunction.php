<?php 
/*----------------------------------
  mryes
  Way Jepara
  SMK Budi Utomo Way Jepara
  Kumpulan Function Dashbord Admin

-------------------------------------*/
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\User_siswa;
use App\User_guru;
use App\Master_sekolah;
use App\Master_rombel;
use App\Master_jurusan;
use App\Master_jabatan;
use App\Master_agama;
use App\Anggota_rombel;
use App\Profile_siswa;
use App\Transportasi;
use App\Master_jam_sekolah;
use App\Setting;
use App\Informasi;


/*Function Secara Global untuk admin guru siswa */ 
  function Setting(){
    if (Cache::has('setting')){ $data = Cache::get('setting'); }
    else{
      $data = Setting::first();
      Cache::put('setting', $data, ChaceJam()); 
    }
    return $data;
  }
  function getInformasiSekolah($id=null){
    if (Cache::has('userinformasi_sekolah'.$id)){ $data = Cache::get('userinformasi_sekolah'.$id); }
    else{
      if(!empty($id)){ //id sekolah
        $data = Informasi::whereIn('infoIdTujuan',[$id,0])
        ->orderBy('infoCreated','ASC')
        ->where('infoIsActive',1)
        ->limit(3)
        ->get();
      }
      else{
        $data = Informasi::where('infoIsActive',1)
        ->orderBy('infoCreated','ASC')
        ->limit(5)
        ->get();
      }
      Cache::put('userinformasi_sekolah'.$id, $data, ChaceJam()); 
    }
    if(empty($data)){
      $data2 = [];
    }
    else{
      $data2 = $data;
    }
    return $data2;
   }
/*Function Secara Global untuk admin guru siswa*/

function getIdSekolah()
{
  //get id sekolah pada user
  $IdSekolah = Auth::user()->admSklId;
  return $IdSekolah ;
}
function IsAcktive(){
  return 1;
}
/*Get Function Database Table*/
  function GetDataAgama(){
    //Cache Redis --------------------------------------------------------------------
    if (Cache::has('master_agama')){ $data = Cache::get('master_agama'); }
    else{ 
       $data = Master_agama::get();
       $chace = Cache::put('master_agama', $data, ChaceJam());
    }
    return $data;
    //Cache Redis --------------------------------------------------------------------
  }
  function GetDataRombel($IdSekolah=null){
    $dataagama = Master_agama::get();
    if(empty($IdSekolah)){ //jika sekolah kosong
      $data = Master_rombel::orderBy('rblKode','DESC')->get();
    }
    else{
      $data = Master_rombel::where('rblSklId',$IdSekolah)
      ->orderBy('rblKode','ASC')
      ->get();
    }
    
    return $data;
  }
  function GetSekolah(){
    $IdSekolah = getIdSekolah();
    if (Cache::has('getSekolah'.$IdSekolah)){ $data = Cache::get('getSekolah'.$IdSekolah); }
    else{
      if(empty($IdSekolah)){ //jika sekolah kosong
        $var = Master_sekolah::get();
      }
      else{
        $var = Master_sekolah::where('sklId',$IdSekolah)->get();
      }
      foreach ($var as $value) {
        $data[]= array(
          'id' => $value->sklId,
          'kode' => $value->sklKode,
          'npsn' => $value->sklNpsn,
          'nama'  => $value->sklNama,
        );
      }
      Cache::put('getSekolah'.$IdSekolah, $data, ChaceJam()); 
    }
    return $data;
  }
  function GetDataTransport(){
    //Cache Redis --------------------------------------------------------------------
    if (Cache::has('transpot')){ $data = Cache::get('transpot'); }
    else{ 
       $data = Transportasi::get();
       $chace = Cache::put('transpot', $data, ChaceJam());
    }
    return $data;
    //Cache Redis --------------------------------------------------------------------
  }

/* /Get Function Database Table*/
/*Get Function Profile*/
  function FullNama(){
    $fullnama = strtoupper(Auth::user()->admFirstName.' '.Auth::user()->admLastName);
    return $fullnama;
  }
  function NamaDepan(){
    $fullnama = strtoupper(Auth::user()->admFirstName);
    return $fullnama;
  }
  function NamaBelakang(){
    $fullnama = strtoupper(Auth::user()->admLastName);
    return $fullnama;
  }
/* hak aksi admin ---------------------------------------------*/
  function AksiInsert(){
    //cek apakah di beri izin untuk inser data
    $data = Auth::user()->admInsert;
    if($data == 1){ $aksi = true; }else{ $aksi = false; }
    return $aksi;
  }
  function AksiUpdate(){
    //cek apakah di beri izin untuk Update Data
    $data = Auth::user()->admUpdate;
    if($data == 1){ $aksi = true; }else{ $aksi = false; }
    return $aksi;
    
  }
  function AksiDelete(){
    //cek apakah di beri izin untuk Delete Data
    $data = Auth::user()->admDelete;
    if($data == 1){ $aksi = true; }else{ $aksi = false; }
    return $aksi;
  }
  function HakAkses(){
    //cek hak askes admin apakah superadmin
    $data = Auth::user()->admRole;
    if($data == "SUPERADMIN"){ $aksi = true; }else{ $aksi = false; }
    return $aksi;
  }
  function KodeHakAkses(){
    //Kode hak akses untuk membatasi hak yg bukan SuperAdmin Full
    $data = Auth::user()->admKode;
    if($data == "SUPERADMIN"){ $aksi = true; }else{ $aksi = false; }
    return $aksi;
  }
/* end hak aksi admin ---------------------------------------------*/

  //get foto pada guru dan admin
  function GetFotoProfile($id=null){

    if(!empty(Auth::user()->admFotoProfile)){
      $img = asset('storage/images/guru/original/'.Auth::user()->admFotoProfile);
    }
    elseif(!empty($id)){

      $img3 = asset('storage/images/guru/original/'.$id);
      //jika foto tidaka ada atau kosong makan retrun defaul img
      if(!empty($img3)){
        $img2 = asset('image/avatar3.png');
      }
      else{
        $img2 = asset('storage/images/guru/original/'.$id);
      }
      $img=$img2;
    
    }
    else{
      $img = asset('image/avatar3.png');
    } 
    return $img;
            
  }
  function GetFotoMenu(){

    if(!empty(Auth::user()->admFotoProfile)){
      $img = asset('storage/images/guru/100/'.Auth::user()->admFotoProfile);
    }
    else{
      $img = asset('image/avatar3.png');
    } 
    return $img;
            
  }
  //menampilkan foto siswa tumblis pada data siswa
  function FotoSiswaSmall($id=null){
    if(!empty($id)){
      $img = asset('storage/images/siswa/100/'.$id);
    }
    else{
      $img = asset('image/noimage.png');
    } 
    return '<img width="80" height="80" src="'.$img.'?date='.time().'" class="img-fluid rounded-circle">';
  }

  function GetHakAkses(){
    $data = Master_jabatan::where('mjbKode',Auth::user()->admRole)->first();
    return $data->mjbNama;
  }
  
  function HakAksesFilterMenu()
  {
    if(Auth::user()->admRole != "SUPERADMIN"){
      return false;
    }
    else{
      return true;
    }
    
  }
  function HakAksesFilterMenuSuper(){
    if(Auth::user()->admKode != "SUPERADMIN"){
      return false;
    }
    else{
      return true;
    }
  }


/*Get Function Dashbor*/
  function GetJumlahGuruPerSekolah(){
    $IdSekolah = getIdSekolah();
    if (Cache::has('jumGuruPerSekolah'.$IdSekolah)){ $data = Cache::get('jumGuruPerSekolah'.$IdSekolah); }
    else{
      if(empty($IdSekolah)){ //jika sekolah kosong
        $sekolah = Master_sekolah::get();
      }
      else{
        $sekolah = Master_sekolah::where('sklId',$IdSekolah)->get();
      }
      foreach ($sekolah as $value) {
        $siswa = User_guru::where('ugrSklId',$value->sklId)->count();
        $data[]= array(
          'sekolah' => $value->sklKode,
          'jumlah'  => $siswa,
        );
      }
      Cache::put('jumGuruPerSekolah'.$IdSekolah, $data, ChaceJam()); 
    }
    return $data;
  }
  function GetJumlahSiswaPerSekolah(){
    $IdSekolah = getIdSekolah();
    if (Cache::has('jumSiswaPerSekolah'.$IdSekolah)){ $data = Cache::get('jumSiswaPerSekolah'.$IdSekolah); }
    else{
      if(empty($IdSekolah)){ //jika sekolah kosong
        $sekolah = Master_sekolah::get();
      }
      else{
        $sekolah = Master_sekolah::where('sklId',$IdSekolah)->get();
      }
      foreach ($sekolah as $value) {
        $siswa = User_siswa::where('ssaSklId',$value->sklId)
        ->where('ssaIsActive',IsAcktive())
        ->count();
        $data[]= array(
          'sekolah' => $value->sklKode,
          'jumlah'  => $siswa,
        );
      }
      Cache::put('jumSiswaPerSekolah'.$IdSekolah, $data, ChaceJam()); 
    }
    return $data;
  }
  function GetJumlahRombelPerSekolah(){
    $IdSekolah = getIdSekolah();
    if (Cache::has('jumlahRombel'.$IdSekolah)){ $data = Cache::get('jumlahRombel'.$IdSekolah); }
    else{
      
      if(empty($IdSekolah)){ //jika sekolah kosong
        $sekolah = Master_sekolah::get();
      }
      else{
        $sekolah = Master_sekolah::where('sklId',$IdSekolah)->get();
      }
      foreach ($sekolah as $value) {
        $rombel = Master_rombel::where('rblSklId',$value->sklId)->count();
        $data[]= array(
          'sekolah' => $value->sklKode,
          'jumlah'  => $rombel,
        );
      }
      Cache::put('jumlahRombel'.$IdSekolah, $data, ChaceJam()); 
    }
    return $data;

  }
  function GetJumlahSiswaPerJurusan(){
    $IdSekolah = getIdSekolah();
    if (Cache::has('jumSiswaPerJurusan'.$IdSekolah)){ $data = Cache::get('jumSiswaPerJurusan'.$IdSekolah); }
    else{
      if(empty($IdSekolah)){ //jika sekolah kosong
        $var = Master_jurusan::get();
      }
      else{
        $var = Master_jurusan::where('jrsSklId',$IdSekolah)->get();
      }
      foreach ($var as $value) {
        $count = User_siswa::where('ssaJrsId',$value->jrsId)
        ->where('ssaIsActive',IsAcktive())
        ->count();
        $data[]= array(
          'idskl' => $value->jrsSklId,
          'jurusan' => $value->jrsNama,
          'jumlah'  => $count,
        );
      }
      Cache::put('jumSiswaPerJurusan'.$IdSekolah, $data, ChaceJam()); 
    }
    return $data;
  }
  function GetRincianSiswaRombel($IdSekolah=null){
   
    if (Cache::has('RincianSiswaRombel'.$IdSekolah)){ $dataArray = Cache::get('RincianSiswaRombel'.$IdSekolah); }
    else{
      if(empty($IdSekolah)){ //jika sekolah kosong
        $data = Master_rombel::get();
      }
      else{
        $data = Master_rombel::where('rblSklId',$IdSekolah)->get();
      }
      
      foreach($data as $keys => $value){
        $jsk=$jsp=$totaljsk = 0;
        $idrombel = $value['rblId'];

        //hitung jumlah laki2 dan perempuan ----------------------------------------
        $siswaRombel= DB::table('user_siswa')
            ->leftjoin('profile_siswa', 'ssaUsername', '=', 'psSsaUsername')
            ->leftjoin('anggota_rombel', 'ssaUsername', '=', 'arbSsaId')
            ->where('arbRblId',$idrombel)
            ->where('ssaIsActive',IsAcktive())
            // ->orderByAsc('rblKode')
            ->select('ssaUsername','psJsk')
            ->get();
        //setelah diload semua data siswa per rombel loopping untuk filter total jsk
        foreach($siswaRombel as $key => $value2){
          if($value2->psJsk == 'L'){ $jsk++; }else{ $jsp++; }
        }
        //hitung jumlah laki2 dan perempuan ----------------------------------------
        
        $dataArray[]=array(
          //'idjurusan' => $value['master_jurusan']['jrsId'],
          'jurusan' => $value['master_jurusan']['jrsSlag'],
          //'idkelas' => $value['master_kelas']['klsId'],
          'kelas' => $value['master_kelas']['klsKode'],
          'idrombel' => $value['rblId'],
          'rombel' => $value['master_kelas']['klsKode'].' '.$value['rblNama'],
          'jum_perrombel' =>$siswaRombel->count(),
          'jum_l'=>$jsk,
          'jum_p'=>$jsp,
        );
      }
      Cache::put('RincianSiswaRombel'.$IdSekolah, $dataArray, ChaceJam()); 
    }

    return $dataArray;

  }
  function loopCekAnggotaRombel($idskl)
  {
    $no=0;
    $datasiswa = User_siswa::where('ssaSklId',$idskl)->where('ssaIsActive',IsAcktive())->get();
    foreach($datasiswa as $val1){
      $cekrombel = Anggota_rombel::where('arbSsaId',$val1['ssaUsername'])->count();
      if($cekrombel > 0){ }
      else{
        $no++;
      }
    }
    return $no;
  }
  function getSiswaNoRombel()
  {
    $IdSekolah = getIdSekolah();
    if (Cache::has('getSiswaNoRombel'.$IdSekolah)){ $dataArray = Cache::get('getSiswaNoRombel'.$IdSekolah); }
    else{
      
      
      if(empty($IdSekolah)){ //jika sekolah kosong
        $sekolah = Master_sekolah::get();
      }
      else{
        $sekolah = Master_sekolah::where('sklId',$IdSekolah)->get();
      }

      //loping data berdasrkan sekolah
      foreach($sekolah as $val){
        $count = loopCekAnggotaRombel($val['sklId']);
        $dataArray[] = array(
          'kode_skl' =>$val->sklKode,
          'jum_skl' =>$count,
        );
      }
      Cache::put('getSiswaNoRombel'.$IdSekolah, $dataArray, ChaceJam()); 
    }
    return $dataArray;
  }
  //agama -------------------------------------------------------------
  function GetRincianSiswaAgama($idrombel=null,$agama=null){
    $siswaRombel = User_siswa::where('arbRblId',$idrombel)
    ->where('ssaAgama',$agama)
    ->where('ssaIsActive',IsAcktive())
    ->leftjoin('anggota_rombel', 'ssaUsername', '=', 'arbSsaId');
    return $siswaRombel->count();
  }
  function GetTotalAgama($IdSekolah){
    if (Cache::has('TotalAgama'.$IdSekolah)){ $dataArray = Cache::get('TotalAgama'.$IdSekolah); }
    else{
      $agama = Master_agama::get();
      foreach($agama as $value){
        $data = User_siswa::where('ssaSklId',$IdSekolah)
        ->where('ssaAgama',$value['agmKode'])
        ->where('ssaIsActive',IsAcktive())
        ->count();
        $dataArray[]=array(
          'id' =>$value['agmId'],
          'agama' =>$value['agmNama'],
          'jumlah' =>$data,
        );
      }
      Cache::put('TotalAgama'.$IdSekolah, $dataArray, ChaceJam()); 
    }
    return $dataArray;
  }
  function loopAgama($idrombel=null,$idAgama=null){
    //lakukan cek siswa transpord berdasarkan id 
    $dataTranspot = Transportasi::get();
    $dataTranspot = Transportasi::get();
    foreach($dataTranspot as $value2){
      $idTransport = $value2['trsId'];
      $data = User_siswa::where('ssaIsActive',IsAcktive())
      ->where('ssaSklId',$idTransport)
      ->where('arbRblId',$idrombel)
      ->where('psTransport',$idTransport)
      ->leftjoin('anggota_rombel', 'ssaUsername', '=', 'arbSsaId')
      ->leftjoin('profile_siswa', 'ssaUsername', '=', 'psSsaUsername')
      ->count();
      //tampung data ke dalam array 
      $array[] = array(
        'id_transport' =>$value2['trsId'],
        'kode_transport'=>$value2['trsKode'],
        'kode_transport'=>$value2['trsNama'],
        'jum_transport' =>$data
      );
    } //end foreach 2
    return $array ;
  }
  function getAgamaSiswas($IdSekolah = null,$id){
    if (Cache::has('getAgamaSiswas'.$id.$IdSekolah)){ $dataArray = Cache::get('getAgamaSiswas'.$id.$IdSekolah); }
    else{
      $data = User_siswa::where('ssaSklId',$IdSekolah)
      ->where('ssaAgama',$id);
      if(!empty($data->count())){
        foreach($data->get() as $val){
          $dataArray[] =array(
            'username' =>  $val['ssaUsername'],
            'fullname' => $val['ssaFirstName'].' '.$val['ssaLastName'],
            'agama'  => $val['ssaAgama'],

          ); 
        }
      }
      else{
        $dataArray=array();
      }
      Cache::put('getAgamaSiswas'.$id.$IdSekolah, $dataArray, ChaceJam()); 
    }

    return $dataArray;
  }
  //jenis kelamin -------------------------------------------------------------
  function GetAllJenisKelamin($jsk=null){
    $IdSekolah = getIdSekolah();
    if (Cache::has('jsk'.$jsk.$IdSekolah)){ $dataArray = Cache::get('jsk'.$jsk.$IdSekolah); }
    else{
      if(empty($IdSekolah)){ //jika sekolah kosong
        $datasiswa = User_siswa::get();
        $data = User_siswa::where('psJsk',$jsk)
        ->where('ssaIsActive',IsAcktive())
        ->leftjoin('profile_siswa', 'ssaUsername', '=', 'psSsaUsername');
      }
      else{
        $datasiswa = User_siswa::where('ssaSklId',$IdSekolah);
        $data = User_siswa::where('ssaSklId',$IdSekolah)
        ->where('ssaIsActive',IsAcktive())
        ->where('psJsk',$jsk)
        ->leftjoin('profile_siswa', 'ssaUsername', '=', 'psSsaUsername');
      }
      $jml_jsk = $data->count();
      $totalSiswa = $datasiswa->count();
      if(empty($totalSiswa)){
        $presen =0;
      }else{
        $presen = ($jml_jsk/$totalSiswa);
      }

      $dataArray=array(
        'jml_jsk'=>$jml_jsk,
        'jml_presen' => round($presen,3)
      );
      Cache::put('jsk'.$jsk.$IdSekolah, $dataArray, ChaceJam()); 
    }

    return $dataArray;

  }
  //trasport -------------------------------------------------------------
  function getSiswaTransportTotal($IdSekolah = null)
  {
    if (Cache::has('TransportTotal'.$IdSekolah)){ $dataArray = Cache::get('TransportTotal'.$IdSekolah); }
    else{
      $dataTranspot = Transportasi::get();

      foreach($dataTranspot as $key => $value){
        if(empty($IdSekolah)){

          $data = User_siswa::where('psTransport',$value['trsId'])
          ->where('ssaIsActive',IsAcktive())
          ->leftjoin('profile_siswa', 'ssaUsername', '=', 'psSsaUsername')
          ->count();
        } 
        else{
          $data = User_siswa::where('ssaSklId',$IdSekolah)
          ->where('ssaIsActive',IsAcktive())
          ->where('psTransport',$value['trsId'])
          ->leftjoin('profile_siswa', 'ssaUsername', '=', 'psSsaUsername')
          ->count();
        }
        
        $dataArray[]=array(
          'idtransport' =>$value['trsId'],
          'transport' =>$value['trsKode'],
          'trsNama' =>$value['trsNama'],
          'jumlah'   => $data,
        );

      }
      Cache::put('TransportTotal'.$IdSekolah, $dataArray, ChaceJam()); 
    }
    return $dataArray;

  }
  function loopTranspot($idrombel=null,$idTransport=null){
    //lakukan cek siswa transpord berdasarkan id 
    $dataTranspot = Transportasi::get();
    //$dataTranspot = Transportasi::get();
    foreach($dataTranspot as $value2){
      $idTransport = $value2['trsId'];
      $data = User_siswa::where('ssaIsActive',IsAcktive())
      ->where('ssaIsActive',IsAcktive())
      ->where('ssaSklId',$idTransport)
      ->where('arbRblId',$idrombel)
      ->where('psTransport',$idTransport)
      ->leftjoin('anggota_rombel', 'ssaUsername', '=', 'arbSsaId')
      ->leftjoin('profile_siswa', 'ssaUsername', '=', 'psSsaUsername')
      ->count();
      //tampung data ke dalam array 
      $array[] = array(
        'id_transport' =>$value2['trsId'],
        'kode_transport'=>$value2['trsKode'],
        'kode_transport'=>$value2['trsNama'],
        'jum_transport' =>$data
      );
    } //end foreach 2
    return $array ;
  }
  function getTransportRombel($IdSekolah = null){    
    if (Cache::has('TransportRombel'.$IdSekolah)){ $dataArray = Cache::get('TransportRombel'.$IdSekolah); }
    else{
      $dataRombel = GetDataRombel($IdSekolah);
      //looping rombel
      foreach($dataRombel as $key1 => $value1){
        $idrombel = $value1['rblId'];
        $data = loopTranspot($idrombel,$IdSekolah);
        //tampung data ke dalam array 
        $dataArray[] = array(
          'kelas' => $value1->master_kelas->klsKode,
          'rombel' => $value1['rblNama'],
          'nama_rombel' =>$value1->master_kelas->klsKode.' '.$value1['rblNama'],
          'data_transport' => $data,
        );
      
      }//end foreach 1
      Cache::put('TransportRombel'.$IdSekolah, $dataArray, ChaceJam()); 
    }

    return $dataArray;
  }
  function getTransportSiswas($IdSekolah = null,$id){
    $idd = decrypt_url($id);
    if (Cache::has('TransportSiswas'.$id.$IdSekolah)){ $dataArray = Cache::get('TransportSiswas'.$id.$IdSekolah); }
    else{
      $data = User_siswa::where('ssaSklId',$IdSekolah)
      ->where('ssaIsActive',IsAcktive())
      ->where('psTransport',$idd)
      ->orderBy('ssaFirstName')
      ->leftjoin('profile_siswa', 'ssaUsername', '=', 'psSsaUsername');
      
      if(!empty($data->count())){
        foreach($data->get() as $val){
          $dataArray[] =array(
            'username' =>  $val['ssaUsername'],
            'fullname' => $val['ssaFirstName'].' '.$val['ssaLastName'],
            'rombel'  => $val->anggota_rombel->master_rombel->master_kelas->klsNama.' '.$val->anggota_rombel->master_rombel->rblNama,

          ); 
        }
      }
      else{
        $dataArray=array();
      }
      Cache::put('TransportSiswas'.$id.$IdSekolah, $dataArray, ChaceJam()); 
    }

    return $dataArray;
  }
  //cek kondisi siswa ----------------------------------------------------------
  function GetCekDataSiswa(){
    $IdSekolah = getIdSekolah();
    if(empty($IdSekolah)){ 
      $skl = Master_sekolah::get();
      foreach($skl as $val){
        $siswaRombel = User_siswa::where('ssaSklId',$val['sklId'])->whereNull('ssaRblId');
        $siswaJurusan = User_siswa::where('ssaSklId',$val['sklId'])->whereNull('ssaJrsId');
        $siswaAgama = User_siswa::where('ssaSklId',$val['sklId'])->whereNull('ssaAgama');

        $dataArray[]=array(
          'id_sekolah' => $val['sklId'],
          'kode_sekolah' => $val['sklNama'],
          'jum_rombel' => $siswaRombel->count(),
          'jum_jurusan' => $siswaJurusan->count(),
          'jum_agama' => $siswaAgama->count(),
        );        
      }
      
    }else{
      $skl = Master_sekolah::where('sklId',$IdSekolah)->get();
      foreach($skl as $val){
        $siswaRombel = User_siswa::whereNull('ssaRblId')->where('ssaSklId',$val['sklId']);
        $siswaJurusan = User_siswa::whereNull('ssaJrsId')->where('ssaSklId',$val['sklId']);
        $siswaAgama = User_siswa::whereNull('ssaAgama')->where('ssaSklId',$val['sklId']);

        $dataArray[]=array(
          'id_sekolah' => $val['sklId'],
          'kode_sekolah' => $val['sklNama'],
          'jum_rombel' => $siswaRombel->count(),
          'jum_jurusan' => $siswaJurusan->count(),
          'jum_agama' => $siswaAgama->count(),
        );        
      }
    }

    return $dataArray;

  }

  //asbensi ----------------------------------------------
  function getJamMasukSekolah(){
    if (Cache::has('getJamMasukSekolah')){ $jamSekolah= Cache::get('getJamMasukSekolah'); }
    else{
      $jamSekolah = Master_jam_sekolah::first();
      //82800 detik  = 23 jam 
      Cache::put('getJamMasukSekolah', $jamSekolah, 82800); 
    }
    return $jamSekolah->jamsklMasuk;
  }
  function getJamTerlamabtSekolah(){
    if (Cache::has('getJamTerlamabtSekolah')){ $jamSekolah= Cache::get('getJamTerlamabtSekolah'); }
    else{
      $jamSekolah = Master_jam_sekolah::first();
      Cache::put('getJamTerlamabtSekolah', $jamSekolah, 82800); 
    }
    return $jamSekolah->jamBatasTerlambar;
  }
  function getStatusAbsensi($data){
      if($data == 'H'){ $status = '<span class="badge badge-success">HADIR</span>'; }
			if($data == 'I'){ $status = '<span class="badge badge-primary">IZIN</span>'; }
			if($data == 'A'){ $status = '<span class="badge badge-danger">ALPHA</span>'; }
			if($data == 'S'){ $status = '<span class="badge badge-info">SAKIT</span>'; }
			if($data == 'B'){ $status = '<span class="badge badge-danger">BOLOS</span>'; }
			if($data == 'T'){ $status = '<span class="badge badge-warning">TERLAMBAT</span>'; }
			if($data == 'U'){ $status = '<span class="badge badge-secondary">UJIAN</span>'; }
			if($data == 'L'){ $status = '<span class="badge badge-secondary">UJIAN</span>'; }
      if($data == 'K'){ $status = '<span class="badge badge-secondary">UJIAN</span>'; }
      return $status;
  }
  //cek kehadiran hanya jam masuk saja ssat siswa melakukan absensi sekolah
  function CekKehadiranJamMasuk($jam){
    $jamSekolah = Master_jam_sekolah::first();
    $jamMasuk = $jamSekolah->jamsklMasuk;
    $jamTerlamabr = $jamSekolah->jamBatasTerlambar;

    if($jam >= $jamMasuk AND $jam <=$jamTerlamabr ){
      $satus = 'H';
    }
    else{
      $status = 'T';
    }
    return $status;

  }


  /* /Get Function Dashbor*/