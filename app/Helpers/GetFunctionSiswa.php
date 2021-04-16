<?php 
/*
  mryes
  Way Jepara
  SMK Budi Utomo Way Jepara
  Kumpulan Function Siswa
*/


use App\User_siswa;
use App\Master_sekolah;
use App\Master_jabatan;
use App\Master_rombel;
use App\Master_kelas;

//id pada siswa ------------------------------------------------
  function getIdSiswa()
  {
    $id = Auth::user()->ssaId;

    return $id;
  }
  function getIdSkl()
  {
    $idskl = Auth::user()->ssaSklId; 
    return $idskl;
  }
 
  function getIdJrs()
  {
    $id = Auth::user()->ssaJrsId; 
    return $id;
  }
  function getIdRombel()
  {
    $data = Auth::user()->ssaRblId; 
    return $data;
  }
//id pada siswa ------------------------------------------------ 

// Elearning materi --------------------------------------------------------------------
  function getKodeKelas()
  {
    //Cache Redis --------------------------------------------------------------------
    if (Cache::has('getKodeKelas'.getIdRombel())){ 
      $data = Cache::get('getKodeKelas'.getIdRombel());
    }
    else{ 
      $data = Master_rombel::with('master_kelas')->find(getIdRombel());
      Cache::put('getKodeKelas'.getIdRombel(), $data, ChaceJam()); 
    }
    //Cache Redis --------------------------------------------------------------------
    return $data->master_kelas->klsKode;
  }
  function getIdKelas()
  {
    //Cache Redis --------------------------------------------------------------------
    if (Cache::has('getKodeKelas'.getIdRombel())){ 
      $data = Cache::get('getKodeKelas'.getIdRombel());
    }
    else{ 
      $data = Master_rombel::with('master_kelas')->find(getIdRombel());
      Cache::put('getKodeKelas'.getIdRombel(), $data, ChaceJam()); 
    }
    //Cache Redis --------------------------------------------------------------------
    return $data->master_kelas->klsId;
  }
  function getKodeRombel()
  {
    //Cache Redis --------------------------------------------------------------------
    if (Cache::has('getKodeRombel'.getIdRombel())){ 
      $data = Cache::get('getKodeRombel'.getIdRombel()); }
    else{ 
      $data = Master_rombel::find(getIdRombel());
      Cache::put('getKodeRombel'.getIdRombel(), $data, ChaceJam());
    }
  //Cache Redis --------------------------------------------------------------------
    return $data->rblKode;
  }
// Elearning materi --------------------------------------------------------------------
function DateTimeNow(){
  return date('Y-m-d H:i:s');
}
function Username(){
  $data = Auth::user()->ssaUsername;
  return $data;
}
function FullNamaSiswa(){
  $fullnama = strtoupper(Auth::user()->ssaFirstName.' '.Auth::user()->ssaLastName);
  return $fullnama;
}
function NamaDepanSiswa(){
  $fullnama = strtoupper(Auth::user()->ssaFirstName);
  return $fullnama;
}
function NamaBelakangSiswa(){
  $fullnama = strtoupper(Auth::user()->ssaLastName);
  return $fullnama;
}
function StatusSiswa($select=null){
  //jika select tidak kosong berati untuk select status siswanya
  $data = Auth::user()->ssaIsActive;
  if(!empty($select)){
    $data2 = $data;
  }
  else{
    if($data==1){
      $data2 = 'AKTIF';
    }
    else{
      $data2= 'TIDAK AKTIF';
    }
  }
  return $data2 ; 
}

//get foto profile siswa --------------------------------------
function GetFotoProfileSiswa(){

  if(!empty(Auth::user()->ssaFotoProfile)){
    $img = asset('storage/images/siswa/original/'.Auth::user()->ssaFotoProfile);
  }
  else{
    $img = asset('image/noimage.png');
  } 
  return $img;
          
}

?>
