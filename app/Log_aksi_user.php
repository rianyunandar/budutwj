<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Log_aksi_user extends Model
{
    protected $table = 'log_aksi_user';
    protected $primaryKey = 'laId';
    public $incrementing = false;
    public $timestamps = false;

    public function user_siswa()
    {
       return $this->hasOne(User_siswa::class,'ssaId','laIdUser');
       
    }
    public function user_guru()
    {
      //return $this->hasOne(User_siswa::class,'ssaUsername','psSsaUsername');
      return $this->hasOne(User_guru::class,'ugrId','laIdUser'); 
    }
    public function user_admin()
    {
      //return $this->hasOne(User_siswa::class,'ssaUsername','psSsaUsername');
      return $this->hasOne(User_admin::class,'admId','laIdUser'); 
    }
    
}
