<?php

use App\Http\Controllers\MyLearningController;
use App\Http\Controllers\CourseController;
use App\Models\Course;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VueQuizController;
use Illuminate\Types\Relations\Role;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\SocialiteController;

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


//Route::middleware(['auth'])->group(function () {
    //Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
//});   

//Route::resource('courses', CourseController::class);
// routes/web.php

//Route::get('/admin', function () {
    //return view('admin.index'); // This should match your Blade file
//});

Route::get('/admin', function () {
    return Inertia::render('Admin/AdminDashboard', [
        'courses' => Course::all()
    ]);
});

//For google login 
Route::get('/auth/google', [SocialiteController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);

Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/admin/AdminDashboard', fn () => Inertia::render('Admin/AdminDashboard', [
        'courses' => Course::all()  
    ]));
});

Route::middleware(['auth', 'isUser'])->group(function () {
    Route::get('/dashboard', fn () => Inertia::render('Dashboard'));
});


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';