<?php

use App\Http\Controllers\api\PaymentTypeController;
use App\Http\Controllers\api\CategoryController;
use App\Models\User;
use App\Models\Category;
use App\Models\DefaultCategory;
use App\Models\PaymentType;
use App\Models\Transaction;
use App\Models\Vcard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\VCardController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\TransactionController;
use App\Http\Resources\AuthUserResource;

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

Route::post('login', [AuthController::class, 'login'])->name('login');

Route::post('vcards', [VCardController::class, 'store']);

Route::middleware(['auth:api'])->group(function(){
    Route::post('logout', [AuthController::class, 'logout']);


    Route::middleware(['isUnblocked'])->group(function() {
        // Routes for admins OR unblocked users
        Route::get('users/me', [AuthController::class, 'myself']);
        Route::put('users/me',[AuthController::class,'editProfile']);
        Route::get('/payment_types',[PaymentTypeController::class,'getAllPaymentTypes']);
        Route::get('/payment_types/{payment_type}',[PaymentTypeController::class,'show']);

        // Routes for unblocked users
        Route::middleware(['isVcardUser'])->group(function() {

            Route::get('vcards/transactions', [TransactionController::class, 'show_user_transactions']);
            Route::post('/vcards/categories/default', [VCardController::class, 'addCategoryFromDefault']);
            Route::post('/vcards/categories', [VCardController::class, 'addNewCategory']);
            Route::put('/vcards/categories/{id}', [VCardController::class, 'alterCategory']);
            Route::get('/vcards/categories', [VCardController::class, 'getCategories']);
            Route::get('/vcards/categories/{id}', [VCardController::class, 'getCategory']);
            Route::delete('/vcards/categories', [VCardController::class, 'removeCategory']);
            Route::post('/transactions',[TransactionController::class,'userTransaction']);

            Route::delete('/vcards', [VCardController::class, 'deleteVcard']);
        });
    });

    Route::middleware(['isAdmin'])->prefix('admin')->group(function() {
        Route::get('/categories',[CategoryController::class,'show_all']);
        Route::get('/categories/{category}', [CategoryController::class, 'show']);
        Route::post('/categories',[CategoryController::class,'create']);
        Route::delete('/categories/{category}',[CategoryController::class, 'delete']);
        Route::put('/categories/{category}', [CategoryController::class, 'update']);
        Route::get('/transactions/{transaction}',fn($transaction) => new \App\Http\Resources\TransactionResource(Transaction::find($transaction)));
        Route::get('/payment_types',[PaymentTypeController::class,'getAllPaymentTypes']);
        Route::put('/payment_types/{id}', [PaymentTypeController::class, 'alterPaymentType']);
        Route::post('/payment_types', [PaymentTypeController::class, 'addPaymentType']);
        Route::delete('/payment_types/{payment_type}',[PaymentTypeController::class,'deletePaymentType']);
        Route::get('/payment_types',[PaymentTypeController::class,'getAllPaymentTypesAdmin']);
        Route::patch('/vcard/{vcard}',[VCardController::class,'blockVcard']);
        Route::get('/transactions',[TransactionController::class,'show_all_transactions']);
        Route::patch('/vcard/{vcard}',[VCardController::class,'blockVcard']);
        Route::post('/transactions',[TransactionController::class,'adminTransaction']);
    });
});



//admin prefix


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
