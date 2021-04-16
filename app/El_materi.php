<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class El_materi extends Model
{
    protected $table = 'el_materi';
    protected $primaryKey = 'materiId';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = []; //tambahkan baris ini

    public function anggota_rombel()
    {
      return $this->belongsTo(El_materi_anggota_rombel::class,'materiKode','mtraMateriKode');  
    }

    public function getMateriById($id){
      //Cache Redis --------------------------------------------------------------------
      if (Cache::has('bacamateri'.$id)){ 
        $data = Cache::get('bacamateri'.$id);
      }
      else{
        $data = El_materi::find($id);
        Cache::put('bacamateri'.$id, $data, ChaceJam()); 
      }
      //Cache Redis --------------------------------------------------------------------
      return $data;
    }
    
   
}
