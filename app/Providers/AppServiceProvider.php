<?php

namespace App\Providers;

use App\Models\Expense;
use App\Models\SiteSetting;
use App\Models\User;
use App\Observers\AuditObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        Expense::observe(AuditObserver::class);
        SiteSetting::observe(AuditObserver::class);
        User::observe(AuditObserver::class);

        View::composer('*', function ($view): void {
            $view->with('siteSetting', SiteSetting::current());
        });
    }
}
