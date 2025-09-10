<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ActivityController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Activity::query()->with(['contact', 'lead', 'organization', 'assignedTo', 'createdBy']);

        if ($request->has('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('assigned_to')) {
            $query->where('assigned_to', $request->input('assigned_to'));
        }

        $perPage = $request->input('per_page', 15);
        $activities = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($activities);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'type' => 'required|string|in:call,email,meeting,task,note',
                'status' => 'nullable|string|in:pending,completed,cancelled',
                'priority' => 'nullable|string|in:low,medium,high',
                'due_date' => 'nullable|date',
                'contact_id' => 'nullable|exists:contacts,id',
                'lead_id' => 'nullable|exists:leads,id',
                'organization_id' => 'nullable|exists:organizations,id',
                'assigned_to' => 'nullable|exists:users,id',
                'created_by' => 'nullable|exists:users,id',
                'metadata' => 'nullable|array',
            ]);

            // Set default values for demo purposes
            $validated['status'] = $validated['status'] ?? 'pending';
            $validated['priority'] = $validated['priority'] ?? 'medium';
            $validated['created_by'] = $validated['created_by'] ?? 1; // Default demo user

            $activity = Activity::create($validated);
            $activity->load(['contact', 'lead', 'organization', 'assignedTo', 'createdBy']);

            return response()->json($activity, 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    public function show(Activity $activity): JsonResponse
    {
        $activity->load(['contact', 'lead', 'organization', 'assignedTo', 'createdBy']);
        return response()->json($activity);
    }

    public function update(Request $request, Activity $activity): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'type' => 'sometimes|required|string|in:call,email,meeting,task,note',
                'status' => 'nullable|string|in:pending,completed,cancelled',
                'priority' => 'nullable|string|in:low,medium,high',
                'due_date' => 'nullable|date',
                'contact_id' => 'nullable|exists:contacts,id',
                'lead_id' => 'nullable|exists:leads,id',
                'organization_id' => 'nullable|exists:organizations,id',
                'assigned_to' => 'nullable|exists:users,id',
                'metadata' => 'nullable|array',
            ]);

            $activity->update($validated);
            $activity->load(['contact', 'lead', 'organization', 'assignedTo', 'createdBy']);

            return response()->json($activity);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    public function destroy(Activity $activity): JsonResponse
    {
        $activity->delete();
        return response()->json(['message' => 'Activity deleted successfully']);
    }
}