<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LeadControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'sanctum');
    }

    /** @test */
    public function it_can_list_leads()
    {
        $response = $this->getJson('/api/leads');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'email',
                            'phone',
                            'company',
                            'status',
                            'source',
                            'value',
                            'probability',
                            'expected_close_date',
                            'created_at'
                        ]
                    ],
                    'meta'
                ]);
    }

    /** @test */
    public function it_can_create_a_lead()
    {
        $leadData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'company' => $this->faker->company,
            'status' => 'new',
            'source' => 'website',
            'value' => 50000,
            'probability' => 25,
            'expected_close_date' => now()->addDays(30)->format('Y-m-d')
        ];

        $response = $this->postJson('/api/leads', $leadData);

        $response->assertStatus(201)
                ->assertJson([
                    'success' => true,
                    'message' => 'Lead created successfully'
                ]);
    }

    /** @test */
    public function it_can_convert_lead_to_contact()
    {
        $leadId = 1;

        $response = $this->postJson("/api/leads/{$leadId}/convert");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'contact_id',
                        'lead_id',
                        'converted_at'
                    ]
                ])
                ->assertJson([
                    'success' => true,
                    'message' => 'Lead converted to contact successfully'
                ]);
    }

    /** @test */
    public function it_can_get_pipeline_analytics()
    {
        $response = $this->getJson('/api/leads/analytics/pipeline');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'stages' => [
                            '*' => [
                                'name',
                                'count',
                                'value'
                            ]
                        ],
                        'total_value',
                        'conversion_rate',
                        'average_deal_size'
                    ]
                ]);
    }

    /** @test */
    public function it_can_get_source_analytics()
    {
        $response = $this->getJson('/api/leads/analytics/sources');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        '*' => [
                            'source',
                            'count',
                            'conversion_rate'
                        ]
                    ]
                ]);
    }

    /** @test */
    public function it_validates_lead_status()
    {
        $invalidData = [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'status' => 'invalid_status',
            'source' => 'website'
        ];

        $response = $this->postJson('/api/leads', $invalidData);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'errors' => [
                        'status'
                    ]
                ]);
    }

    /** @test */
    public function it_can_filter_leads_by_status()
    {
        $response = $this->getJson('/api/leads?status=qualified');

        $response->assertStatus(200)
                ->assertJson(['success' => true]);
    }

    /** @test */
    public function it_can_filter_leads_by_source()
    {
        $response = $this->getJson('/api/leads?source=referral');

        $response->assertStatus(200)
                ->assertJson(['success' => true]);
    }
}
