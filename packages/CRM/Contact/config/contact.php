<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Contact Management Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration settings for the contact management
    | system including persons, organizations, and relationships.
    |
    */

    'default_country' => env('CRM_DEFAULT_COUNTRY', 'US'),
    'default_timezone' => env('CRM_DEFAULT_TIMEZONE', 'UTC'),
    'default_currency' => env('CRM_DEFAULT_CURRENCY', 'USD'),

    'person' => [
        'name_format' => 'first_last', // first_last, last_first, full
        'required_fields' => ['first_name', 'email'],
        'unique_fields' => ['email'],
        'avatar_enabled' => true,
        'social_profiles_enabled' => true,
    ],

    'organization' => [
        'required_fields' => ['name'],
        'unique_fields' => ['name', 'email'],
        'logo_enabled' => true,
        'industry_enabled' => true,
        'size_categories' => [
            'startup' => '1-10 employees',
            'small' => '11-50 employees',
            'medium' => '51-200 employees',
            'large' => '201-1000 employees',
            'enterprise' => '1000+ employees',
        ],
    ],

    'relationships' => [
        'person_organization_roles' => [
            'employee',
            'manager',
            'director',
            'ceo',
            'cto',
            'cfo',
            'consultant',
            'contractor',
            'partner',
            'other',
        ],
    ],

    'import' => [
        'max_file_size' => 10240, // KB
        'allowed_formats' => ['csv', 'xlsx', 'xls'],
        'batch_size' => 100,
        'duplicate_handling' => 'skip', // skip, update, create_new
    ],

    'export' => [
        'default_format' => 'csv',
        'available_formats' => ['csv', 'xlsx', 'pdf'],
        'max_records' => 10000,
    ],

    'segmentation' => [
        'enabled' => true,
        'auto_segments' => [
            'recent_contacts' => 'Contacts added in last 30 days',
            'vip_contacts' => 'Contacts marked as VIP',
            'inactive_contacts' => 'Contacts with no activity in 90 days',
        ],
    ],

    'communication' => [
        'track_email_opens' => true,
        'track_email_clicks' => true,
        'auto_log_emails' => true,
        'auto_log_calls' => true,
    ],
];
