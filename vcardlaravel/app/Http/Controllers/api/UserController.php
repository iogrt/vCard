<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function create(User $user){
       //verificar aqui se phone number e etc ja foram utilizados
    }
}
