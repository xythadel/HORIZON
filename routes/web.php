<?php

use App\Http\Controllers\MyLearningController;
use App\Http\Controllers\CourseController;
use App\Models\Course;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VueQuizController;
use App\Http\Controllers\AdminController;
use Illuminate\Types\Relations\Role;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\Auth\LoginController;

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

//Admin routes
Route::middleware(['auth'])->group(function () {
    Route::get('/Admin/AdminDashboard', [AdminController::class, 'index'])->name('admin');

    Route::middleware('auth')->group(function () {
    Route::get('/complete-registration', [App\Http\Controllers\Auth\CompleteRegistrationController::class, 'showForm'])->name('complete.registration');
    Route::post('/complete-registration', [App\Http\Controllers\Auth\CompleteRegistrationController::class, 'submitForm']);
});


    //For google login 
    Route::get('/auth/google', [SocialiteController::class, 'redirectToGoogle']);
    Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);

}); // <-- Close the middleware group

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';