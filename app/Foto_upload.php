<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Foto_upload extends Model
{
    protected $table = 'foto_upload';
    protected $primaryKey = 'fotoId';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = []; //tambahkan baris ini

    

   
}
