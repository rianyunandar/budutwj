<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Master_jabatan extends Model
{
    protected $table = 'master_jabatan';
    protected $primaryKey = 'mjbId';
    public $incrementing = false;
    public $timestamps = false;

    public function getJabatan($id)
    {
      //Cache Redis --------------------------------------------------------------------
      if (Cache::has('master_jabatan'.$id)){ 
        $jabatan = Cache::get('master_jabatan'.$id); 
      }
      else{ 
        //filter jabatan guru pada select
        if(empty($id)){ $jabatan = Master_jabatan::where('mjbIsActive',1)->get();}
        else{ $jabatan = Master_jabatan::where('mjbIsActive',1)->where('mjbFilterTampil',2)->get(); }

        $chace = Cache::put('master_jabatan'.$id, $jabatan, ChaceJam());
      }
      return $jabatan;
      //Cache Redis --------------------------------------------------------------------
    }

   
}
