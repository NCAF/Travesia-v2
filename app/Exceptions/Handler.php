<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $e)
    {
        // Handle CSRF Token Mismatch Exception
        if ($e instanceof TokenMismatchException) {
            // If it's an AJAX request
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Sesi Anda telah berakhir. Silakan refresh halaman dan coba lagi.',
                    'csrf_token' => csrf_token(),
                    'type' => 'token_mismatch',
                    'reload' => true
                ], 419);
            }

            // If it's a regular request
            return redirect()->back()
                ->withInput($request->except(['_token', 'password', 'password_confirmation']))
                ->with('error', 'Sesi Anda telah berakhir. Silakan coba lagi.')
                ->with('show_csrf_warning', true);
        }

        return parent::render($request, $e);
    }
}
