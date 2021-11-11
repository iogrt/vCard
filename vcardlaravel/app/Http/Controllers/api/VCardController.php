<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vcard;
use App\Http\Resources\VCardResource;
use App\Http\Requests\StoreVCardRequest;
use Carbon\Carbon;

class VCardController extends Controller
{
    public function getVCard(Vcard $vcard){//apenas a nivel de conhecimento de dados
        //return Vcard::all();
        //return Vcard::find($id);//sem usar o resource
        //return VCardResource::collection($id);//usando resources, pois nao devolve o dado diretamente
        return new VCardResource($vcard);
    }

    //public function store(StoreVCardRequest $request){
    public function store(Request $request){
        return $request->all(); 
       /* $vcard = Vcard::withTrashed()->where('phone_number',$request->phone_number)->whereNotNull('deleted_at')->first();

        $find = Vcard::where('phone_number',$request->phone_number)->first();

        if($find){
            return response()->json(['message'=>'This phone was used before in a account deleted!' ], 422);
        }

        if(!is_null($vcard)){
            return response()->json(['message'=>'This phone is already in use!' ], 422);
        }*/

       /* $user = new User();
        $user->fill($request->validated());
        $user->password = bcrypt($user->password);
        $user->save();
        return new UserResource($user);

        $now = Carbon::now();//data corrente

        
        return new VCardResource($newTask);*/

        /*
        $now = Carbon::now();//data corrente
        return $request;*/
        /*$validated_data = $request->validated();
        
        $newVCard = new Vcard;
        dd($newVCard);*/
        
        //$newVCard->fill($request->validated());
        /*$newVCard->phone_number = $validated_data['phone_number'];
        $newVCard->name = $validated_data['name'];
        $newVCard->email = $validated_data['email'];
        $newVCard->confirmation_code = $validated_data['confirmation_code'];
        $newVCard->password = bcrypt($newVCard->password);*/
        //$newVCard->created_at = $now;//atualizr o campo
        /*if ($request->hasFile('photo_url')) {
            $path = $request->photo_url->store('public/fotos');
            $newVCard->photo_url = basename($path);
        }*/
       /* $newVCard->save();
        return new UserResource($newVCard);*/
    }
}
