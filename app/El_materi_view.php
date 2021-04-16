<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class El_materi_view extends Model
{
    protected $table = 'el_materi_view';
    protected $primaryKey = 'viewId';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = []; //tambahkan baris ini

    public function user_siswa()
    {
      return $this->belongsTo(User_siswa::class,'viewIdSiswa','ssaId');  
    }
    public function el_materi()
    {
      return $this->belongsTo(El_materi::class,'viewIdMateri','materiId');  
    }

    
    
   
}
