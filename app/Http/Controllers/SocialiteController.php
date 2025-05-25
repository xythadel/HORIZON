<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SocialiteController extends Controller
{
    // Redirect to Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Handle Google callback
    public function handleGoogleCallback()
{
    try {
        $googleUser = Socialite::driver('google')->user();

        // Find or create user
        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName() ?? $googleUser->getNickname(),
                'password' => bcrypt(Str::random(16)),
                'email_verified_at' => now(),
            ]
        );

        Auth::login($user);

        if (Auth::check()) {
            logger('User is logged in:', [Auth::user()]);
            return redirect('/dashboard');
        }

        logger('Auth::check() failed after login');
        return redirect('/')->with('error', 'Login failed');

    } catch (\Exception $e) {
        return redirect('/')->with('error', 'Authentication failed');
    }
}
}