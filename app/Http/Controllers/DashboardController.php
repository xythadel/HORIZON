<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    // Temporarily disable authentication for development
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index()
    {
        $user = Auth::user(); // Might be null during development if not logged in
        $courses = Course::all();

           $progress = 0;

        return Inertia::render('Dashboard', [
            'user' => $user,
            'isAdmin' => optional($user)->role === 'admin', // safe check
            'courses' => $courses,
            'progress' => $progress,
        ]);
    }
}
