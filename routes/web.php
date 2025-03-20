<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FuncController;

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

Route::get('/dashboard', function () {
    return view('dashboard');
});
Route::get('/user/dashboard', function () {
    return view('app.user.dashboard');
});
Route::get('/user/destination-list', function () {
    return view('app.user.destination-list');
});
Route::get('/user/detail-destination', function () {
    return view('app.user.detail-destination');
});
Route::get('/driver/register-driver', function () {
    return view('auth.register-driver');
});
Route::get('/driver/destination-list', function () {
    return view('app.driver.destination-list');
});

Route::get('/auth/login', function () {
    $redirectResponse = FuncController::check_user();

    if ($redirectResponse !== null) {
        return $redirectResponse;
    }

    return view('auth.login');
})->name('login');

Route::get('/auth/register', function () {
    $redirectResponse = FuncController::check_user();

    if ($redirectResponse !== null) {
        return $redirectResponse;
    }

    return view('auth.register');
})->name('register');

Route::get('/', function () {
    $user = FuncController::get_profile_without_abort();
    return view('home')->with('user', $user);
})->name('home');

Route::get('/destinasi', function () {
    $user = FuncController::get_profile_without_abort();
    return view('destinasi')->with('user', $user);
})->name('destinasi');

Route::get('/destinasi/{destinasi}', function () {
    $user = FuncController::get_profile_without_abort();
    return view('detail-destinasi')->with('user', $user);
})->name('destinasi.detail');


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
