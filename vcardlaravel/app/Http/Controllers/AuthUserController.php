<?php

namespace App\Http\Controllers;

use App\Models\AuthUser;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class AuthUserController extends Controller
{
    //
    public function getAllUsers(){
        return AuthUser::all()->toArray();
    }
}
