<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class isUnblockedUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::user()->user_type == 'V' && Auth::user()->blocked != 1){
            return $next($request);
        }
        else if(Auth::user()->blocked == 1){
            return response()->json(['message' => 'Vcard number ' . Auth::user()->username . ' is temporarily blocked!'],403);
        }

        return response()->json(['message' => 'Only Vcard users allowed here'],403);
    }
}
