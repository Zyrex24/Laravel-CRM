<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Lead Management Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration settings for the lead management
    | system including pipelines, stages, scoring, and automation.
    |
    */

    'default_pipeline' => env('CRM_DEFAULT_PIPELINE', 'sales'),
    'default_stage' => env('CRM_DEFAULT_STAGE', 'new'),
    'default_currency' => env('CRM_DEFAULT_CURRENCY', 'USD'),

    'scoring' => [
        'enabled' => true,
        'max_score' => 100,
        'factors' => [
            'email_opens' => 5,
            'email_clicks' => 10,
            'website_visits' => 8,
            'form_submissions' => 15,
            'phone_calls' => 20,
            'meetings' => 25,
            'proposal_views' => 30,
        ],
        'decay_enabled' => true,
        'decay_days' => 30,
        'decay_percentage' => 10,
    ],

    'rotten_leads' => [
        'enabled' => true,
        'days_threshold' => 30,
        'auto_notification' => true,
        'notification_frequency' => 'weekly', // daily, weekly, monthly
    ],

    'assignment' => [
        'auto_assign' => true,
        'assignment_strategy' => 'round_robin', // round_robin, random, load_balanced
        'respect_working_hours' => true,
        'working_hours' => [
            'start' => '09:00',
            'end' => '17:00',
            'timezone' => 'UTC',
        ],
    ],

    'conversion' => [
        'auto_convert_to_contact' => false,
        'auto_convert_to_deal' => false,
        'require_approval' => true,
        'conversion_stages' => ['qualified', 'proposal', 'negotiation'],
    ],

    'sources' => [
        'website' => 'Website',
        'social_media' => 'Social Media',
        'email_campaign' => 'Email Campaign',
        'referral' => 'Referral',
        'cold_call' => 'Cold Call',
        'trade_show' => 'Trade Show',
        'advertisement' => 'Advertisement',
        'partner' => 'Partner',
        'other' => 'Other',
    ],

    'types' => [
        'individual' => 'Individual',
        'business' => 'Business',
        'enterprise' => 'Enterprise',
        'government' => 'Government',
        'non_profit' => 'Non-Profit',
    ],

    'priorities' => [
        'low' => 'Low',
        'medium' => 'Medium',
        'high' => 'High',
        'urgent' => 'Urgent',
    ],

    'pipeline' => [
        'default_stages' => [
            'new' => ['name' => 'New', 'probability' => 10, 'color' => '#6B7280'],
            'contacted' => ['name' => 'Contacted', 'probability' => 20, 'color' => '#3B82F6'],
            'qualified' => ['name' => 'Qualified', 'probability' => 40, 'color' => '#10B981'],
            'proposal' => ['name' => 'Proposal', 'probability' => 60, 'color' => '#F59E0B'],
            'negotiation' => ['name' => 'Negotiation', 'probability' => 80, 'color' => '#EF4444'],
            'closed_won' => ['name' => 'Closed Won', 'probability' => 100, 'color' => '#059669'],
            'closed_lost' => ['name' => 'Closed Lost', 'probability' => 0, 'color' => '#DC2626'],
        ],
        'kanban_enabled' => true,
        'drag_drop_enabled' => true,
        'stage_duration_tracking' => true,
    ],

    'notifications' => [
        'new_lead_assigned' => true,
        'lead_stage_changed' => true,
        'lead_score_threshold' => 80,
        'rotten_lead_alert' => true,
        'follow_up_reminders' => true,
    ],

    'import' => [
        'max_file_size' => 10240, // KB
        'allowed_formats' => ['csv', 'xlsx', 'xls'],
        'batch_size' => 100,
        'duplicate_handling' => 'skip', // skip, update, create_new
        'required_fields' => ['name', 'email'],
    ],

    'export' => [
        'default_format' => 'csv',
        'available_formats' => ['csv', 'xlsx', 'pdf'],
        'max_records' => 10000,
        'include_activities' => true,
    ],
];
