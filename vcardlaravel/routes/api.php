<?php

use App\Models\User;
use App\Models\Category;
use App\Models\DefaultCategory;
use App\Models\PaymentType;
use App\Models\Transaction;
use App\Models\Vcard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\VCardController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('vcards/{vcard}', [VCardController::class, 'getVCard']);
Route::post('vcards', [VCardController::class, 'store']);

//mock examples
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

