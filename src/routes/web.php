<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
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

Route::get('/', [ProductController::class, 'index']);
Route::get('/items/{item_id}', [ProductController::class, 'show']);
Route::get('/email', [ProductController::class, 'email']);

Route::middleware('auth')->group(function () {
     Route::get('/sell', [ProductController::class, 'sell']);
     Route::post('/sell', [ProductController::class, 'item']);
     Route::get('/mypage', [ProductController::class, 'mypage']);
     Route::get('/mypage/profile', [ProductController::class, 'profile']);
     Route::post('/mypage/profile', [ProductController::class, 'storeOrUpdate']);
     Route::post('/items/{item_id}/like', [ProductController::class, 'toggle']);
     Route::post('/items/{item_id}/comment', [ProductController::class, 'comment']);
     Route::get('/purchase/{item_id}', [ProductController::class, 'purchase']);
     Route::post('/purchase/{item_id}', [ProductController::class, 'payment']);
     Route::get('/purchase/address/{item_id}', [ProductController::class, 'address']);
     Route::post('/purchase/address/{item_id}', [ProductController::class, 'updateAddress']);
     Route::get('/success/{item_id}', [ProductController::class, 'success']);
    
 });


 Route::post('/logout', [ProductController::class, 'logout']);
Route::post('/login', [ProductController::class, 'login']);

