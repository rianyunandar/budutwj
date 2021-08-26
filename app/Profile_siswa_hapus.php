<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile_siswa_hapus extends Model
{
    protected $table = 'profile_siswa_hapus';
    protected $primaryKey = 'psId';
    public $incrementing = false;
    public $timestamps = false;

    public function user_siswa_hapus()
    {
       return $this->hasOne(User_siswa_hapus::class,'ssaUsername','psSsaUsername');
       
    }
    public function master_smp()
    {
      return $this->belongsTo(Master_smp::class,'psAsalSmp','smpId');
    }
    public function master_agama(){
        return $this->belongsTo(Master_agama::class,'psAgama','agmId');
    }
    public function master_status_keadaan()
    {
      return $this->belongsTo(Master_status_keadaan::class,'psStatusKeterangan','mstId');

    }
    public function transportasi()
    {
      return $this->belongsTo(Transportasi::class,'psTransport','trsId');

    }


}
