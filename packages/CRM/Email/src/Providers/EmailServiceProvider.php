<?php

namespace CRM\Email\Providers;

use CRM\Email\Contracts\EmailRepositoryInterface;
use CRM\Email\Contracts\EmailTemplateRepositoryInterface;
use CRM\Email\Repositories\EmailRepository;
use CRM\Email\Repositories\EmailTemplateRepository;
use Illuminate\Support\ServiceProvider;

class EmailServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Merge package configuration
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/email.php',
            'crm.email'
        );

        // Register repositories
        $this->app->bind(EmailRepositoryInterface::class, EmailRepository::class);
        $this->app->bind(EmailTemplateRepositoryInterface::class, EmailTemplateRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'crm-email');

        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');

        // Publish configuration
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/email.php' => config_path('crm/email.php'),
            ], 'crm-email-config');

            // Publish views
            $this->publishes([
                __DIR__ . '/../../resources/views' => resource_path('views/vendor/crm-email'),
            ], 'crm-email-views');

            // Publish assets
            $this->publishes([
                __DIR__ . '/../../resources/assets' => public_path('vendor/crm-email'),
            ], 'crm-email-assets');
        }
    }
}
