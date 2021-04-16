<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Master_kelas extends Model
{
    protected $table = 'master_kelas';
    protected $primaryKey = 'klsId';
    public $incrementing = false;
    public $timestamps = false;
    
}
