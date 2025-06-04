<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DestinasiController;
use App\Http\Controllers\Api\CheckoutController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [AuthController::class, 'login'])->name('api.login');
Route::post('register', [AuthController::class, 'register'])->name('api.register');
Route::get('destinasi/all', [DestinasiController::class, 'all'])->name('api.destinasi.all');
Route::get('destinasi/recent', [DestinasiController::class, 'recent'])->name('api.destinasi.recent');
Route::get('destinasi/{kode}', [DestinasiController::class, 'show'])->name('api.destinasi.show');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('api.logout');

    Route::middleware(['role:seller'])->prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('api.dashboard.index');
    });

    Route::prefix('destinasi')->group(function () {
        Route::middleware(['role:user'])->group(function () {
            Route::post('/order', [DestinasiController::class, 'order'])->name('api.destinasi.order');
        });

        Route::middleware(['role:seller'])->group(function () {
            Route::get('/', [DestinasiController::class, 'index'])->name('api.destinasi.index');
            Route::post('/', [DestinasiController::class, 'store'])->name('api.destinasi.store');
            Route::post('/traveling/{id}', [DestinasiController::class, 'traveling'])->name('api.destinasi.traveling');
            Route::post('/arrived/{id}', [DestinasiController::class, 'arrived'])->name('api.destinasi.arrived');
            Route::put('/{id}', [DestinasiController::class, 'update'])->name('api.destinasi.update');
            Route::delete('/{id}', [DestinasiController::class, 'destroy'])->name('api.destinasi.destroy');
        });
    });

    Route::prefix('orders')->group(function () {
        Route::middleware(['role:user'])->post('/order/pay', [OrderController::class, 'pay'])->name('api.orders.pay');

        Route::middleware(['role:seller'])->group(function () {
            Route::get('/seller', [OrderController::class, 'seller'])->name('api.orders.seller');
            Route::post('/{id}', [OrderController::class, 'finished'])->name('api.orders.finished');
        });

        Route::get('/detail/{id}', [OrderController::class, 'show'])->name('api.orders.show');

        Route::middleware(['role:user'])->group(function () {
            Route::get('/user', [OrderController::class, 'user'])->name('api.orders.user');
            Route::post('/', [OrderController::class, 'store'])->name('api.orders.store');
        });
    });

    Route::prefix('chat')->group(function () {
        Route::middleware(['role:seller'])->get('/seller', [ChatController::class, 'seller'])->name('api.chat.seller');
        Route::middleware(['role:user'])->get('/user', [ChatController::class, 'user'])->name('api.chat.user');
    });

    Route::prefix('messages')->group(function () {
        Route::get('/{chat_id}', [MessageController::class, 'index'])->name('api.messages.index');
        Route::post('/{chat_id}', [MessageController::class, 'store'])->name('api.messages.store');
    });

    Route::prefix('checkout')->group(function () {
        Route::post('/process', [CheckoutController::class, 'process'])->name('api.checkout.process');
    });

});
