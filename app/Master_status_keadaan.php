<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Master_status_keadaan extends Model
{
  protected $table = 'master_status_keadaan';
  protected $primaryKey = 'mstId';
  public $incrementing = false;
  public $timestamps = false;
}
