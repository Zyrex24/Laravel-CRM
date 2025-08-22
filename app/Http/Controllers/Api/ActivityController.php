<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ActivityController extends Controller
{
    /**
     * Display a listing of activities.
     */
    public function index(Request $request): JsonResponse
    {
        $type = $request->get('type', 'all');
        $status = $request->get('status', 'all');
        $contact_id = $request->get('contact_id');
        $perPage = $request->get('per_page', 15);

        // Mock data
        $activities = collect([
            [
                'id' => 1,
                'type' => 'call',
                'subject' => 'Follow-up call with John Doe',
                'description' => 'Discuss project requirements and timeline',
                'status' => 'completed',
                'priority' => 'high',
                'contact_id' => 1,
                'contact_name' => 'John Doe',
                'due_date' => now()->subDays(1),
                'completed_at' => now()->subHours(2),
                'created_at' => now()->subDays(3)
            ],
            [
                'id' => 2,
                'type' => 'email',
                'subject' => 'Send proposal to Tech Solutions',
                'description' => 'Prepare and send detailed project proposal',
                'status' => 'pending',
                'priority' => 'medium',
                'contact_id' => 2,
                'contact_name' => 'Tech Solutions Inc',
                'due_date' => now()->addDays(2),
                'completed_at' => null,
                'created_at' => now()->subDays(1)
            ],
            [
                'id' => 3,
                'type' => 'meeting',
                'subject' => 'Product demo meeting',
                'description' => 'Demonstrate new features to potential client',
                'status' => 'scheduled',
                'priority' => 'high',
                'contact_id' => 1,
                'contact_name' => 'John Doe',
                'due_date' => now()->addDays(1),
                'completed_at' => null,
                'created_at' => now()
            ]
        ]);

        // Apply filters
        if ($type !== 'all') {
            $activities = $activities->where('type', $type);
        }

        if ($status !== 'all') {
            $activities = $activities->where('status', $status);
        }

        if ($contact_id) {
            $activities = $activities->where('contact_id', (int)$contact_id);
        }

        return response()->json([
            'success' => true,
            'data' => $activities->values(),
            'meta' => [
                'total' => $activities->count(),
                'per_page' => $perPage,
                'current_page' => 1
            ]
        ]);
    }

    /**
     * Store a newly created activity.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:call,email,meeting,task,note',
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'contact_id' => 'required|integer|exists:contacts,id',
            'due_date' => 'nullable|date|after_or_equal:today',
            'status' => 'sometimes|in:pending,scheduled,in_progress,completed,cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $activity = array_merge($request->all(), [
            'id' => rand(1000, 9999),
            'status' => $request->get('status', 'pending'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Activity created successfully',
            'data' => $activity
        ], 201);
    }

    /**
     * Display the specified activity.
     */
    public function show(int $id): JsonResponse
    {
        $activity = [
            'id' => $id,
            'type' => 'call',
            'subject' => 'Follow-up call with John Doe',
            'description' => 'Discuss project requirements and timeline. Review budget constraints and delivery expectations.',
            'status' => 'completed',
            'priority' => 'high',
            'contact_id' => 1,
            'contact_name' => 'John Doe',
            'contact_email' => 'john@example.com',
            'due_date' => now()->subDays(1),
            'completed_at' => now()->subHours(2),
            'notes' => 'Client is interested in expanding the project scope',
            'attachments' => [
                ['name' => 'meeting_notes.pdf', 'size' => '245KB'],
                ['name' => 'project_outline.docx', 'size' => '128KB']
            ],
            'created_at' => now()->subDays(3),
            'updated_at' => now()->subHours(2)
        ];

        return response()->json([
            'success' => true,
            'data' => $activity
        ]);
    }

    /**
     * Update the specified activity.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'type' => 'sometimes|required|in:call,email,meeting,task,note',
            'subject' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'sometimes|required|in:low,medium,high',
            'contact_id' => 'sometimes|required|integer|exists:contacts,id',
            'due_date' => 'nullable|date|after_or_equal:today',
            'status' => 'sometimes|in:pending,scheduled,in_progress,completed,cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $activity = array_merge([
            'id' => $id,
            'type' => 'call',
            'subject' => 'Follow-up call with John Doe',
            'created_at' => now()->subDays(3)
        ], $request->all(), [
            'updated_at' => now()
        ]);

        // Set completed_at if status is being changed to completed
        if ($request->get('status') === 'completed' && !isset($activity['completed_at'])) {
            $activity['completed_at'] = now();
        }

        return response()->json([
            'success' => true,
            'message' => 'Activity updated successfully',
            'data' => $activity
        ]);
    }

    /**
     * Remove the specified activity.
     */
    public function destroy(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Activity deleted successfully'
        ]);
    }

    /**
     * Mark activity as completed.
     */
    public function complete(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Activity marked as completed',
            'data' => [
                'id' => $id,
                'status' => 'completed',
                'completed_at' => now()
            ]
        ]);
    }

    /**
     * Get activity statistics.
     */
    public function stats(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'total_activities' => 245,
                'completed' => 180,
                'pending' => 45,
                'overdue' => 12,
                'scheduled' => 8,
                'completion_rate' => 73.5,
                'by_type' => [
                    'call' => 85,
                    'email' => 92,
                    'meeting' => 38,
                    'task' => 25,
                    'note' => 5
                ],
                'by_priority' => [
                    'high' => 45,
                    'medium' => 125,
                    'low' => 75
                ]
            ]
        ]);
    }

    /**
     * Get upcoming activities.
     */
    public function upcoming(Request $request): JsonResponse
    {
        $days = $request->get('days', 7);
        
        $activities = [
            [
                'id' => 3,
                'type' => 'meeting',
                'subject' => 'Product demo meeting',
                'contact_name' => 'John Doe',
                'due_date' => now()->addDays(1),
                'priority' => 'high'
            ],
            [
                'id' => 2,
                'type' => 'email',
                'subject' => 'Send proposal to Tech Solutions',
                'contact_name' => 'Tech Solutions Inc',
                'due_date' => now()->addDays(2),
                'priority' => 'medium'
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $activities,
            'period' => "{$days} days"
        ]);
    }

    /**
     * Get overdue activities.
     */
    public function overdue(): JsonResponse
    {
        $activities = [
            [
                'id' => 4,
                'type' => 'call',
                'subject' => 'Follow-up with prospect',
                'contact_name' => 'Jane Smith',
                'due_date' => now()->subDays(2),
                'priority' => 'high',
                'days_overdue' => 2
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $activities,
            'total_overdue' => count($activities)
        ]);
    }
}
