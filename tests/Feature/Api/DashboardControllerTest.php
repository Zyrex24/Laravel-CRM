<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'sanctum');
    }

    /** @test */
    public function it_can_get_dashboard_overview()
    {
        $response = $this->getJson('/api/dashboard/overview');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'stats' => [
                            'total_contacts',
                            'total_leads',
                            'active_deals',
                            'monthly_revenue',
                            'conversion_rate',
                            'activities_today'
                        ],
                        'growth' => [
                            'contacts',
                            'leads',
                            'deals',
                            'revenue'
                        ],
                        'recent_activities'
                    ]
                ]);
    }

    /** @test */
    public function it_can_get_pipeline_data()
    {
        $response = $this->getJson('/api/dashboard/pipeline');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'stages' => [
                            '*' => [
                                'name',
                                'count',
                                'value',
                                'color'
                            ]
                        ],
                        'total_value',
                        'weighted_value',
                        'average_deal_size',
                        'conversion_rates'
                    ]
                ]);
    }

    /** @test */
    public function it_can_get_revenue_analytics()
    {
        $response = $this->getJson('/api/dashboard/revenue?period=monthly');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'period',
                        'chart_data' => [
                            '*' => [
                                'date',
                                'revenue',
                                'deals'
                            ]
                        ],
                        'summary' => [
                            'total_revenue',
                            'total_deals',
                            'average_deal_value',
                            'growth_rate'
                        ]
                    ]
                ]);
    }

    /** @test */
    public function it_can_get_team_performance()
    {
        $response = $this->getJson('/api/dashboard/team-performance');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'team_members' => [
                            '*' => [
                                'id',
                                'name',
                                'role',
                                'contacts_added',
                                'leads_converted',
                                'deals_closed',
                                'revenue_generated',
                                'performance_score'
                            ]
                        ],
                        'team_totals',
                        'top_performers'
                    ]
                ]);
    }

    /** @test */
    public function it_can_get_upcoming_tasks()
    {
        $response = $this->getJson('/api/dashboard/upcoming-tasks');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'today',
                        'tomorrow',
                        'overdue',
                        'summary'
                    ]
                ]);
    }

    /** @test */
    public function it_can_get_notifications()
    {
        $response = $this->getJson('/api/dashboard/notifications');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        '*' => [
                            'id',
                            'type',
                            'title',
                            'message',
                            'read',
                            'created_at'
                        ]
                    ],
                    'unread_count'
                ]);
    }

    /** @test */
    public function it_can_get_system_health()
    {
        $response = $this->getJson('/api/dashboard/system-health');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'status',
                        'uptime',
                        'last_backup',
                        'database_size',
                        'active_users',
                        'storage_usage',
                        'performance'
                    ]
                ]);
    }
}
