<?php

namespace App\Providers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;
use Symfony\Component\Mailer\Transport;

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
        Mail::extend('mailtrap', function (array $config) {
            $dsn = $config['dsn'] ?? '';
            if (! is_string($dsn) || $dsn === '') {
                throw new InvalidArgumentException(
                    'The mailtrap mailer requires MAIL_MAILTRAP_DSN (see config/mail.php).'
                );
            }

            // Omit dispatcher: Laravel's Illuminate\Events\Dispatcher is not PSR-14; Symfony defaults are fine for Mailtrap.
            return Transport::fromDsn($dsn);
        });
    }
}
