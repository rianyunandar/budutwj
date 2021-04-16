<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class LoginAdmin extends Authenticatable
{
    use Notifiable;

    protected $table = 'user_admin';
    protected $primaryKey = 'admId';

    protected $fillable = [
        'admUsername', 'admPassword','admRole',
    ];
    
    protected $hidden = [
        'admPassword', 'admRemember_token',
    ];
    
    //menyesuaikan password login pada tabel databse
    public function getAuthPassword()
    {
        return $this->admPassword;
    }
    public function master_jabatan(){
     return $this->hasOne(Master_jabatan::class,'mjbId','admRole');
    }
    
}