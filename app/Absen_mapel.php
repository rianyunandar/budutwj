<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Absen_mapel extends Model
{
    protected $table = 'absen_mapel';
    protected $primaryKey = 'abmpId';
    public $incrementing = false;
    public $timestamps = false;
    
    public function master_mapel_jadwal()
    {
    return $this->belongsTo(Master_mapel_jadwal::class,'abmpMajdId','majdId');

    }
    public function master_rombel()
    {
    return $this->belongsTo(Master_rombel::class,'abmpRblId','rblId');

    }
    public function user_siswa()
    {
    return $this->belongsTo(User_siswa::class,'abmpSsaUsername','ssaUsername');

    }
    public function absen_kategori()
    {
    return $this->belongsTo(Absen_kategori::class,'abmpAkKode','akKode');

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
