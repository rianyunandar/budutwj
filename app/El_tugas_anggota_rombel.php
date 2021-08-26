<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class El_tugas_anggota_rombel extends Model
{
    protected $table = 'el_tugas_anggota_rombel';
    protected $primaryKey = 'tgsarId';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = []; //tambahkan baris ini

    public function el_tugas()
    {
      return $this->belongsTo(El_tugas::class,'tgsarTugasKode','tugasKode');
    }
   

    public function master_rombel()
    {
      return $this->belongsTo(Master_rombel::class,'tgsarRblKode','rblKode')->with('master_kelas');  
    }
    public function getAnggota_rombel($id)
    {
      $data = El_tugas_anggota_rombel::where('tgsarTugasKode', $id)->get(); 
		  return $data;
    }
    // public function user_guru()
    // {
    //   return $this->belongsTo(User_guru::class,'tgsarUgrId','ugrId');  
    // }
    
    // public function master_kelas()
    // {
    //     return $this->hasManyThrough(
    //         'App\Master_kelas',
    //         'App\Master_rombel',
    //         'rblKlsId', // Foreign key on idkelas table Master_rombel
    //         'klsId' //id kelas
    //     );
    // }
    
    public function getTugasBy($koderombel){
        
      //Cache Redis --------------------------------------------------------------------
      //menampilkan list mapel tugas pada siswa
      if (Cache::has('list_tugas_siswa'.$koderombel)){ 
        $data = Cache::get('list_tugas_siswa'.$koderombel);
      }
      else{
        $data = El_tugas_anggota_rombel::where('tgsarRblKode',$koderombel)
        ->where('tgsarTampilSiswa',1)
        ->with('el_tugas')
        
        ->get();
        Cache::put('list_tugas_siswa'.$koderombel, $data, ChaceJam()); 
      }
      //Cache Redis --------------------------------------------------------------------
      return $data;
    }
   
}
