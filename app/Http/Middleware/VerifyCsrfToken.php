<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Closure;
use Illuminate\Session\TokenMismatchException;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/midtrans/notification',
        'api/midtrans/*',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            return parent::handle($request, $next);
        } catch (TokenMismatchException $e) {
            // Jika AJAX request, kembalikan JSON response
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Sesi Anda telah berakhir. Silakan refresh halaman dan coba lagi.',
                    'csrf_token' => csrf_token(),
                    'reload' => true
                ], 419);
            }

            // Jika regular request, redirect dengan error message
            return redirect()->back()
                ->withInput($request->except(['_token', 'password', 'password_confirmation']))
                ->with('error', 'Sesi Anda telah berakhir. Silakan coba lagi.');
        }
    }
}
