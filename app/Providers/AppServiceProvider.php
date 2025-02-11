<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;

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
    public function boot()
    {  
        VerifyEmail::createUrlUsing(function ($notifiable) {
            return url("/email/verify/{$notifiable->getKey()}?hash=" . sha1($notifiable->getEmailForVerification()));
        });
    }
}
