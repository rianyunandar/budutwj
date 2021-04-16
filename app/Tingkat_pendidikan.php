<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Tingkat_pendidikan extends Model
{
    protected $table = 'tingkat_pendidikan';
    protected $primaryKey = 'tkpdId';
    public $incrementing = false;
    public $timestamps = false;
    
}
