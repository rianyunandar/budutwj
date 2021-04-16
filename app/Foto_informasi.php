<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Foto_informasi extends Model
{
    protected $table = 'foto_informasi';
    protected $primaryKey = 'fotoinfoId';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = []; //tambahkan baris ini

    

   
}
