<?php

function hariIndo($hariInggris) {
  switch ($hariInggris) {
    case 'Sunday':
      return 'Minggu';
    case 'Monday':
      return 'Senin';
    case 'Tuesday':
      return 'Selasa';
    case 'Wednesday':
      return 'Rabu';
    case 'Thursday':
      return 'Kamis';
    case 'Friday':
      return 'Jumat';
    case 'Saturday':
      return 'Sabtu';
    default:
      return 'hari tidak valid';
  }
}
 function bulanIndo($bulan) {
  switch ($bulan) {
    case '1':
      return 'Januari';
    case '2':
      return 'Februari';
    case '3':
      return 'Maret';
    case '4':
      return 'April';
    case '5':
      return 'Mei';
    case '6':
      return 'Juni';
    case '7':
      return 'Juli';
    case '8':
      return 'Agustus';
    case '9':
      return 'September';
    case '10':
      return 'Oktober';
    case '11':
      return 'November';
    case '12':
      return 'Desember';
    default:
      return 'Bulan tidak valid';
  }
}

function getBulan()
{
   for ($i=1; $i <=12 ; $i++) { 
     $data[]=$i;
   }
   return $data;
}
function getTanggal()
 {
   for ($i=1; $i <=31 ; $i++) { 
     $data[]=$i;
   }
   return $data;
 } 
 function getTahunSekarang(){
  $data=date('Y');
  return $data;
 }

 function formatTanggalIndo($date){
  // ubah string menjadi format tanggal
  return date('d-m-Y', strtotime($date));
 }
 function formatTanggalJamIndo($date){
  return date('d-m-Y H:i:s', strtotime($date));
 }
