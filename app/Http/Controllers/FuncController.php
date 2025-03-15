<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Laravel\Sanctum\PersonalAccessToken;

class FuncController extends Controller
{
    public static function check_user()
    {
        if (isset($_COOKIE['travesia_token']) && !empty($_COOKIE['travesia_token'])) {
            $token = PersonalAccessToken::findToken($_COOKIE['travesia_token']);
            if ($token) {
                $user = $token->tokenable;
                if ($user->role == 'seller') {
                    return redirect()->route('seller.main');
                } else {
                    return redirect()->route('home');
                }
            }
        }
        return null;
    }

    public static function get_profile()
    {
        if (isset($_COOKIE['travesia_token']) && !empty($_COOKIE['travesia_token'])) {
            $token = PersonalAccessToken::findToken($_COOKIE['travesia_token']);

            return $token->tokenable;
        } else {
            abort(Response::HTTP_NOT_FOUND);
        }
    }

    public static function get_profile_without_abort()
    {
        if (isset($_COOKIE['travesia_token']) && !empty($_COOKIE['travesia_token'])) {
            $token = PersonalAccessToken::findToken($_COOKIE['travesia_token']);
            if ($token) {
                return $token->tokenable;
            }
        }
        return '';
    }

    public static function set_access_role($role)
    {
        if (isset($_COOKIE['travesia_token']) && !empty($_COOKIE['travesia_token'])) {
            $token = PersonalAccessToken::findToken($_COOKIE['travesia_token']);
            if ($token) {
                $user = $token->tokenable;

                if ($user->role != $role) {
                    abort(Response::HTTP_UNAUTHORIZED);
                }
            } else {
                return redirect()->route('login');
            }
        } else {
            return redirect()->route('login');
        }
        return null;
    }
}
