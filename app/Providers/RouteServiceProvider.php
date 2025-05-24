<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Optional: Explicit bindings
        Route::model('course', \App\Models\Course::class);
        Route::model('topic', \App\Models\Topic::class);
        Route::model('user', \App\Models\User::class);
    }
    public function map(): void
    {   
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }

    protected function mapWebRoutes(): void
    {
        Route::middleware(['web'])
            ->group(base_path('routes/web.php'));
    }

    protected function mapApiRoutes(): void
    {
        Route::prefix('api')
            ->middleware(['api'])
            ->group(base_path('routes/api.php'));
    }
}
