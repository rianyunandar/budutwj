<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Master_agama extends Model
{
    protected $table = 'master_agama';
    protected $primaryKey = 'agmId';
    public $incrementing = false;
    public $timestamps = false;

    function getAgama($id=null)
    {
     //Cache Redis --------------------------------------------------------------------
       if (Cache::has('master_agama')){ $data = Cache::get('master_agama'); }
        else{ 
           $data = Master_agama::get();
           $chace = Cache::put('master_agama', $data, ChaceJam());
        }
        return $data;
      //Cache Redis --------------------------------------------------------------------
    }
    
}
