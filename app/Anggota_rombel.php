<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Anggota_rombel extends Model
{
    protected $table = 'anggota_rombel';
    protected $primaryKey = 'arbId';
    public $incrementing = false;
    public $timestamps = false;

    public function master_sekolah()
    {
        return $this->belongsTo(Master_sekolah::class,'arbSklId','sklId');  
    }
    public function master_jurusan()
    {
        return $this->belongsTo(Master_jurusan::class,'arbJrsId','jrsId');  
    }
    public function master_rombel()
    {
        return $this->belongsTo(Master_rombel::class,'arbRblId','rblId');  
    }
    public function user_siswa()
    {
        return $this->belongsTo(User_siswa::class,'arbSsaId','ssaUsername');  
    }
    public function profile_siswa()
    { 
    return $this->belongsTo(Profile_siswa::class,'arbSsaId','psSsaUsername')->with('master_agama')->with('master_smp');

    } 

    public function master_kelas()
    {
        return $this->hasManyThrough(
            'App\Master_kelas',
            'App\Master_rombel',
            'rblKlsId', // Foreign key on idkelas table Master_rombel
            'klsId' //id kelas
        );
    }
    
}
