<?php

use App\Http\Controllers\DishController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/items', [DishController::class, 'index'])->name('items');
Route::post('/order', OrderController::class)->name('order');

Route::get('/sales/dishes', [ReportController::class, 'dishSales'])->name('dishSales');
Route::get('/sales/overview', [ReportController::class, 'saleOverview'])->name('saleOverview');

