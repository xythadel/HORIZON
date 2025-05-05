<?php

// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Only allow authenticated users to access
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user(); // Now guaranteed not null
        $courses = Course::all();

        return Inertia::render('Dashboard', [
            'user' => $user,
            'isAdmin' => $user->role === 'admin',
            'courses' => $courses,
        ]);
    }
}


