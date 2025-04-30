<?php

use App\Http\Controllers\MyLearningController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VueQuizController;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
// ->middleware(['auth', 'verified'])

Route::get('test', function () {
    return Inertia::render('test');
})->name('test');

// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/modulevue', function () {
    return Inertia::render('settings/VueModules/ModuleVue');
});

Route::get('/modulelara', function () {
    return Inertia::render('settings/LaraModules/ModuleLara');
});

Route::get('/mylearning', function () {
    return Inertia::render('settings/MyLearning');
})->name('mylearning');

Route::get('/quizvue', [VueQuizController::class, 'index'])->name('quizvue');

Route::get('/quizlara', function () {
    return Inertia::render('settings/LaraModules/QuizLara');
})->name('quiz.lara');


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
