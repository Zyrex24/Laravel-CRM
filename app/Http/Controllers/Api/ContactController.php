<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Display a listing of contacts.
     */
    public function index(Request $request): JsonResponse
    {
        $query = $request->get('query', '');
        $type = $request->get('type', 'all'); // person, organization, all
        $perPage = $request->get('per_page', 15);

        // Mock data for demonstration
        $contacts = collect([
            [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '+1234567890',
                'type' => 'person',
                'company' => 'Tech Corp',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(2)
            ],
            [
                'id' => 2,
                'name' => 'Tech Solutions Inc',
                'email' => 'info@techsolutions.com',
                'phone' => '+1987654321',
                'type' => 'organization',
                'industry' => 'Technology',
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(1)
            ]
        ]);

        // Apply filters
        if ($query) {
            $contacts = $contacts->filter(function ($contact) use ($query) {
                return stripos($contact['name'], $query) !== false ||
                       stripos($contact['email'], $query) !== false;
            });
        }

        if ($type !== 'all') {
            $contacts = $contacts->where('type', $type);
        }

        return response()->json([
            'success' => true,
            'data' => $contacts->values(),
            'meta' => [
                'total' => $contacts->count(),
                'per_page' => $perPage,
                'current_page' => 1
            ]
        ]);
    }

    /**
     * Store a newly created contact.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:contacts,email',
            'phone' => 'nullable|string|max:20',
            'type' => 'required|in:person,organization',
            'company' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Mock creation
        $contact = array_merge($request->all(), [
            'id' => rand(1000, 9999),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Contact created successfully',
            'data' => $contact
        ], 201);
    }

    /**
     * Display the specified contact.
     */
    public function show(int $id): JsonResponse
    {
        // Mock data
        $contact = [
            'id' => $id,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+1234567890',
            'type' => 'person',
            'company' => 'Tech Corp',
            'notes' => 'Important client contact',
            'created_at' => now()->subDays(5),
            'updated_at' => now()->subDays(2)
        ];

        return response()->json([
            'success' => true,
            'data' => $contact
        ]);
    }

    /**
     * Update the specified contact.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:contacts,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'type' => 'sometimes|required|in:person,organization',
            'company' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Mock update
        $contact = array_merge([
            'id' => $id,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+1234567890',
            'type' => 'person',
            'company' => 'Tech Corp',
            'created_at' => now()->subDays(5)
        ], $request->all(), [
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Contact updated successfully',
            'data' => $contact
        ]);
    }

    /**
     * Remove the specified contact.
     */
    public function destroy(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Contact deleted successfully'
        ]);
    }

    /**
     * Get contact statistics.
     */
    public function stats(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'total_contacts' => 150,
                'persons' => 120,
                'organizations' => 30,
                'recent_contacts' => 15,
                'growth_rate' => 12.5
            ]
        ]);
    }

    /**
     * Search contacts with advanced filters.
     */
    public function search(Request $request): JsonResponse
    {
        $filters = $request->only(['query', 'type', 'company', 'industry', 'created_after', 'created_before']);
        
        // Mock search results
        $results = [
            [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'type' => 'person',
                'company' => 'Tech Corp',
                'match_score' => 0.95
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $results,
            'filters_applied' => $filters,
            'total_results' => count($results)
        ]);
    }
}
