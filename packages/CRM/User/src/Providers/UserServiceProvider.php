<?php

namespace CRM\User\Providers;

use Illuminate\Support\ServiceProvider;
use CRM\User\Models\User;
use CRM\User\Models\Role;
use CRM\User\Models\Permission;
use CRM\User\Repositories\UserRepository;
use CRM\User\Repositories\RoleRepository;
use CRM\User\Repositories\PermissionRepository;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/user.php',
            'crm.user'
        );

        // Register repositories
        $this->app->bind(UserRepository::class, function ($app) {
            return new UserRepository(new User());
        });

        $this->app->bind(RoleRepository::class, function ($app) {
            return new RoleRepository(new Role());
        });

        $this->app->bind(PermissionRepository::class, function ($app) {
            return new PermissionRepository(new Permission());
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'user');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/user.php' => config_path('crm/user.php'),
            ], 'crm-user-config');

            $this->publishes([
                __DIR__ . '/../../resources/views' => resource_path('views/vendor/user'),
            ], 'crm-user-views');

            $this->publishes([
                __DIR__ . '/../../database/migrations' => database_path('migrations'),
            ], 'crm-user-migrations');
        }
    }
}
