<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LeadController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Lead::query()->with(['contact', 'organization', 'assignedTo']);

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('assigned_to')) {
            $query->where('assigned_to', $request->input('assigned_to'));
        }

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 15);
        $leads = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($leads);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'status' => 'nullable|string|in:new,qualified,proposal,negotiation,closed_won,closed_lost',
                'source' => 'nullable|string|max:255',
                'priority' => 'nullable|string|in:low,medium,high',
                'value' => 'nullable|numeric|min:0',
                'expected_close_date' => 'nullable|date',
                'probability' => 'nullable|integer|min:0|max:100',
                'contact_id' => 'nullable|exists:contacts,id',
                'organization_id' => 'nullable|exists:organizations,id',
                'assigned_to' => 'nullable|exists:users,id',
                'custom_fields' => 'nullable|array',
                'notes' => 'nullable|string',
            ]);

            $lead = Lead::create($validated);
            $lead->load(['contact', 'organization', 'assignedTo']);

            return response()->json($lead, 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    public function show(Lead $lead): JsonResponse
    {
        $lead->load(['contact', 'organization', 'assignedTo', 'activities']);
        return response()->json($lead);
    }

    public function update(Request $request, Lead $lead): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'status' => 'nullable|string|in:new,qualified,proposal,negotiation,closed_won,closed_lost',
                'source' => 'nullable|string|max:255',
                'priority' => 'nullable|string|in:low,medium,high',
                'value' => 'nullable|numeric|min:0',
                'expected_close_date' => 'nullable|date',
                'probability' => 'nullable|integer|min:0|max:100',
                'contact_id' => 'nullable|exists:contacts,id',
                'organization_id' => 'nullable|exists:organizations,id',
                'assigned_to' => 'nullable|exists:users,id',
                'custom_fields' => 'nullable|array',
                'notes' => 'nullable|string',
            ]);

            $lead->update($validated);
            $lead->load(['contact', 'organization', 'assignedTo']);

            return response()->json($lead);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    public function destroy(Lead $lead): JsonResponse
    {
        $lead->delete();
        return response()->json(['message' => 'Lead deleted successfully']);
    }
}