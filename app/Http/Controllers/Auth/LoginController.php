<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;


class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     * You can remove or comment this line, since we’ll override the method.
     */
    // protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Redirect users based on their role after login.
     */
    protected function redirectTo()
    {
       $role = Auth::user()->role;

    if ($role === 'admin') {
        return '/admin'; // Make sure this matches the route in Vue/Inertia
    }
    return '/dashboard';
    }
public function login(Request $request): RedirectResponse
{
    // Validate credentials
    $credentials = $request->only('email', 'password');

    // Attempt login
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        // Get fresh user with latest role
        $user = Auth::user();
        $role = $user->role;

        // Redirect based on role
        if ($role === 'admin') {
            return redirect('/admin');
        }

        return redirect('/dashboard');
    }

    // If login fails
    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
}


    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out']);
    }
}