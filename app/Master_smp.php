<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Master_smp extends Model
{
    protected $table = 'master_smp';
    protected $primaryKey = 'smpId';
    public $incrementing = false;
    public $timestamps = false;

   
}
