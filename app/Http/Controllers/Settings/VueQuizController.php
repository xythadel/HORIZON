<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class VueQuizController extends Controller
{
    public function index()
    {
        $questions = [
            [
                'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                'options' => ['Option A', 'Option B', 'Option C', 'Option D'],
            ],
            [
                'text' => 'Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'options' => ['Option A', 'Option B', 'Option C', 'Option D'],
            ],
            [
                'text' => 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.',
                'options' => ['Option A', 'Option B', 'Option C', 'Option D'],
            ],
            [
                'text' => 'Duis aute irure dolor in reprehenderit in voluptate velit.',
                'options' => ['Option A', 'Option B', 'Option C', 'Option D'],
            ],
            [
                'text' => 'Excepteur sint occaecat cupidatat non proident.',
                'options' => ['Option A', 'Option B', 'Option C', 'Option D'],
            ],
        ];

        return Inertia::render('VueModules/ModuleVueQuiz', [
            'questions' => $questions,
        ]);
    }
}
// Compare this snippet from app/Http/Controllers/VueQuizController.php:
// <?php