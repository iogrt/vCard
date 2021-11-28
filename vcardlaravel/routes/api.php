<?php

use App\Http\Controllers\api\PaymentTypeController;
use App\Http\Controllers\AuthUserController;
use App\Models\User;
use App\Models\Category;
use App\Models\DefaultCategory;
use App\Models\PaymentType;
use App\Models\Transaction;
use App\Models\Vcard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\VCardController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');

//mock examples
Route::get('/authUsers',[AuthUserController::class, 'getAllUsers']);
Route::get('vcards/{vcard}', [VCardController::class, 'getVCard']);
Route::post('vcards', [VCardController::class, 'store']);
Route::get('vcards/{vcard}/transactions', [VCardController::class, 'getVcardTransactions']);

Route::post('/transactions/vcard',[TransactionController::class,'store_vcard_transaction']);
Route::get('/transactions/{transaction}',fn($transaction) => new \App\Http\Resources\TransactionResource(Transaction::find($transaction)));
Route::post('/transactions/others',[TransactionController::class,'store_transaction_other']);

//admin prefix
Route::get('/transactions/',[TransactionController::class,'show_all_transactions']);

Route::get('/payment_types',[PaymentTypeController::class,'getAllPaymentTypes']);

//mock examples
/*
Route::get('/users',fn() => User::all()->toArray());
Route::get('/categories',fn() => Category::all()->toArray());
Route::get('/default_categories',fn() => DefaultCategory::all()->toArray());
Route::get('/transactions',fn() => Transaction::all()->toArray());
Route::get('/vcards',fn() => Vcard::all()->toArray());
Route::get('/payment_types',fn() => PaymentType::all()->toArray());

Route::get('/vcards/{vcard}/transactions',fn($vcard) => Vcard::find($vcard)->transactions->toArray());
Route::get('/vcards/{vcard}/categories',fn($vcard) => Vcard::find($vcard)->categories->toArray());

Route::get('/transactions/{transaction}/pairTransaction',fn($transaction) => Transaction::find($transaction)->pairTransaction);
Route::get('/transactions/{transaction}/pairVcard',fn($transaction) => Transaction::find($transaction)->pairVcard);
Route::get('/transactions/{transaction}/category',fn($transaction) => Transaction::find($transaction)->category);

Route::get('/transactions/{transaction}/paymentType',fn($transaction) => Transaction::find($transaction)->paymentType);
Route::get('/transactions/{transaction}/paymentType',fn($transaction) => Transaction::find($transaction)->paymentType);

Route::prefix('admin/')->group(function() {
    Route::post('login', [AuthController::class,'login'])->name('login');
});

// protected test, needs login
Route::middleware('auth:api')->get('/locked/users',fn() => User::all()->toArray());
 */
