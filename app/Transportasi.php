<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Transportasi extends Model
{
    protected $table = 'transportasi';
    protected $primaryKey = 'trsId';
    public $incrementing = false;
    public $timestamps = false;

    
}
