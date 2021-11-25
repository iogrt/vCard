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
        return new VCardResource($vcard);//se for um user deletado nao funciona.....
    }

    public function store(StoreVCardRequest $request){
        $find = Vcard::where('phone_number',$request->phone_number)->first();
        if($find){
            return response()->json(['message'=>'This phone cant be used!' ], 422);
        }

        $finDel = Vcard::withTrashed()->where('phone_number',$request->phone_number)->whereNotNull('deleted_at')->first();
        if(!is_null($finDel)){
            return response()->json(['message'=>'This phone was used before in a account deleted!!' ], 422);
        }

        $now = Carbon::now();//data corrente

        $newCard = new Vcard;

        $newCard->phone_number = $request->phone_number;
        $newCard->name = $request->name;
        $newCard->email = $request->email;
        $newCard->blocked = 0;
        $newCard->password = bcrypt($newCard->password);
        $newCard->confirmation_code = bcrypt($request->confirmation_code);
        if ($request->file('photo_url')!=null) {
            $path = $request->photo_url->store('public/fotos');
            $newCard->photo_url = basename($path);
        }

        $newCard->save();

        return new VCardResource($newCard);
    }
}
