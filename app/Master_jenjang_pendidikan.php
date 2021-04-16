<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Master_jenjang_pendidikan extends Model
{
  protected $table = 'master_jenjang_pendidikan';
  protected $primaryKey = 'mjpId';
  public $incrementing = false;
  public $timestamps = false;


  public function getJenjangPendidikan($id=null)
  {
    //Cache Redis --------------------------------------------------------------------
    if (Cache::has('master_jenjang_pendidikan'.$id)){ $data = Cache::get('master_jenjang_pendidikan'.$id); }
    else{ 
     if(empty($id)){ $data = Master_jenjang_pendidikan::get(); }
     else{ $data = Master_jenjang_pendidikan::where('sklId', $id)->get(); }
     $chace = Cache::put('master_jenjang_pendidikan'.$id, $data, ChaceJam());
    }
    return $data;
    //Cache Redis --------------------------------------------------------------------
 }

}
