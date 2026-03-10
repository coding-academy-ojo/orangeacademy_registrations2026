<?php

namespace App\Providers;

use App\Mail\MailtrapApiTransport;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Mail;

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
        Paginator::useBootstrapFive();

        Mail::extend('mailtrap-api', function () {
            $apiKey = config('mail.mailers.mailtrap-api.api_key');
            return new MailtrapApiTransport($apiKey);
        });
    }
}
