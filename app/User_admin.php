<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_admin extends Model
{
    protected $table = 'user_admin';
    protected $primaryKey = 'admId';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'admUsername', 'admPassword','admRole',
    ];

    //hiden atribut password
    protected $hidden = ['admPassword'];
    
    public function role_admin()
    {
        return $this->belongsTo(Master_jabatan::class,'mjbKode','admRole');
    }
    

}
