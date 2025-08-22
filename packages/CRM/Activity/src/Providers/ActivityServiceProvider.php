<?php

namespace CRM\Activity\Providers;

use Illuminate\Support\ServiceProvider;
use CRM\Activity\Models\Activity;
use CRM\Activity\Models\ActivityType;
use CRM\Activity\Repositories\ActivityRepository;
use CRM\Activity\Repositories\ActivityTypeRepository;

class ActivityServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/activity.php',
            'crm.activity'
        );

        // Register repositories
        $this->app->bind(ActivityRepository::class, function ($app) {
            return new ActivityRepository(new Activity());
        });

        $this->app->bind(ActivityTypeRepository::class, function ($app) {
            return new ActivityTypeRepository(new ActivityType());
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'activity');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/activity.php' => config_path('crm/activity.php'),
            ], 'crm-activity-config');

            $this->publishes([
                __DIR__ . '/../../resources/views' => resource_path('views/vendor/activity'),
            ], 'crm-activity-views');

            $this->publishes([
                __DIR__ . '/../../database/migrations' => database_path('migrations'),
            ], 'crm-activity-migrations');
        }
    }
}
