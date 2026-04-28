<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OnlineOrderApiController;

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

Route::prefix('v1')->group(function () {
    // Public API endpoints for online orders
    Route::get('/products', [OnlineOrderApiController::class, 'getProducts']);
    Route::get('/products/{id}', [OnlineOrderApiController::class, 'getProduct']);
    Route::post('/orders', [OnlineOrderApiController::class, 'createOrder']);
    Route::get('/orders/{id}/status', [OnlineOrderApiController::class, 'getOrderStatus']);
});
