<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class ActivityControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'sanctum');
    }

    /** @test */
    public function it_can_list_activities()
    {
        $response = $this->getJson('/api/activities');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        '*' => [
                            'id',
                            'type',
                            'subject',
                            'description',
                            'status',
                            'priority',
                            'contact_id',
                            'contact_name',
                            'due_date',
                            'created_at'
                        ]
                    ],
                    'meta'
                ]);
    }

    /** @test */
    public function it_can_create_an_activity()
    {
        $activityData = [
            'type' => 'call',
            'subject' => 'Follow-up call',
            'description' => 'Call to discuss project requirements',
            'priority' => 'high',
            'contact_id' => 1,
            'due_date' => now()->addDays(1)->format('Y-m-d')
        ];

        $response = $this->postJson('/api/activities', $activityData);

        $response->assertStatus(201)
                ->assertJson([
                    'success' => true,
                    'message' => 'Activity created successfully'
                ]);
    }

    /** @test */
    public function it_can_complete_an_activity()
    {
        $activityId = 1;

        $response = $this->postJson("/api/activities/{$activityId}/complete");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'id',
                        'status',
                        'completed_at'
                    ]
                ])
                ->assertJson([
                    'success' => true,
                    'message' => 'Activity marked as completed'
                ]);
    }

    /** @test */
    public function it_can_get_activity_statistics()
    {
        $response = $this->getJson('/api/activities/stats/overview');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'total_activities',
                        'completed',
                        'pending',
                        'overdue',
                        'completion_rate',
                        'by_type',
                        'by_priority'
                    ]
                ]);
    }

    /** @test */
    public function it_can_get_upcoming_activities()
    {
        $response = $this->getJson('/api/activities/upcoming?days=7');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        '*' => [
                            'id',
                            'type',
                            'subject',
                            'contact_name',
                            'due_date',
                            'priority'
                        ]
                    ],
                    'period'
                ]);
    }

    /** @test */
    public function it_can_get_overdue_activities()
    {
        $response = $this->getJson('/api/activities/overdue');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        '*' => [
                            'id',
                            'type',
                            'subject',
                            'contact_name',
                            'due_date',
                            'priority',
                            'days_overdue'
                        ]
                    ],
                    'total_overdue'
                ]);
    }

    /** @test */
    public function it_validates_activity_type()
    {
        $invalidData = [
            'type' => 'invalid_type',
            'subject' => 'Test activity',
            'priority' => 'high',
            'contact_id' => 1
        ];

        $response = $this->postJson('/api/activities', $invalidData);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'errors' => ['type']
                ]);
    }

    /** @test */
    public function it_can_filter_activities_by_type()
    {
        $response = $this->getJson('/api/activities?type=call');

        $response->assertStatus(200)
                ->assertJson(['success' => true]);
    }

    /** @test */
    public function it_can_filter_activities_by_contact()
    {
        $response = $this->getJson('/api/activities?contact_id=1');

        $response->assertStatus(200)
                ->assertJson(['success' => true]);
    }
}
