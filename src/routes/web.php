<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', [ItemController::class, 'index']);
Route::get('/item/{item_id}', [ItemController::class, 'show']);

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back();
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/purchase/success', [PurchaseController::class, 'success'])->name('purchase.success');
Route::get('/purchase/cancel', function () {
    return redirect('/');
})->name('purchase.cancel');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mypage', [AuthController::class, 'index']);
    Route::get('/mypage/profile', [AuthController::class, 'edit']);
    Route::patch('/mypage/profile',[AuthController::class, 'update']);
    Route::post('/item/{id}/like', [ItemController::class, 'toggle']);
    Route::post('/item/{id}/comment', [ItemController::class, 'commentStore']);
    Route::get('/purchase/{item}', [PurchaseController::class, 'show']);
    Route::post('/purchase/payment-method', function (Request $request) {
        session(['payment_method' => $request->payment_method]);
        return response()->json(['status' => 'ok']);
    });
    Route::get('/purchase/address/{item}', [PurchaseController::class, 'editAddress']);
    Route::post('/purchase/address/{item}', [PurchaseController::class, 'updateAddress']);
    Route::post('/purchase/{item}', [PurchaseController::class, 'store']);
    Route::get('/sell', [ItemController::class, 'sellIndex']);
    Route::post('/sell', [ItemController::class, 'sellStore']);
});