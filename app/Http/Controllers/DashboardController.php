<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::get();
        return Inertia::render('Dashboard', [
            'courses' => $courses
        ]);
    }
}
