<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Semester extends Model
{
    protected $table = 'semester';
    protected $primaryKey = 'smId';
    public $incrementing = false;
    public $timestamps = false;

    public function tahun_ajaran()
    {
        return $this->belongsTo(Tahun_ajaran::class,'smTajrId','tajrId');  
    }
    public function master_sekolah()
    {
        return $this->belongsTo(Master_sekolah::class,'smSklId','sklId');  
    }
    
}
