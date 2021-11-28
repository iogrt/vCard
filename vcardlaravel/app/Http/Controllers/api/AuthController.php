<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;


class AuthController extends Controller
{
        public function login(Request $request){

            request()->request->add ([
                'grant_type' => 'password',
                'client_id' => env('PASSPORT_CLIENT_ID'),
                'client_secret' => env('PASSPORT_CLIENT_SECRET'),
                'username' => (string)$request->username,
                'password' => $request->password,
                'scope'    => '',
            ]);

            $url = config('app.passport_url') . '/oauth/token';

            $request = Request::create($url, 'POST');
            $response = Route::dispatch($request);
            dd($response);

            $errorCode = $response->getStatusCode();

            if ($errorCode == '200') {
                return json_decode((string) $response->getBody(), true);
            } else {
                return response()->json(
                ['msg' => 'User credentials are invalid'],
                $errorCode
                );
            }
    }

    public function logout(Request $request) {
        $accessToken = $request->user()->token();
        $token = $request->user()->tokens->find($accessToken);
        $token->revoke();
        $token->delete();
        return response(['msg' => 'Token revoked'], 200);
    }
}
