<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Inertia\Inertia;

class MyLearningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('MyLearning');
    }
}
