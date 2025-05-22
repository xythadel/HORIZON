<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
public function handleGoogleCallback()
{
    $user = Socialite::driver('google')->user();

    $existingUser = User::where('email', $user->getEmail())->first();

    if ($existingUser) {
        Auth::login($existingUser);

        return redirect('/'); // Your Vue app entry point
    }

    // If user doesn't exist, create one or redirect to registration step
    $newUser = User::create([
        'name' => $user->getName(),
        'email' => $user->getEmail(),
        // You may want to store google_id, etc.
    ]);

    Auth::login($newUser);

    return redirect('/'); // Or wherever your Vue app handles logged-in state
}
}
