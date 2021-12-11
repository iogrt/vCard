<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class isUnblocked
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
        if(Auth::user()->blocked != 1){
            return $next($request);
        }
        else {
            return response()->json(['message' => Auth::user()->username . ' is temporarily blocked!'],403);
        }
    }
}
