<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile_guru extends Model
{
    protected $table = 'profile_guru';
    protected $primaryKey = 'prgId';
    public $incrementing = false;
    public $timestamps = false;

    public function user_guru()
    {
      //return $this->hasOne(User_siswa::class,'ssaUsername','psSsaUsername');
      return $this->hasOne(User_guru::class,'ugrUsername','prgUgrUsername'); 
    }


}
