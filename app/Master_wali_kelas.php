<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Master_wali_kelas extends Model
{
    protected $table = 'master_wali_kelas';
    protected $primaryKey = 'wakasId';
    public $incrementing = false;
    public $timestamps = false;

    public function user_guru()
    {
        return $this->belongsTo(User_guru::class,'wakasUgrId','ugrId');  
    }
    public function master_jurusan()
    {
        return $this->belongsTo(Master_jurusan::class,'wakasJrsId','jrsId');  
    }
    public function master_rombel()
    {
        return $this->belongsTo(Master_rombel::class,'wakasRblId','rblId');  
    }
    public function master_sekolah()
    {
        return $this->belongsTo(Master_sekolah::class,'wakasSklId','sklId');  
    }
   
}
