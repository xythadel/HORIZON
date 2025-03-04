<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('horizon', function () {
    return Inertia::render('Horizon');
})->middleware(['auth', 'verified'])->name('horizon');

Route::get('test', function () {
    return Inertia::render('test');
})->name('test');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
