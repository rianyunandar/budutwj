<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class El_tugas_nilai extends Model
{
    protected $table = 'el_tugas_nilai';
    protected $primaryKey = 'tgsnilaiId';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = []; //tambahkan baris ini

    public function el_tugas()
    {
      return $this->belongsTo(El_tugas::class,'tgsnilaiIdTugas','tugasId');
    }
   
    
    
   
}
