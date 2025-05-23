<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('AdminDashboard', [
            'users' => User::all(),
            'courses' => Course::with('topics')->get(),
            'topics' => Topic::all(),
            'authUser' => Auth::user(),
        ]);
    }

    public function index()
    {
        return inertia('Admin/AdminDashboard'); // Or whatever Inertia page you want to show
    }

    public function getAllUsers()
    {
        return response()->json(User::all());
    }
}
