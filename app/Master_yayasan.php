<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Master_yayasan extends Model
{
    protected $table = 'master_yayasan';
    protected $primaryKey = 'msysId';
    public $incrementing = false;
    public $timestamps = false;

    
    public function master_sekolah()
    {
        return $this->belongsTo(Master_sekolah::class,'msysId','sklMsysId');  
    }
    
}
