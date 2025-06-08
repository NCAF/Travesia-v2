<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SessionController extends Controller
{
    /**
     * Get fresh CSRF token
     *
     * @return JsonResponse
     */
    public function getCsrfToken(): JsonResponse
    {
        return response()->json([
            'csrf_token' => csrf_token(),
            'timestamp' => now()->toISOString(),
            'expires_in' => config('session.lifetime') * 60 // in seconds
        ]);
    }

    /**
     * Keep session alive
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function keepAlive(Request $request): JsonResponse
    {
        // Touch the session to keep it alive
        $request->session()->regenerate(false);
        
        return response()->json([
            'success' => true,
            'message' => 'Session refreshed',
            'timestamp' => now()->toISOString(),
            'session_lifetime' => config('session.lifetime')
        ]);
    }

    /**
     * Get session info
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getSessionInfo(Request $request): JsonResponse
    {
        return response()->json([
            'session_id' => $request->session()->getId(),
            'csrf_token' => csrf_token(),
            'is_authenticated' => auth()->check(),
            'user_id' => auth()->id(),
            'session_lifetime' => config('session.lifetime'),
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Refresh session and return new token
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function refreshSession(Request $request): JsonResponse
    {
        // Regenerate session ID for security
        $request->session()->regenerate();
        
        return response()->json([
            'success' => true,
            'csrf_token' => csrf_token(),
            'session_id' => $request->session()->getId(),
            'message' => 'Session and CSRF token refreshed',
            'timestamp' => now()->toISOString()
        ]);
    }
} 