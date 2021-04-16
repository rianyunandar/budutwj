<?php 
/*
  mryes
  Way Jepara
  SMK Budi Utomo Way Jepara
  Kumpulan Function Guru
*/
use App\User_siswa;
use App\Master_sekolah;
use App\Master_jabatan;  

function FullNamaGuru(){
  $fullnama = strtoupper(Auth::user()->ugrFirstName.' '.Auth::user()->ugrLastName);
  return $fullnama;
}
function NamaDepanGuru(){
  $fullnama = strtoupper(Auth::user()->ugrFirstName);
  return $fullnama;
}
function NamaBelakangGuru(){
  $fullnama = strtoupper(Auth::user()->ugrLastName);
  return $fullnama;
}
function GetIdGuru(){
  $id = Auth::user()->ugrId; 
  return encrypt_url($id);

}


function StatusGuru($select=null){
  //jika select tidak kosong berati untuk select status siswanya
  $data = Auth::user()->ugrIsActive;
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

//get foto profile guru --------------------------------------
function GetFotoProfileGuru(){

  if(!empty(Auth::user()->ugrFotoProfile)){
    $img = asset('storage/images/guru/original/'.Auth::user()->ugrFotoProfile);
  }
  else{
    $img = asset('image/noimage.png');
  } 
  return $img;
          
}
//get foto profile guru ------------------------------------
function GetFotoProfileSiswaGuru($link=null){

  if(!empty($link)){
    $img = asset('storage/images/siswa/original/'.$link);
  }
  else{
    $img = asset('image/noimage.png');
  } 
  return $img;
          
}

function GuruHakAkses(){
  if(empty(Auth::user()->ugrRole)){
    $data=[];
  }
  else{
    $role = unserialize(Auth::user()->ugrRole) ;
    foreach ($role  as $item){
      $data[] = $item.',';
    }
  }
  
  return $data;
}

// function GetHakAksesGuru(){
//   //$role = Auth::user()->ugrRole ;
//   // foreach ( as $item){
//   //   $data = $item;
//   // }
//   //return $role;

// }
