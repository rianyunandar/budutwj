<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Absen_finger_siswa extends Model
{
    protected $table = 'absen_finger_siswa';
    protected $primaryKey = 'afsId';
    public $incrementing = false;
    public $timestamps = false;

    public function master_jurusan()
    {
       return $this->belongsTo(Master_jurusan::class,'afsJrsId','jrsId');
       
    }
    public function master_sekolah()
    {
       return $this->belongsTo(Master_sekolah::class,'afsSklId','sklId');
       
    }
    public function user_siswa()
    {
      
       return $this->belongsTo(User_siswa::class,'afsSsaUsername','ssaUsername');
       
    }
    public function master_rombel()
    {
       return $this->belongsTo(Master_rombel::class,'afsRblId','rblId')->with('master_kelas');
       
    }
    public function absen_kategori()
    {
       return $this->belongsTo(Absen_kategori::class,'afsAkId','akKode');
       
    }
    
    
    
}
