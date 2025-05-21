<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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
    //protected function redirectTo()
    //{
      // $role = Auth::user()->role;

    //if ($role === 'admin') {
        //return '/Admin/AdminDashboard';
    //}
    //return '/dashboard';
    //}

    protected function authenticated(Request $request, $user)
{
    if ($user->role === 'admin') {
        return redirect('/Admin/AdminDashboard');
    }

    return redirect('/dashboard');
}
}
