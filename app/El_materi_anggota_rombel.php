<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class El_materi_anggota_rombel extends Model
{
    protected $table = 'el_materi_anggota_rombel';
    protected $primaryKey = 'mtraId';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = []; //tambahkan baris ini

    public function el_materi()
    {
      return $this->belongsTo(El_materi::class,'mtraMateriKode','materiKode');
    }

    public function master_rombel()
    {
      return $this->belongsTo(Master_rombel::class,'mtraRblKode','rblKode')->with('master_kelas');  
    }
    public function getAnggota_rombel($id)
    {
      $data = El_materi_anggota_rombel::where('mtraMateriKode', $id)->get(); 
		  return $data;
    }
    
    // public function master_kelas()
    // {
    //     return $this->hasManyThrough(
    //         'App\Master_kelas',
    //         'App\Master_rombel',
    //         'rblKlsId', // Foreign key on idkelas table Master_rombel
    //         'klsId' //id kelas
    //     );
    // }
    public function getMateriBy($koderombel,$kodemapel,$idkelas){
        
      //Cache Redis --------------------------------------------------------------------
      //menampilkan list materi pada siswa
      if (Cache::has('list_materi_siswa'.$koderombel.$kodemapel.$idkelas)){ 
        $data = Cache::get('list_materi_siswa'.$koderombel.$kodemapel.$idkelas);
      }
      else{
        $data = El_materi_anggota_rombel::where('mtraMapelKode',$kodemapel)
        ->where('mtraRblKode',$koderombel)
        ->where('mtraKlsId',$idkelas)
        ->where('mtraTampilSiswa',1)
        ->with('el_materi')
        ->get();
        Cache::put('list_materi_siswa'.$koderombel.$kodemapel.$idkelas, $data, ChaceJam()); 
      }
      //Cache Redis --------------------------------------------------------------------
      return $data;
    }
   
}
