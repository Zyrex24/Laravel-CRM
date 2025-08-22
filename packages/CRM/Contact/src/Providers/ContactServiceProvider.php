<?php

namespace CRM\Contact\Providers;

use Illuminate\Support\ServiceProvider;
use CRM\Contact\Models\Person;
use CRM\Contact\Models\Organization;
use CRM\Contact\Repositories\PersonRepository;
use CRM\Contact\Repositories\OrganizationRepository;

class ContactServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/contact.php',
            'crm.contact'
        );

        // Register repositories
        $this->app->bind(PersonRepository::class, function ($app) {
            return new PersonRepository(new Person());
        });

        $this->app->bind(OrganizationRepository::class, function ($app) {
            return new OrganizationRepository(new Organization());
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'contact');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/contact.php' => config_path('crm/contact.php'),
            ], 'crm-contact-config');

            $this->publishes([
                __DIR__ . '/../../resources/views' => resource_path('views/vendor/contact'),
            ], 'crm-contact-views');

            $this->publishes([
                __DIR__ . '/../../database/migrations' => database_path('migrations'),
            ], 'crm-contact-migrations');
        }
    }
}
