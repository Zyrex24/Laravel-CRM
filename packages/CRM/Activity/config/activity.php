<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Activity Management Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration settings for the activity management
    | system including types, scheduling, reminders, and analytics.
    |
    */

    'default_timezone' => env('CRM_DEFAULT_TIMEZONE', 'UTC'),
    'default_duration' => 30, // minutes
    'working_hours' => [
        'start' => '09:00',
        'end' => '17:00',
        'timezone' => 'UTC',
    ],

    'types' => [
        'call' => [
            'name' => 'Phone Call',
            'icon' => 'phone',
            'color' => '#10B981',
            'default_duration' => 15,
            'requires_outcome' => true,
        ],
        'meeting' => [
            'name' => 'Meeting',
            'icon' => 'users',
            'color' => '#3B82F6',
            'default_duration' => 60,
            'requires_outcome' => true,
        ],
        'email' => [
            'name' => 'Email',
            'icon' => 'mail',
            'color' => '#6366F1',
            'default_duration' => 5,
            'requires_outcome' => false,
        ],
        'task' => [
            'name' => 'Task',
            'icon' => 'check-square',
            'color' => '#F59E0B',
            'default_duration' => 30,
            'requires_outcome' => true,
        ],
        'note' => [
            'name' => 'Note',
            'icon' => 'file-text',
            'color' => '#6B7280',
            'default_duration' => 0,
            'requires_outcome' => false,
        ],
        'lunch' => [
            'name' => 'Lunch',
            'icon' => 'coffee',
            'color' => '#EF4444',
            'default_duration' => 90,
            'requires_outcome' => false,
        ],
        'demo' => [
            'name' => 'Demo',
            'icon' => 'monitor',
            'color' => '#8B5CF6',
            'default_duration' => 45,
            'requires_outcome' => true,
        ],
        'proposal' => [
            'name' => 'Proposal',
            'icon' => 'document',
            'color' => '#06B6D4',
            'default_duration' => 120,
            'requires_outcome' => true,
        ],
    ],

    'outcomes' => [
        'call' => [
            'connected' => 'Connected',
            'no_answer' => 'No Answer',
            'voicemail' => 'Left Voicemail',
            'busy' => 'Busy',
            'wrong_number' => 'Wrong Number',
            'interested' => 'Interested',
            'not_interested' => 'Not Interested',
            'callback_requested' => 'Callback Requested',
        ],
        'meeting' => [
            'completed' => 'Completed',
            'no_show' => 'No Show',
            'rescheduled' => 'Rescheduled',
            'cancelled' => 'Cancelled',
            'productive' => 'Productive',
            'follow_up_needed' => 'Follow-up Needed',
        ],
        'task' => [
            'completed' => 'Completed',
            'in_progress' => 'In Progress',
            'blocked' => 'Blocked',
            'cancelled' => 'Cancelled',
            'deferred' => 'Deferred',
        ],
        'demo' => [
            'completed' => 'Completed',
            'no_show' => 'No Show',
            'technical_issues' => 'Technical Issues',
            'interested' => 'Interested',
            'not_interested' => 'Not Interested',
            'follow_up_scheduled' => 'Follow-up Scheduled',
        ],
    ],

    'priorities' => [
        'low' => 'Low',
        'medium' => 'Medium',
        'high' => 'High',
        'urgent' => 'Urgent',
    ],

    'statuses' => [
        'scheduled' => 'Scheduled',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
        'no_show' => 'No Show',
    ],

    'reminders' => [
        'enabled' => true,
        'default_reminder_time' => 15, // minutes before
        'reminder_methods' => ['email', 'notification', 'sms'],
        'multiple_reminders' => true,
        'reminder_options' => [5, 10, 15, 30, 60, 120, 1440], // minutes
    ],

    'calendar' => [
        'integration_enabled' => true,
        'default_view' => 'week', // day, week, month
        'time_slot_duration' => 30, // minutes
        'show_weekends' => false,
        'first_day_of_week' => 1, // 0 = Sunday, 1 = Monday
        'business_hours_only' => true,
    ],

    'auto_logging' => [
        'enabled' => true,
        'log_emails' => true,
        'log_calls' => true,
        'log_meetings' => true,
        'require_manual_confirmation' => false,
    ],

    'analytics' => [
        'track_completion_rates' => true,
        'track_response_times' => true,
        'track_productivity_metrics' => true,
        'generate_reports' => true,
    ],

    'notifications' => [
        'activity_assigned' => true,
        'activity_due' => true,
        'activity_overdue' => true,
        'activity_completed' => true,
        'activity_cancelled' => true,
        'daily_summary' => true,
        'weekly_summary' => true,
    ],

    'permissions' => [
        'view_own_activities' => true,
        'view_team_activities' => false,
        'view_all_activities' => false,
        'create_activities' => true,
        'edit_own_activities' => true,
        'edit_team_activities' => false,
        'edit_all_activities' => false,
        'delete_own_activities' => true,
        'delete_team_activities' => false,
        'delete_all_activities' => false,
    ],
];
