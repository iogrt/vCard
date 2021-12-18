<?php

namespace App\Http\Controllers\api;

//use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentTypeRequest;
use http\Env\Response;
use Illuminate\Routing\Controller;
use App\Http\Resources\PaymentTypeResource;
use App\Models\PaymentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaymentTypeController extends Controller
{
    public function getAllPaymentTypesAdmin(){
        $types = PaymentType::withTrashed()->get();
        PaymentTypeResource::$timestamps = true;

        return PaymentTypeResource::collection($types);
    }

    public function getAllPaymentTypes(){
        $types = PaymentType::all();
        PaymentTypeResource::$timestamps = false;

        return PaymentTypeResource::collection($types);
    }

    public function show($paymentType){
        $payment = PaymentType::withTrashed()->find($paymentType);

        if($payment == null){
            return response()->json(["message' => 'Could not find Payment type '$paymentType'"],404);
        }

        PaymentTypeResource::$timestamps = false;
        return new PaymentTypeResource($payment);
    }

    public function addPaymentType(PaymentTypeRequest $request){
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string',
            'description' => 'nullable|string|max:8192',
            'validation_rules' => 'nullable'
        ],[
            'name.string' => 'Name must be text',
            'description.string' => 'Description must be text',
            'description.max' => 'Description maximum size of 8192 exceeded',
        ]);

        if($validator->fails()){
            return response()->json(["message" => "Validation Errors!","errors" => $validator->getMessageBag()],422);
        }

        $arr = [
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
            'validation_rules' => json_encode($request->validation_rules)
        ];

        $res = DB::transaction(fn() => PaymentType::create($arr));

        PaymentTypeResource::$timestamps = true;
        return new PaymentTypeResource($res);
    }

    public function deletePaymentType(PaymentType $payment_type){
        return DB::transaction(function() use($payment_type){
            $payment_type->delete();

            return $payment_type;
        });
    }


    public function alterPaymentType(Request $request,$id){
        // escape regex
        $request->validation_rules = '"' . str_replace("\\","\\\\",$request->validation_rules) . '"';

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string',
            'description' => 'nullable|string|max:8192',
            'validation_rules' => 'nullable'
        ],[
            'name.string' => 'Name must be text',
            'description.string' => 'Description must be text',
            'description.max' => 'Description maximum size of 8192 exceeded',
        ]);

        if($validator->fails()){
            return response()->json(["message" => "Validation Errors!","errors" => $validator->getMessageBag()],422);
        }

        $paymentType = DB::transaction(function() use($id,$request){
            $paymentType = PaymentType::find($id);

            if(!$paymentType){
                return response()->json(["message" => "Payment Type $id doesn't exist!"],422);
            }

            $paymentType->code = $request->code;
            $paymentType->name = $request->name ?? null;
            $paymentType->description = $request->description ?? null;
            $paymentType->validation_rules = $request->validation_rules ?? null;

            $paymentType->update();

            return $paymentType;
        });


        return new PaymentTypeResource($paymentType);
    }

}
