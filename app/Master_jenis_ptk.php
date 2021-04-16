<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Master_jenis_ptk extends Model
{
    protected $table = 'master_jenis_ptk';
    protected $primaryKey = 'ptkId';
    public $incrementing = false;
    public $timestamps = false;

    public function getJenisPtk($id=null)
    {
        //Cache Redis --------------------------------------------------------------------
        if (Cache::has('master_jenis_ptk')){ $data = Cache::get('master_jenis_ptk'); }
        else{ 
          
          if(empty($id)){ $data = Master_jenis_ptk::get(); }
          else{ $data = Master_jenis_ptk::get(); }

          $chace = Cache::put('master_jenis_ptk', $data, ChaceJam());
        }
        return $data;
        //Cache Redis --------------------------------------------------------------------
    }

   
}
