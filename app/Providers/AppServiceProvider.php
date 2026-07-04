<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ZScoreService;
use Illuminate\Pagination\Paginator; // Move this to the top!

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ZScoreService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Note: You usually only need one of these depending on your CSS framework.
        Paginator::useBootstrapFive(); 
        
        // If you are using Tailwind instead, comment out the Bootstrap line above and use:
        // Paginator::defaultView('vendor.pagination.tailwind');
    }
}