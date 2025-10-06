<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register helper classes as singletons if needed
        $this->app->singleton('AesEncryptionHelper', function ($app) {
            return new \App\Helpers\AesEncryptionHelper();
        });

        $this->app->singleton('MessageHelper', function ($app) {
            return new \App\Helpers\MessageHelper();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load helper files
        $helperFiles = [
            app_path('Helpers/AesEncryptionHelper.php'),
            app_path('Helpers/MessageHelper.php'),
        ];

        foreach ($helperFiles as $file) {
            if (file_exists($file)) {
                require_once $file;
            }
        }
    }
}