<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Email Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the CRM Email package including IMAP settings,
    | template management, tracking, and automation features.
    |
    */

    // Database Configuration
    'database' => [
        'connection' => env('CRM_EMAIL_DB_CONNECTION', 'mysql'),
        'table_prefix' => env('CRM_EMAIL_TABLE_PREFIX', 'crm_'),
    ],

    // IMAP Configuration
    'imap' => [
        'enabled' => env('CRM_EMAIL_IMAP_ENABLED', true),
        'accounts' => [
            'default' => [
                'host' => env('CRM_EMAIL_IMAP_HOST', 'imap.gmail.com'),
                'port' => env('CRM_EMAIL_IMAP_PORT', 993),
                'encryption' => env('CRM_EMAIL_IMAP_ENCRYPTION', 'ssl'),
                'validate_cert' => env('CRM_EMAIL_IMAP_VALIDATE_CERT', true),
                'username' => env('CRM_EMAIL_IMAP_USERNAME'),
                'password' => env('CRM_EMAIL_IMAP_PASSWORD'),
                'protocol' => env('CRM_EMAIL_IMAP_PROTOCOL', 'imap'),
            ],
        ],
        'sync_interval' => env('CRM_EMAIL_SYNC_INTERVAL', 300), // seconds
        'max_messages_per_sync' => env('CRM_EMAIL_MAX_MESSAGES_PER_SYNC', 100),
        'folders' => [
            'inbox' => 'INBOX',
            'sent' => 'INBOX.Sent',
            'drafts' => 'INBOX.Drafts',
            'trash' => 'INBOX.Trash',
        ],
    ],

    // SMTP Configuration
    'smtp' => [
        'enabled' => env('CRM_EMAIL_SMTP_ENABLED', true),
        'driver' => env('CRM_EMAIL_SMTP_DRIVER', 'smtp'),
        'host' => env('CRM_EMAIL_SMTP_HOST', 'smtp.gmail.com'),
        'port' => env('CRM_EMAIL_SMTP_PORT', 587),
        'encryption' => env('CRM_EMAIL_SMTP_ENCRYPTION', 'tls'),
        'username' => env('CRM_EMAIL_SMTP_USERNAME'),
        'password' => env('CRM_EMAIL_SMTP_PASSWORD'),
        'from' => [
            'address' => env('CRM_EMAIL_FROM_ADDRESS', 'noreply@crm.com'),
            'name' => env('CRM_EMAIL_FROM_NAME', 'CRM System'),
        ],
    ],

    // Email Templates
    'templates' => [
        'enabled' => env('CRM_EMAIL_TEMPLATES_ENABLED', true),
        'engine' => env('CRM_EMAIL_TEMPLATE_ENGINE', 'twig'), // blade, twig
        'cache_enabled' => env('CRM_EMAIL_TEMPLATE_CACHE', true),
        'cache_ttl' => env('CRM_EMAIL_TEMPLATE_CACHE_TTL', 3600),
        'default_templates' => [
            'welcome' => 'Welcome to CRM',
            'lead_assigned' => 'New Lead Assigned',
            'follow_up' => 'Follow Up Reminder',
            'quote_sent' => 'Quote Sent',
            'meeting_reminder' => 'Meeting Reminder',
            'task_reminder' => 'Task Reminder',
        ],
        'variables' => [
            'contact' => ['first_name', 'last_name', 'email', 'phone', 'company'],
            'lead' => ['title', 'value', 'stage', 'source', 'priority'],
            'user' => ['name', 'email', 'signature'],
            'organization' => ['name', 'website', 'industry'],
            'system' => ['app_name', 'app_url', 'current_date', 'current_time'],
        ],
    ],

    // Email Tracking
    'tracking' => [
        'enabled' => env('CRM_EMAIL_TRACKING_ENABLED', true),
        'open_tracking' => env('CRM_EMAIL_OPEN_TRACKING', true),
        'click_tracking' => env('CRM_EMAIL_CLICK_TRACKING', true),
        'bounce_tracking' => env('CRM_EMAIL_BOUNCE_TRACKING', true),
        'reply_tracking' => env('CRM_EMAIL_REPLY_TRACKING', true),
        'pixel_url' => env('CRM_EMAIL_PIXEL_URL', '/crm/email/track/open'),
        'link_redirect_url' => env('CRM_EMAIL_LINK_REDIRECT_URL', '/crm/email/track/click'),
        'retention_days' => env('CRM_EMAIL_TRACKING_RETENTION', 365),
    ],

    // Email Automation
    'automation' => [
        'enabled' => env('CRM_EMAIL_AUTOMATION_ENABLED', true),
        'workflows' => [
            'lead_nurturing' => env('CRM_EMAIL_LEAD_NURTURING', true),
            'follow_up_sequences' => env('CRM_EMAIL_FOLLOW_UP_SEQUENCES', true),
            'abandoned_leads' => env('CRM_EMAIL_ABANDONED_LEADS', true),
            'birthday_reminders' => env('CRM_EMAIL_BIRTHDAY_REMINDERS', true),
        ],
        'max_emails_per_day' => env('CRM_EMAIL_MAX_PER_DAY', 1000),
        'delay_between_emails' => env('CRM_EMAIL_DELAY_BETWEEN', 60), // seconds
    ],

    // Email Security
    'security' => [
        'spam_protection' => env('CRM_EMAIL_SPAM_PROTECTION', true),
        'rate_limiting' => [
            'enabled' => env('CRM_EMAIL_RATE_LIMITING', true),
            'max_per_minute' => env('CRM_EMAIL_MAX_PER_MINUTE', 60),
            'max_per_hour' => env('CRM_EMAIL_MAX_PER_HOUR', 1000),
        ],
        'blacklist' => [
            'enabled' => env('CRM_EMAIL_BLACKLIST_ENABLED', true),
            'domains' => explode(',', env('CRM_EMAIL_BLACKLIST_DOMAINS', '')),
            'emails' => explode(',', env('CRM_EMAIL_BLACKLIST_EMAILS', '')),
        ],
        'whitelist' => [
            'enabled' => env('CRM_EMAIL_WHITELIST_ENABLED', false),
            'domains' => explode(',', env('CRM_EMAIL_WHITELIST_DOMAINS', '')),
            'emails' => explode(',', env('CRM_EMAIL_WHITELIST_EMAILS', '')),
        ],
    ],

    // Email Storage
    'storage' => [
        'disk' => env('CRM_EMAIL_STORAGE_DISK', 'local'),
        'path' => env('CRM_EMAIL_STORAGE_PATH', 'crm/emails'),
        'attachments' => [
            'max_size' => env('CRM_EMAIL_MAX_ATTACHMENT_SIZE', 10240), // KB
            'allowed_types' => explode(',', env('CRM_EMAIL_ALLOWED_TYPES', 'pdf,doc,docx,xls,xlsx,ppt,pptx,txt,jpg,jpeg,png,gif')),
            'virus_scan' => env('CRM_EMAIL_VIRUS_SCAN', false),
        ],
    ],

    // Email Analytics
    'analytics' => [
        'enabled' => env('CRM_EMAIL_ANALYTICS_ENABLED', true),
        'metrics' => [
            'open_rate' => true,
            'click_rate' => true,
            'bounce_rate' => true,
            'unsubscribe_rate' => true,
            'reply_rate' => true,
        ],
        'reports' => [
            'daily' => env('CRM_EMAIL_DAILY_REPORTS', true),
            'weekly' => env('CRM_EMAIL_WEEKLY_REPORTS', true),
            'monthly' => env('CRM_EMAIL_MONTHLY_REPORTS', true),
        ],
    ],

    // Email Queue
    'queue' => [
        'enabled' => env('CRM_EMAIL_QUEUE_ENABLED', true),
        'connection' => env('CRM_EMAIL_QUEUE_CONNECTION', 'database'),
        'queue_name' => env('CRM_EMAIL_QUEUE_NAME', 'emails'),
        'retry_attempts' => env('CRM_EMAIL_RETRY_ATTEMPTS', 3),
        'retry_delay' => env('CRM_EMAIL_RETRY_DELAY', 300), // seconds
    ],

    // Email Notifications
    'notifications' => [
        'enabled' => env('CRM_EMAIL_NOTIFICATIONS_ENABLED', true),
        'channels' => ['mail', 'database'],
        'events' => [
            'email_sent' => env('CRM_EMAIL_NOTIFY_SENT', false),
            'email_opened' => env('CRM_EMAIL_NOTIFY_OPENED', true),
            'email_clicked' => env('CRM_EMAIL_NOTIFY_CLICKED', true),
            'email_bounced' => env('CRM_EMAIL_NOTIFY_BOUNCED', true),
            'email_replied' => env('CRM_EMAIL_NOTIFY_REPLIED', true),
        ],
    ],

    // Email Permissions
    'permissions' => [
        'send_emails' => 'Send Emails',
        'view_emails' => 'View Emails',
        'delete_emails' => 'Delete Emails',
        'manage_templates' => 'Manage Email Templates',
        'view_analytics' => 'View Email Analytics',
        'manage_settings' => 'Manage Email Settings',
    ],
];
