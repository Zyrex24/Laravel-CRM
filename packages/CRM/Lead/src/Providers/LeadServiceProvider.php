<?php

namespace CRM\Lead\Providers;

use Illuminate\Support\ServiceProvider;
use CRM\Lead\Models\Lead;
use CRM\Lead\Models\Pipeline;
use CRM\Lead\Models\Stage;
use CRM\Lead\Repositories\LeadRepository;
use CRM\Lead\Repositories\PipelineRepository;
use CRM\Lead\Repositories\StageRepository;

class LeadServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/lead.php',
            'crm.lead'
        );

        // Register repositories
        $this->app->bind(LeadRepository::class, function ($app) {
            return new LeadRepository(new Lead());
        });

        $this->app->bind(PipelineRepository::class, function ($app) {
            return new PipelineRepository(new Pipeline());
        });

        $this->app->bind(StageRepository::class, function ($app) {
            return new StageRepository(new Stage());
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'lead');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/lead.php' => config_path('crm/lead.php'),
            ], 'crm-lead-config');

            $this->publishes([
                __DIR__ . '/../../resources/views' => resource_path('views/vendor/lead'),
            ], 'crm-lead-views');

            $this->publishes([
                __DIR__ . '/../../database/migrations' => database_path('migrations'),
            ], 'crm-lead-migrations');
        }
    }
}
