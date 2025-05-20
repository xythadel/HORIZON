<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
            // Check if the user is logged in and has the 'user' role
        if ($request->user() && $request->user()->hasRole('user')) {
            return $next($request);
        }
        
        // Redirect if not a user
        return redirect('/')->with('error', 'You do not have user access.');
    }
}
