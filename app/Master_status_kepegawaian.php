<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Master_status_kepegawaian extends Model
{
  protected $table = 'master_status_kepegawaian';
  protected $primaryKey = 'mskpId';
  public $incrementing = false;
  public $timestamps = false;

  public function getStatusPegawai($id=null)
  {
    //Cache Redis --------------------------------------------------------------------
    if (Cache::has('master_status_kepegawaian')){ $data = Cache::get('master_status_kepegawaian'); }
    else{ 

      if(empty($id)){ $data = Master_status_kepegawaian::get(); }
      else{ $data = Master_status_kepegawaian::get(); }

      $chace = Cache::put('master_status_kepegawaian', $data, ChaceJam());
    }
    return $data;
    //Cache Redis --------------------------------------------------------------------
  }
}
