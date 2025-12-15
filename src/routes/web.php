<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\Auth\RegisterController;
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

Route::post('/register', [RegisterController::class, 'store'])->middleware('guest');

Route::middleware('auth')->group(function () {
    Route::get('/mypage', [AuthController::class, 'index']);
    Route::get('/mypage/profile', [AuthController::class, 'edit']);
    Route::patch('/mypage/profile',[AuthController::class, 'update']);
    Route::post('/item/{id}/like', [ItemController::class, 'toggle']);
    Route::post('/item/{id}/comment', [ItemController::class, 'commentStore']);
    Route::get('/purchase/{item}', [PurchaseController::class, 'show']);
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'editAddress']);
    Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress']);
    Route::post('/purchase/{item}', [PurchaseController::class, 'store']);
});