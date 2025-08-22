<?php

namespace CRM\DataGrid\Providers;

use Illuminate\Support\ServiceProvider;
use CRM\DataGrid\DataGrid;
use CRM\DataGrid\Contracts\DataGridInterface;

class DataGridServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/datagrid.php',
            'crm.datagrid'
        );

        $this->app->bind(DataGridInterface::class, DataGrid::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'datagrid');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/datagrid.php' => config_path('crm/datagrid.php'),
            ], 'crm-datagrid-config');

            $this->publishes([
                __DIR__ . '/../../resources/views' => resource_path('views/vendor/datagrid'),
            ], 'crm-datagrid-views');

            $this->publishes([
                __DIR__ . '/../../database/migrations' => database_path('migrations'),
            ], 'crm-datagrid-migrations');
        }
    }
}
