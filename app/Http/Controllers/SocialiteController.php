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
                    'password' => bcrypt(Str::random(16)), // Random password for safety
                    'email_verified_at' => now(),
                ]
            );

            Auth::login($user);

            return redirect('/dashboard'); // or wherever you want to redirect
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Authentication failed');
        }
    }
}
