<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_ortu extends Model
{
    protected $table = 'user_ortu';
    protected $primaryKey = 'ortuId';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'ortuUsername', 'ortuPassword','ortuRole','ortuEmail','ortuQrCode'
    ];

    // public function role_admin()
    // {
    //     //return $this->belongsTo(User_admin::class,'admRlId');
    //     return $this->hasMany('App\Role_admin','rladmId','admId');
    // }
}
