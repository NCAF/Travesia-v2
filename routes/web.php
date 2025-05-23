<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FuncController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DestinasiController;
use App\Models\Destinasi;

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

    Route::get('/user/destination-list', [DestinasiController::class, 'userDestinationList'])->name('user.destination-list');

    Route::get('/user/detail-destination/{id}', [DestinasiController::class, 'detailDestination'])->name('user.detail-destination');


    Route::get('/user/search-destination', [DestinasiController::class, 'searchUser'])->name('user.search-destination');

    Route::get('/user/passenger-details/{id}', function ($id) {
    $destinasi = Destinasi::findOrFail($id);
    return view('app.user.passenger-details', compact('destinasi'));
})->name('user.passenger-details');

    Route::post('/orders', [\App\Http\Controllers\Api\OrderController::class, 'store'])->name('orders.store');

    Route::get('/user/order-lists', [\App\Http\Controllers\Api\OrderController::class, 'userOrderList'])->name('user.order-lists');

    Route::get('/user/order-detail', function () {
        return view('app.user.order-detail');
    });
    
    Route::get('/user/order-detail/{id}', [\App\Http\Controllers\Api\OrderController::class, 'orderList'])->name('user.order-detail');

});

Route::get('/user/destination-list-not-login', [DestinasiController::class, 'destinationListNotLogin'])->name('user.destination-list-not-login');

Route::get('/user/search-destination-not-login', [DestinasiController::class, 'searchUserNotLogin'])->name('user.search-destination-not-login');

Route::get('/user/select-payment-method', function () {
    return view('app.user.select-payment-method');
});

Route::get('/user/payment', function () {
    return view('app.user.payment');
});

Route::get('/user/order-detail', function () {
    return view('app.user.order-detail');
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
