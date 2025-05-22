<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\User;

class CompleteRegistrationController extends Controller
{
    public function showForm(): View
    {
        return view('auth.complete-registration'); // We'll create this blade or Vue route next
    }public function submitForm(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'birthday' => ['required', 'date'],
    ]);

    // Check if user is authenticated
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Please log in first.');
    }

    // Get the authenticated user
    $user = User::find(Auth::id());

    if (!$user) {
        return redirect()->route('login')->with('error', 'User not found.');
    }

    // Update user info
    $user->update([
        'name' => $request->name,
        'birthday' => $request->birthday,
    ]);

    // Redirect based on role or default
    return redirect()->route($user->role === 'admin' ? 'admin.dashboard' : 'user.dashboard');
}
}