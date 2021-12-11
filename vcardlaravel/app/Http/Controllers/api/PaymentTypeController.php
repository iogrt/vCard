<?php

namespace App\Http\Controllers\api;

//use App\Http\Controllers\Controller;
use Illuminate\Routing\Controller;
use App\Http\Resources\PaymentTypeResource;
use App\Models\PaymentType;
use Illuminate\Http\Request;

class PaymentTypeController extends Controller
{
    public function getAllPaymentTypes(){
        $types = PaymentType::whereNull('deleted_at')->get();

        return PaymentTypeResource::collection($types);
    }
}
