<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class El_tugas extends Model
{
    protected $table = 'el_tugas';
    protected $primaryKey = 'tugasId';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = []; //tambahkan baris ini

    // public function anggota_rombel()
    // {
    //   return $this->belongsTo(El_tugas_anggota_rombel::class,'tugasKode','tgsarTugasKode');  
    // }

    public function mapel()
    {
      return $this->belongsTo(Master_mapel::class,'tugasMapelKode','mapelKode');  
    }

    
    public function getTugasById($id){
      //Cache Redis --------------------------------------------------------------------
        if (Cache::has('bacatugas'.$id)){ 
          $data = Cache::get('bacatugas'.$id);
        }
        else{
          $data = El_tugas::find($id);
          Cache::put('bacatugas'.$id, $data, ChaceJam()); 
        }
      //Cache Redis --------------------------------------------------------------------
      return $data;
    }
    
   
}
