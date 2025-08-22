<?php

return [
    /*
    |--------------------------------------------------------------------------
    | CRM Core Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the core configuration settings for the CRM system.
    | These settings control various aspects of the application behavior.
    |
    */

    'name' => env('CRM_NAME', 'Laravel CRM'),
    'version' => '1.0.0',
    'timezone' => env('CRM_TIMEZONE', 'UTC'),
    'locale' => env('CRM_LOCALE', 'en'),

    /*
    |--------------------------------------------------------------------------
    | Database Configuration
    |--------------------------------------------------------------------------
    */
    'database' => [
        'prefix' => env('CRM_DB_PREFIX', 'crm_'),
        'connection' => env('CRM_DB_CONNECTION', 'mysql'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'ttl' => env('CRM_CACHE_TTL', 3600),
        'prefix' => env('CRM_CACHE_PREFIX', 'crm_'),
    ],

    /*
    |--------------------------------------------------------------------------
    | File Storage Configuration
    |--------------------------------------------------------------------------
    */
    'storage' => [
        'disk' => env('CRM_STORAGE_DISK', 'local'),
        'path' => env('CRM_STORAGE_PATH', 'crm'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    */
    'security' => [
        'password_min_length' => 8,
        'session_timeout' => 120, // minutes
        'max_login_attempts' => 5,
    ],

    /*
    |--------------------------------------------------------------------------
    | Feature Flags
    |--------------------------------------------------------------------------
    */
    'features' => [
        'api_enabled' => env('CRM_API_ENABLED', true),
        'webhooks_enabled' => env('CRM_WEBHOOKS_ENABLED', true),
        'email_integration' => env('CRM_EMAIL_INTEGRATION', true),
        'automation' => env('CRM_AUTOMATION_ENABLED', true),
        'analytics' => env('CRM_ANALYTICS_ENABLED', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Pagination Configuration
    |--------------------------------------------------------------------------
    */
    'pagination' => [
        'per_page' => env('CRM_PAGINATION_PER_PAGE', 25),
        'max_per_page' => env('CRM_PAGINATION_MAX_PER_PAGE', 100),
    ],
];
