<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VueQuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::with('questions.options')->get();
        
        return Inertia::render('settings/VueModules/QuizVue', [
            'quizzes' => $quizzes
        ]);
    }
    
    public function show($id)
    {
        $quiz = Quiz::with('questions.options')->findOrFail($id);
        
        return Inertia::render('QuizVue', [
            'quiz' => $quiz
        ]);
    }
}