<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class AuthUser extends Authenticatable
{
    protected $table = 'view_auth_users';

    use HasApiTokens, HasFactory, Notifiable;

    protected $hidden = [
        'password'
    ];

    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }
}
