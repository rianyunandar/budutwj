<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Absen_hadir_guru extends Model
{
    protected $table = 'absen_hadir_guru';
    protected $primaryKey = 'hgId';
    public $incrementing = false;
    public $timestamps = false;
    
}
