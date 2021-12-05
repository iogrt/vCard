<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\EditProfileRequest;
use App\Http\Resources\AuthUserResource;
use App\Models\AuthUser;
use App\Models\User;
use App\Models\Vcard;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;


class AuthController extends Controller
{
        public function login(Request $request){

            request()->request->add ([
                'grant_type' => 'password',
                'client_id' => env('PASSPORT_CLIENT_ID'),
                'client_secret' => env('PASSPORT_CLIENT_SECRET'),
                'username' => (string)$request->username,
                'password' => (string)$request->password,
                'scope'    => '',
            ]);

            $url = env('PASSPORT_SERVER_URL') . '/oauth/token';

            $request = Request::create($url, 'POST');
            $response = Route::dispatch($request);

            $errorCode = $response->getStatusCode();

            if ($errorCode == '200') {
                return json_decode((string) $response->content(), true);
            } else {
                return response()->json(
                ['msg' => 'User credentials are invalid'],
                $errorCode
                );
            }
    }

    public function editProfile(EditProfileRequest $request){
        return DB::transaction(function() use($request){
            switch(Auth::user()->user_type){
                case 'A':
                    $user = User::find(Auth::user()->username);
                    break;
                case 'V':
                    $user = Vcard::find(Auth::user()->username);
                    break;
            }

            if($request->name){
                $user->name = $request->name;
            }

            if($request->password){
                $user->name = bcrypt($request->password);
            }

            if($request->email){
                $user->email = $request->email;
            }

            if ($request->hasFile('photo_url')) {
                $path = $request->photo_url->store('public/fotos');
                $user->photo_url = basename($path);
            }

            $user->update();

            return new AuthUserResource($user);
        });
    }

    public function myself(){
            return new AuthUserResource(Auth::user());
    }

    public function logout(Request $request) {
        $accessToken = $request->user()->token();
        $token = $request->user()->tokens->find($accessToken);
        $token->revoke();
        $token->delete();
        return response(['msg' => 'Token revoked'], 200);
    }
}
