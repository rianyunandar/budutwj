<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Master_mapel_jadwal_rombel extends Model
{
    protected $table = 'master_mapel_jadwal_rombel';
    protected $primaryKey = 'mjrId';
    public $incrementing = false;
    public $timestamps = false;

    public function master_mapel_jadwal()
    {
      return $this->belongsTo(Master_mapel_jadwal::class,'mjrMapelJadwal','majdId')->with('user_guru');
    }
    // public function master_rombel()
    // {
    //   return $this->belongsTo(Master_rombel::class,'mjrRombelId','rblId');
    // }

    
    
}
