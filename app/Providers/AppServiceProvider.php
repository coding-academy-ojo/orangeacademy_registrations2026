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

        // Admin Roles and Permissions
        \Illuminate\Support\Facades\Gate::define('manage-admins', fn($admin) => $admin->isSuperAdmin());
        \Illuminate\Support\Facades\Gate::define('full-access', fn($admin) => $admin->hasFullAccess());
        \Illuminate\Support\Facades\Gate::define('manage-interviews-assessments', fn($admin) => $admin->hasFullAccess() || $admin->isJobCoach());
        \Illuminate\Support\Facades\Gate::define('manage-operations', fn($admin) => $admin->hasFullAccess() || $admin->isCoordinator());
    }
}
