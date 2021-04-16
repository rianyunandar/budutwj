<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class LoginSiswa extends Authenticatable
{
    use Notifiable;

    protected $table = 'user_siswa';
    protected $primaryKey = 'ssaId';

    protected $fillable = [
        'ssaUsername', 'ssaPassword','ssaRole','ssaEmail','ssaQrCode'
    ];
    
    protected $hidden = [
        'ssaPassword', 'ssaRemember_token',
    ];
    
    //menyesuaikan password login pada tabel databse
    public function getAuthPassword()
    {
        return $this->ssaPassword;
    }
}