<?php

return [
    /*
    |--------------------------------------------------------------------------
    | User Management Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration settings for the user management
    | system including authentication, roles, and permissions.
    |
    */

    'authentication' => [
        'guard' => 'web',
        'password_reset_expiry' => 60, // minutes
        'email_verification_required' => true,
        'two_factor_enabled' => false,
    ],

    'roles' => [
        'default_role' => 'user',
        'super_admin_role' => 'super_admin',
        'admin_role' => 'admin',
    ],

    'permissions' => [
        'cache_enabled' => true,
        'cache_ttl' => 3600, // seconds
    ],

    'profile' => [
        'avatar_disk' => 'public',
        'avatar_path' => 'avatars',
        'max_avatar_size' => 2048, // KB
        'allowed_avatar_types' => ['jpg', 'jpeg', 'png', 'gif'],
    ],

    'teams' => [
        'enabled' => true,
        'default_team' => 'default',
        'auto_assign_leads' => true,
        'lead_assignment_strategy' => 'round_robin', // round_robin, random, manual
    ],

    'activity_tracking' => [
        'enabled' => true,
        'track_logins' => true,
        'track_actions' => true,
        'retention_days' => 90,
    ],
];
