<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\TransactionAdminRequest;
use App\Http\Requests\TransactionRequest;
use App\Http\Requests\TransactionUserRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Category;
use App\Models\PaymentType;
use App\Models\Transaction;
use App\Models\Vcard;
use App\Rules\CategoryRule;
use App\Rules\DebitRule;
use App\Rules\TransactionTypeRule;
use Carbon\Carbon;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/*
    A minha interpretação do enunciado: Um vcard só pode fazer transações de débito. Quando a transação é para outro vcard
    é criada outra transacao mirror de crédito, sem categoria e descrição.
    Admins só podem fazer transações de credito para vcards, e nunca do tipo VCARD.
 */
class TransactionController extends Controller
{

    public function show_all_transactions(){
        $transactions = Transaction::query()
            ->orderByDesc('datetime')
            ->paginate(20);

        TransactionResource::$includeDeleted = true;

        return TransactionResource::collection($transactions);
    }

    public function show_user_transactions(){
        $transactions = Transaction::where('vcard',Auth::user()->username)
            ->whereNull('deleted_at')
            ->orderBy('datetime','desc')
            ->paginate(15);

        TransactionResource::$includeDeleted = false;

        return TransactionResource::collection($transactions);
    }

    public function userTransaction(TransactionUserRequest $request){
        $request->merge(['vcard' => Auth::user()->username]);

        if($request->payment_type == 'VCARD'){

            return $this->store_vcard_transaction($request);
        }

        $request->merge(['type' => 'D']);
        return $this->store_transaction_other($request);
    }

    public function adminTransaction(TransactionAdminRequest $request){
        $request->merge(['type' => 'C']);

        return $this->store_transaction_other($request);
    }

    public function store_vcard_transaction(Request $request){
        try {
            DB::beginTransaction();

            $request = (object) $request->only([
                'vcard',
                'payment_reference',
                'value',
                'payment_type',
                'category',
                'description',
            ]);

            $newTransaction = new Transaction();

            $newTransaction->vcard = $request->vcard;
            $newTransaction->pair_vcard = $request->payment_reference;

            $newTransaction->date = Carbon::now();
            $newTransaction->datetime = Carbon::now();

            if(isset($request->description)){
                $newTransaction->description = $request->description;
            }

            if(isset($request->category) && Auth::user()->user_type == 'V'){
                $category = Category::where('name', $request->category)->where('vcard',$request->vcard)->first();

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
            $newTransaction->old_balance = (int)$vcard_owner->balance;
            $vcard_owner->balance = $vcard_owner->balance - $balance;

            $newTransaction->new_balance = $vcard_owner->balance;
            $newTransaction->vcard = $vcard_owner->phone_number;
            $newTransaction->pair_vcard = $vcard_dest->phone_number;

            $newTransaction->save();
            $vcard_owner->save();

            $otherTransaction = new Transaction($newTransaction->toArray());
            $otherTransaction->category_id = null;
            $otherTransaction->description = null;
            $otherTransaction->vcard = $newTransaction->pair_vcard;
            $otherTransaction->type = 'C';
            $otherTransaction->payment_reference = $request->payment_reference;
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

    public function store_transaction_other(Request $request){
        try {
            DB::beginTransaction();

            $request = (object) $request->only([
                'vcard',
                'payment_reference',
                'value',
                'payment_type',
                'type',
                'category',
                'description',
            ]);

            $newTransaction = new Transaction();

            $newTransaction->vcard = $request->vcard;
            $newTransaction->pair_vcard = null;

            $newTransaction->date = Carbon::now();
            $newTransaction->datetime = Carbon::now();

            if(isset($request->description)){
                $newTransaction->description = $request->description;
            }

            if(isset($request->category)){
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
            $newTransaction->old_balance = (int)$vcard_owner->balance;

            switch($request->type){
                case 'C':
                    $vcard_owner->balance = (int)$vcard_owner->balance + $balance;
                    break;
                case 'D':
                    $vcard_owner->balance = (int)$vcard_owner->balance - $balance;
            }

            $newTransaction->new_balance = (int)$vcard_owner->balance;
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
