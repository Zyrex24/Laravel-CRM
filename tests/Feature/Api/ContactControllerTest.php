<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create and authenticate a user for API testing
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'sanctum');
    }

    /** @test */
    public function it_can_list_contacts()
    {
        $response = $this->getJson('/api/contacts');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'email',
                            'phone',
                            'type',
                            'created_at',
                            'updated_at'
                        ]
                    ],
                    'meta' => [
                        'total',
                        'per_page',
                        'current_page'
                    ]
                ]);
    }

    /** @test */
    public function it_can_create_a_contact()
    {
        $contactData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'type' => 'person',
            'company' => $this->faker->company
        ];

        $response = $this->postJson('/api/contacts', $contactData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'id',
                        'name',
                        'email',
                        'phone',
                        'type',
                        'company',
                        'created_at',
                        'updated_at'
                    ]
                ])
                ->assertJson([
                    'success' => true,
                    'message' => 'Contact created successfully'
                ]);
    }

    /** @test */
    public function it_validates_contact_creation_data()
    {
        $invalidData = [
            'name' => '', // Required field
            'email' => 'invalid-email', // Invalid email format
            'type' => 'invalid-type' // Invalid type
        ];

        $response = $this->postJson('/api/contacts', $invalidData);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'errors' => [
                        'name',
                        'email',
                        'type'
                    ]
                ])
                ->assertJson([
                    'success' => false,
                    'message' => 'Validation failed'
                ]);
    }

    /** @test */
    public function it_can_show_a_contact()
    {
        $contactId = 1;

        $response = $this->getJson("/api/contacts/{$contactId}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'id',
                        'name',
                        'email',
                        'phone',
                        'type',
                        'company',
                        'notes',
                        'created_at',
                        'updated_at'
                    ]
                ])
                ->assertJson([
                    'success' => true
                ]);
    }

    /** @test */
    public function it_can_update_a_contact()
    {
        $contactId = 1;
        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '+1234567890'
        ];

        $response = $this->putJson("/api/contacts/{$contactId}", $updateData);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'id',
                        'name',
                        'email',
                        'phone',
                        'updated_at'
                    ]
                ])
                ->assertJson([
                    'success' => true,
                    'message' => 'Contact updated successfully'
                ]);
    }

    /** @test */
    public function it_can_delete_a_contact()
    {
        $contactId = 1;

        $response = $this->deleteJson("/api/contacts/{$contactId}");

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Contact deleted successfully'
                ]);
    }

    /** @test */
    public function it_can_get_contact_statistics()
    {
        $response = $this->getJson('/api/contacts/stats/overview');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'total_contacts',
                        'persons',
                        'organizations',
                        'recent_contacts',
                        'growth_rate'
                    ]
                ])
                ->assertJson([
                    'success' => true
                ]);
    }

    /** @test */
    public function it_can_search_contacts()
    {
        $searchData = [
            'query' => 'John',
            'type' => 'person'
        ];

        $response = $this->postJson('/api/contacts/search', $searchData);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'email',
                            'type',
                            'match_score'
                        ]
                    ],
                    'filters_applied',
                    'total_results'
                ])
                ->assertJson([
                    'success' => true
                ]);
    }

    /** @test */
    public function it_requires_authentication()
    {
        // Remove authentication
        $this->app['auth']->forgetGuards();

        $response = $this->getJson('/api/contacts');

        $response->assertStatus(401);
    }

    /** @test */
    public function it_can_filter_contacts_by_type()
    {
        $response = $this->getJson('/api/contacts?type=person');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true
                ]);
    }

    /** @test */
    public function it_can_search_contacts_with_query()
    {
        $response = $this->getJson('/api/contacts?query=John');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true
                ]);
    }
}
