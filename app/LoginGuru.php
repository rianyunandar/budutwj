<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class LoginGuru extends Authenticatable
{
    use Notifiable;

    protected $table = 'user_guru';
    protected $primaryKey = 'ugrId';

    protected $fillable = [
        'ugrUsername', 'ugrPassword','ugrRole','ugrEmail','ugrQrCode'
    ];
    
    protected $hidden = [
        'ugrPassword', 'remember_token',
    ];
    
    //menyesuaikan password login pada tabel databse
    public function getAuthPassword()
    {
        return $this->ugrPassword;
    }
    
}