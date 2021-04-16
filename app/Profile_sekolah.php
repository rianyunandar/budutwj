<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Profile_sekolah extends Model
{
    protected $table = 'profile_sekolah';
    protected $primaryKey = 'prsklId';
    public $incrementing = false;
    public $timestamps = false;
    
}
