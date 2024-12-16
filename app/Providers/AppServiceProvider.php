<?php

namespace App\Providers;
use Illuminate\Support\Facades\RateLimiter;
// use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
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
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60); // Example: Limit to 60 requests per minute
        });
    }
}