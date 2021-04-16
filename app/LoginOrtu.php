<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class LoginOrtu extends Authenticatable
{
    use Notifiable;

    protected $table = 'user_ortu';
    protected $primaryKey = 'ortuId';

    protected $fillable = [
        'ortuUsername', 'ortuPassword','ortuRole','ortuEmail','ortuQrCode'
    ];
    
    protected $hidden = [
        'ortuPassword', 'ortuRemember_token',
    ];
    
    //menyesuaikan password login pada tabel databse
    public function getAuthPassword()
    {
        return $this->ortuPassword;
    }
}