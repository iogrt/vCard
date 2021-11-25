<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionOtherRequest;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Category;
use App\Models\PaymentType;
use App\Models\Transaction;
use App\Models\Vcard;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{

    public function show_all_transactions(){
        $transactions = Transaction::query()
            ->orderBy('datetime')
            ->paginate(20);

        return TransactionResource::collection($transactions);
    }

    public function store_vcard_transaction(TransactionRequest $request){
        try {
            DB::beginTransaction();

            $newTransaction = new Transaction();

            $newTransaction->vcard = Vcard::find($request->vcard)->phone_number;
            $newTransaction->pair_vcard = Vcard::find($request->payment_reference)->phone_number;

            $newTransaction->date = Carbon::now();
            $newTransaction->datetime = Carbon::now();

            if($request->description){
                $newTransaction->description = $request->description;
            }

            if($request->category){
                $category = Category::where('name', $request->category)->first();

                $newTransaction->category_id = $category->id;
            }

            $newTransaction->payment_type = 'VCARD';
            $newTransaction->type = 'D';

            $vcard_owner = Vcard::where('phone_number', $request->vcard)->first();
            $vcard_dest = Vcard::where('phone_number', $request->payment_reference)->first();

            //como é pra outra vcard a reference é sempre o phone number

            $newTransaction->payment_reference = $vcard_owner->phone_number;
            $balance = $request->value;

            //type is always debit here

            $newTransaction->value = $balance;
            $newTransaction->old_balance = $vcard_owner->balance;
            $vcard_owner->balance = $vcard_owner->balance - $balance;

            $newTransaction->new_balance = $vcard_owner->balance;
            $newTransaction->vcard = $vcard_owner->phone_number;
            $newTransaction->pair_vcard = $vcard_dest->phone_number;

            $newTransaction->save();
            $vcard_owner->save();

            $otherTransaction = new Transaction($newTransaction->toArray());
            $otherTransaction->category_id = $newTransaction->category_id;
            $otherTransaction->vcard = $newTransaction->pair_vcard;
            $otherTransaction->type = 'C';
            $otherTransaction->pair_vcard = $newTransaction->vcard;
            $otherTransaction->old_balance = $vcard_dest->balance;
            $vcard_dest->balance += $newTransaction->value;
            $otherTransaction->new_balance = $otherTransaction->old_balance + $newTransaction->value;

            $otherTransaction->save();
            $vcard_dest->save();

            $newTransaction->pair_transaction = $otherTransaction->id;
            $otherTransaction->pair_transaction = $newTransaction->id;

            $otherTransaction->update();
            $newTransaction->update();

            DB::commit();

            return new TransactionResource($newTransaction);
        }
        catch(\Exception $th){
            DB::rollBack();

            return $th->getMessage();
        }
    }

    public function store_transaction_other(TransactionOtherRequest $request){
        try {
            DB::beginTransaction();

            $newTransaction = new Transaction();

            $newTransaction->vcard = Vcard::find($request->vcard)->phone_number;
            $newTransaction->pair_vcard = null;

            $newTransaction->date = Carbon::now();
            $newTransaction->datetime = Carbon::now();

            if($request->description){
                $newTransaction->description = $request->description;
            }

            if($request->category){
                $newTransaction->category_id = Category::where('name', $request->category)->first()->id;
            }

            $newTransaction->payment_type = $request->payment_type;

            $vcard_owner = Vcard::where('phone_number', $request->vcard)->first();

            //aqui a reference depende das validation rules. tou a pensar guardar em regex

            $newTransaction->payment_reference = $request->payment_reference;
            $balance = $request->value;

            //type is always debit here

            $newTransaction->type = $request->type;
            $newTransaction->value = $balance;
            $newTransaction->old_balance = $vcard_owner->balance;

            switch($request->type){
                case 'C':
                    $vcard_owner->balance = $vcard_owner->balance + $balance;
                    break;
                case 'D':
                    $vcard_owner->balance = $vcard_owner->balance - $balance;
            }

            $newTransaction->new_balance = $vcard_owner->balance;
            $newTransaction->vcard = $vcard_owner->phone_number;
            $newTransaction->pair_transaction = null;

            $newTransaction->save();
            $vcard_owner->save();

            DB::commit();

            return new TransactionResource($newTransaction);
        }
        catch(\Exception $th){
            DB::rollBack();

            return $th->getMessage();
        }
    }

}
