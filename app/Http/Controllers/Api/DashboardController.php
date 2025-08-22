<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    /**
     * Get dashboard overview data.
     */
    public function overview(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'stats' => [
                    'total_contacts' => 1250,
                    'total_leads' => 340,
                    'active_deals' => 85,
                    'monthly_revenue' => 125000,
                    'conversion_rate' => 18.5,
                    'activities_today' => 23
                ],
                'growth' => [
                    'contacts' => 12.5,
                    'leads' => 8.3,
                    'deals' => 15.2,
                    'revenue' => 22.1
                ],
                'recent_activities' => [
                    [
                        'id' => 1,
                        'type' => 'contact_created',
                        'description' => 'New contact added: John Smith',
                        'user' => 'Sales Rep',
                        'created_at' => now()->subMinutes(15)
                    ],
                    [
                        'id' => 2,
                        'type' => 'deal_won',
                        'description' => 'Deal closed: $50,000 with Tech Corp',
                        'user' => 'Sales Manager',
                        'created_at' => now()->subHours(2)
                    ],
                    [
                        'id' => 3,
                        'type' => 'email_sent',
                        'description' => 'Follow-up email sent to Jane Doe',
                        'user' => 'Admin User',
                        'created_at' => now()->subHours(4)
                    ]
                ]
            ]
        ]);
    }

    /**
     * Get sales pipeline data.
     */
    public function pipeline(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'stages' => [
                    ['name' => 'Prospecting', 'count' => 45, 'value' => 225000, 'color' => '#3B82F6'],
                    ['name' => 'Qualification', 'count' => 32, 'value' => 480000, 'color' => '#8B5CF6'],
                    ['name' => 'Proposal', 'count' => 18, 'value' => 540000, 'color' => '#06B6D4'],
                    ['name' => 'Negotiation', 'count' => 12, 'value' => 360000, 'color' => '#10B981'],
                    ['name' => 'Closed Won', 'count' => 8, 'value' => 320000, 'color' => '#F59E0B']
                ],
                'total_value' => 1925000,
                'weighted_value' => 962500,
                'average_deal_size' => 16739,
                'conversion_rates' => [
                    'prospecting_to_qualification' => 71.1,
                    'qualification_to_proposal' => 56.3,
                    'proposal_to_negotiation' => 66.7,
                    'negotiation_to_closed' => 66.7
                ]
            ]
        ]);
    }

    /**
     * Get revenue analytics.
     */
    public function revenue(Request $request): JsonResponse
    {
        $period = $request->get('period', 'monthly'); // daily, weekly, monthly, yearly
        
        $data = [];
        switch ($period) {
            case 'daily':
                for ($i = 29; $i >= 0; $i--) {
                    $data[] = [
                        'date' => now()->subDays($i)->format('Y-m-d'),
                        'revenue' => rand(2000, 8000),
                        'deals' => rand(1, 5)
                    ];
                }
                break;
            case 'weekly':
                for ($i = 11; $i >= 0; $i--) {
                    $data[] = [
                        'date' => now()->subWeeks($i)->format('Y-m-d'),
                        'revenue' => rand(15000, 45000),
                        'deals' => rand(3, 12)
                    ];
                }
                break;
            case 'monthly':
                for ($i = 11; $i >= 0; $i--) {
                    $data[] = [
                        'date' => now()->subMonths($i)->format('Y-m'),
                        'revenue' => rand(80000, 150000),
                        'deals' => rand(15, 35)
                    ];
                }
                break;
            case 'yearly':
                for ($i = 4; $i >= 0; $i--) {
                    $data[] = [
                        'date' => now()->subYears($i)->format('Y'),
                        'revenue' => rand(800000, 1500000),
                        'deals' => rand(150, 350)
                    ];
                }
                break;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'period' => $period,
                'chart_data' => $data,
                'summary' => [
                    'total_revenue' => array_sum(array_column($data, 'revenue')),
                    'total_deals' => array_sum(array_column($data, 'deals')),
                    'average_deal_value' => array_sum(array_column($data, 'revenue')) / array_sum(array_column($data, 'deals')),
                    'growth_rate' => 15.3
                ]
            ]
        ]);
    }

    /**
     * Get team performance data.
     */
    public function teamPerformance(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'team_members' => [
                    [
                        'id' => 1,
                        'name' => 'Sales Manager',
                        'role' => 'manager',
                        'contacts_added' => 45,
                        'leads_converted' => 12,
                        'deals_closed' => 8,
                        'revenue_generated' => 320000,
                        'activities_completed' => 156,
                        'performance_score' => 92
                    ],
                    [
                        'id' => 2,
                        'name' => 'Sales Rep',
                        'role' => 'user',
                        'contacts_added' => 32,
                        'leads_converted' => 8,
                        'deals_closed' => 5,
                        'revenue_generated' => 180000,
                        'activities_completed' => 98,
                        'performance_score' => 78
                    ]
                ],
                'team_totals' => [
                    'contacts_added' => 77,
                    'leads_converted' => 20,
                    'deals_closed' => 13,
                    'revenue_generated' => 500000,
                    'activities_completed' => 254
                ],
                'top_performers' => [
                    ['name' => 'Sales Manager', 'metric' => 'Revenue', 'value' => '$320,000'],
                    ['name' => 'Sales Manager', 'metric' => 'Deals Closed', 'value' => '8'],
                    ['name' => 'Sales Manager', 'metric' => 'Activities', 'value' => '156']
                ]
            ]
        ]);
    }

    /**
     * Get upcoming tasks and activities.
     */
    public function upcomingTasks(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'today' => [
                    [
                        'id' => 1,
                        'type' => 'call',
                        'subject' => 'Follow-up call with John Doe',
                        'contact' => 'John Doe',
                        'time' => '14:00',
                        'priority' => 'high'
                    ],
                    [
                        'id' => 2,
                        'type' => 'meeting',
                        'subject' => 'Product demo with Tech Corp',
                        'contact' => 'Tech Corp',
                        'time' => '16:30',
                        'priority' => 'high'
                    ]
                ],
                'tomorrow' => [
                    [
                        'id' => 3,
                        'type' => 'email',
                        'subject' => 'Send proposal to Jane Smith',
                        'contact' => 'Jane Smith',
                        'time' => '09:00',
                        'priority' => 'medium'
                    ]
                ],
                'overdue' => [
                    [
                        'id' => 4,
                        'type' => 'task',
                        'subject' => 'Update contact information',
                        'contact' => 'Bob Johnson',
                        'due_date' => now()->subDays(2)->format('Y-m-d'),
                        'priority' => 'low'
                    ]
                ],
                'summary' => [
                    'total_today' => 2,
                    'total_tomorrow' => 1,
                    'total_overdue' => 1,
                    'high_priority' => 2
                ]
            ]
        ]);
    }

    /**
     * Get recent notifications.
     */
    public function notifications(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                [
                    'id' => 1,
                    'type' => 'deal_won',
                    'title' => 'Deal Closed',
                    'message' => 'Congratulations! Deal with Tech Corp has been closed for $50,000',
                    'read' => false,
                    'created_at' => now()->subMinutes(30)
                ],
                [
                    'id' => 2,
                    'type' => 'task_overdue',
                    'title' => 'Overdue Task',
                    'message' => 'Task "Follow-up with prospect" is now 2 days overdue',
                    'read' => false,
                    'created_at' => now()->subHours(2)
                ],
                [
                    'id' => 3,
                    'type' => 'new_lead',
                    'title' => 'New Lead',
                    'message' => 'New lead "Sarah Wilson" has been added from website form',
                    'read' => true,
                    'created_at' => now()->subHours(4)
                ]
            ],
            'unread_count' => 2
        ]);
    }

    /**
     * Get quick actions data.
     */
    public function quickActions(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                [
                    'id' => 'add_contact',
                    'title' => 'Add Contact',
                    'description' => 'Create a new contact',
                    'icon' => 'user-plus',
                    'route' => '/contacts/create',
                    'color' => 'blue'
                ],
                [
                    'id' => 'add_lead',
                    'title' => 'Add Lead',
                    'description' => 'Create a new lead',
                    'icon' => 'target',
                    'route' => '/leads/create',
                    'color' => 'green'
                ],
                [
                    'id' => 'schedule_activity',
                    'title' => 'Schedule Activity',
                    'description' => 'Plan a call, meeting, or task',
                    'icon' => 'calendar-plus',
                    'route' => '/activities/create',
                    'color' => 'purple'
                ],
                [
                    'id' => 'send_email',
                    'title' => 'Send Email',
                    'description' => 'Compose and send email',
                    'icon' => 'mail',
                    'route' => '/emails/compose',
                    'color' => 'orange'
                ]
            ]
        ]);
    }

    /**
     * Get system health status.
     */
    public function systemHealth(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'status' => 'healthy',
                'uptime' => '99.9%',
                'last_backup' => now()->subHours(6),
                'database_size' => '2.3 GB',
                'active_users' => 12,
                'email_queue' => 3,
                'storage_usage' => [
                    'used' => '15.2 GB',
                    'total' => '100 GB',
                    'percentage' => 15.2
                ],
                'recent_errors' => 0,
                'performance' => [
                    'avg_response_time' => '145ms',
                    'requests_per_minute' => 45,
                    'memory_usage' => '68%',
                    'cpu_usage' => '23%'
                ]
            ]
        ]);
    }
}
