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
use App\Http\Controllers\Api\MidtransCallbackController;
use App\Http\Controllers\Api\SessionController;

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

// Session Management Routes (No authentication required for CSRF token)
Route::get('csrf-token', [SessionController::class, 'getCsrfToken'])->name('api.csrf-token');
Route::post('keep-alive', [SessionController::class, 'keepAlive'])->name('api.keep-alive');
Route::get('session-info', [SessionController::class, 'getSessionInfo'])->name('api.session-info');
Route::post('refresh-session', [SessionController::class, 'refreshSession'])->name('api.refresh-session');

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
            Route::put('/{id}/cancel', [OrderController::class, 'cancel'])->name('api.orders.cancel');
            Route::get('/{id}/check-status', [OrderController::class, 'checkPaymentStatus'])->name('api.orders.check-status');
            Route::put('/{id}/mark-finished', [OrderController::class, 'markAsFinished'])->name('api.orders.mark-finished');
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

// Midtrans Callback Routes (No authentication required)
Route::prefix('midtrans')->group(function () {
    Route::post('/notification', [MidtransCallbackController::class, 'notification'])->name('api.midtrans.notification');
    Route::get('/finish', [MidtransCallbackController::class, 'finish'])->name('api.midtrans.finish');
    Route::get('/error', [MidtransCallbackController::class, 'error'])->name('api.midtrans.error');
    Route::get('/unfinish', [MidtransCallbackController::class, 'unfinish'])->name('api.midtrans.unfinish');
    
    // Test route untuk testing dari dashboard Midtrans
    Route::post('/test', function (Request $request) {
        \Log::info('Midtrans Test Notification Received:', $request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Test notification received successfully',
            'timestamp' => now(),
            'data' => $request->all()
        ]);
    })->name('api.midtrans.test');
    
    // Debug endpoint untuk testing callback
    Route::post('/debug-callback', function (Request $request) {
        \Log::info('DEBUG: Callback received', $request->all());
        
        // Simulate updating an order status
        if ($request->has('order_id') && $request->has('transaction_status')) {
            $orderId = \App\Http\Controllers\Api\MidtransCallbackController::extractOrderIdStatic($request->order_id);
            if ($orderId) {
                $order = \App\Models\Order::find($orderId);
                if ($order) {
                    $oldStatus = $order->status;
                    $newStatus = $request->transaction_status == 'settlement' ? 'finished' : 'pending';
                    $order->update(['status' => $newStatus]);
                    
                    \Log::info('DEBUG: Order status updated', [
                        'order_id' => $orderId,
                        'old_status' => $oldStatus,
                        'new_status' => $newStatus
                    ]);
                }
            }
        }
        
        return response()->json([
            'status' => 'success',
            'message' => 'Debug callback processed',
            'received_data' => $request->all()
        ]);
    })->name('api.midtrans.debug');
    
    // GET route untuk testing di browser
    Route::get('/status', function () {
        return response()->json([
            'status' => 'ok',
            'message' => 'Midtrans callback endpoint is working',
            'timestamp' => now(),
            'routes' => [
                'POST /api/midtrans/notification' => 'Production callback',
                'POST /api/midtrans/test' => 'Test callback',
                'GET /api/midtrans/status' => 'Status check (this route)'
            ]
        ]);
    })->name('api.midtrans.status');
});
