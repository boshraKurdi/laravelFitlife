<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
        // return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $google_user = Socialite::driver('google')->stateless()->user();

        $exitUser = User::where('email', $google_user->email)->first();
        if ($exitUser) {
            $token = Auth::login($exitUser);
            return response()->json([
                'user' => $exitUser,
                'access_token' => $token,
                'status' => 'login',
                'token_type' => 'Bearer',
            ]);
        } else {
            $user = User::where('google_id', $google_user->id)->first();
            if (!$user) {
                $user = User::Create([
                    'google_id' => $google_user->id,
                    'name' => $google_user->name,
                    'email' => $google_user->email,
                    'google_token' => $google_user->token,

                ]);
                $token = Auth::login($user);
                return response()->json([
                    'user' => $user,
                    'status' => 'register',
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ]);
            } else {
                $token = Auth::login($user);
                return response()->json([
                    'user' => $user,
                    'status' => 'register',
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ]);
            }
        }
    }
}
