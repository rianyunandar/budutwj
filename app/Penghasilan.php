<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Penghasilan extends Model
{
    protected $table = 'penghasilan';
    protected $primaryKey = 'pnId';
    public $incrementing = false;
    public $timestamps = false;
    
}
