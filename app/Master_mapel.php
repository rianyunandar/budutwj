<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Master_mapel extends Model
{
    protected $table = 'master_mapel';
    protected $primaryKey = 'mapelId';
    public $incrementing = false;
    public $timestamps = false;

    public function master_kelas()
    {
        return $this->belongsTo(Master_kelas::class,'mapelKlsId','klsId');  
    }
    public function master_sekolah()
    {
        return $this->belongsTo(Master_sekolah::class,'mapelSklId','sklId');  
    }
    public function master_jurusan()
    {
        return $this->belongsTo(Master_jurusan::class,'mapelJrsId','jrsId');  
    }
    
    
    public function getMapelByKode($id=null){
      //Cache Redis --------------------------------------------------------------------
      if (Cache::has('master_mapel'.$id)){ $data = Cache::get('master_mapel'.$id); }
      else{ 
        if(empty($id)){
            $data = Master_mapel::get();
        }
        else{
            $data = Master_mapel::where('mapelKode', $id)->first();
        }
        Cache::put('master_mapel'.$id, $data, ChaceJam());
      }
      return $data;
      //Cache Redis --------------------------------------------------------------------
    }

    
}
