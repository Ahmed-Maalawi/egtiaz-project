<?php

namespace App\Providers;

use App\Models\OfficialLeave;
use App\Observers\OfficialLeaveObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // dd(session()->get('locale'));
        if (session()->has('locale')) {
            app()->setLocale(session()->get('locale'));
        }
        OfficialLeave::observe(OfficialLeaveObserver::class);

        Paginator::useBootstrap();
    }
}
