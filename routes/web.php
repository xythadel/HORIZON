<?php

use App\Http\Controllers\MyLearningController;
use App\Http\Controllers\CourseController;
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

Route::get('/quizzes', [VueQuizController::class, 'index'])->name('quizzes.index');
Route::get('/quizzes/{id}', [VueQuizController::class, 'show'])->name('quizzes.show');

Route::get('/quizlara', function () {
    return Inertia::render('settings/LaraModules/QuizLara');
})->name('quiz.lara');


Route::post('/logout', function () {
    \Illuminate\Support\Facades\Auth::logout();
    return redirect('Welcome'); // Or '/signup' if you prefer
});

Route::get('/admindashboard', function () {
    return Inertia::render('Admin/AdminDashboard');
})->name('admin.dashboard');


//Route::middleware(['auth'])->group(function () {
    //Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
//});   

//Route::resource('courses', CourseController::class);
// routes/web.php

Route::get('/courses/json', [CourseController::class, 'index']);
Route::post('/courses/json', [CourseController::class, 'store']);
Route::put('/courses/json/{course}', [CourseController::class, 'update']);
Route::delete('/courses/json/{course}', [CourseController::class, 'destroy']);




require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';