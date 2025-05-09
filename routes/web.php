<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FuncController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DestinasiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('app.dashboard');
})->name('dashboard');

Route::middleware('auth')->group(function () {
    // route for user
    Route::get('/user/dashboard', function () {
        return view('app.user.dashboard');
    })->name('user.dashboard');

    Route::get('/user/destination-list', function () {
        return view('app.user.destination-list');
    })->name('user.destination-list');

    Route::get('/user/detail-destination', function () {
        return view('app.user.detail-destination');
    })->name('user.detail-destination');
});

Route::get('/user/passenger-details', function () {
    return view('app.user.passenger-details');
});

Route::get('/user/select-payment-method', function () {
    return view('app.user.select-payment-method');
});

Route::get('/user/payment', function () {
    return view('app.user.payment');
});

Route::get('/user/order-detail', function () {
    return view('app.user.order-detail');
});

Route::get('/user/order-lists', function () {
    return view('app.user.order-lists');
});


Route::middleware('auth.driver')->prefix('driver')->group(function () {

    Route::get('/destination-list', [DestinasiController::class, 'destinationList'])->name('driver.destination-list');

    Route::get('/add-destination', [DestinasiController::class, 'create'])->name('driver.add-destination');

    Route::get('/detail-destination', [DestinasiController::class, 'show'])->name('driver.detail-destination');

    // Crud Destination
    Route::post('/add-destination', [DestinasiController::class, 'createPost'])->name('driver.add-destination.post');

    Route::get('/update-destination', [DestinasiController::class, 'update'])->name('driver.update-destination');
    Route::post('/update-destination', [DestinasiController::class, 'updatePost'])->name('driver.update-destination.post');

    Route::get('/delete-destination', [DestinasiController::class, 'delete'])->name('driver.delete-destination');

    Route::get('/search-destination', [DestinasiController::class, 'search'])->name('driver.search-destination');
});

// Keep the driver registration routes outside the auth middleware
Route::get('/driver/register-driver', [AuthController::class, 'registerDriver'])->name('driver.register-driver');
Route::post('/driver/register-driver', [AuthController::class, 'registerDriverPost'])->name('driver.register-driver.post');

// Authentication Routes
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerPost'])->name('register');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware('set_role:user')->group(function () {
    Route::get('/order/{destinasi}', function () {
        $user = FuncController::get_profile();
        return view('order')->with('user', $user);
    })->name('order');

    Route::get('/bayar/{id}', function () {
        $user = FuncController::get_profile();
        return view('bayar')->with('user', $user);
    })->name('bayar');

    Route::get('/pesanan', function () {
        $user = FuncController::get_profile();
        return view('pesanan')->with('user', $user);
    })->name('pesanan');

    Route::get('/chat', function () {
        $user = FuncController::get_profile();
        return view('chat')->with('user', $user);
    })->name('chat');
});


Route::middleware('set_role:seller')->prefix('seller')->group(function () {
    Route::get('/', function () {
        $user = FuncController::get_profile();
        return view('seller.main')->with('user', $user);
    })->name('seller.main');

    Route::get('/destinasi', function () {
        $user = FuncController::get_profile();
        return view('seller.destinasi')->with('user', $user);
    })->name('seller.destinasi');

    Route::get('/pesanan', function () {
        $user = FuncController::get_profile();
        return view('seller.pesanan')->with('user', $user);
    })->name('seller.pesanan');

    Route::get('/chat', function () {
        $user = FuncController::get_profile();
        return view('seller.chat')->with('user', $user);
    })->name('seller.chat');
});
