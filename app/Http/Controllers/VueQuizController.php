<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use Inertia\Inertia;

class VueQuizController extends Controller
{
    public function index()
    {
        $questions = Quiz::all(); // Fetch all quiz questions from the database

        return Inertia::render('VueModules/QuizVue', [
            'questions' => $questions,
        ]);
    }
}
