<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
// ->middleware(['auth', 'verified'])

Route::get('test', function () {
    return Inertia::render('test');
})->name('test');

Route::get('mylearning', function () {
    return Inertia::render('MyLearning');
})->name('mylearning');

// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
