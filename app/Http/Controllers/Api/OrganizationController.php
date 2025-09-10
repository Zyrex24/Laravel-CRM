<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OrganizationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Organization::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('industry', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        $perPage = $request->input('per_page', 15);
        $organizations = $query->paginate($perPage);

        return response()->json($organizations);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'website' => 'nullable|url',
                'industry' => 'nullable|string|max:255',
                'employees' => 'nullable|integer|min:0',
                'annual_revenue' => 'nullable|numeric|min:0',
                'description' => 'nullable|string',
                'phone' => 'nullable|string|max:255',
                'email' => 'nullable|email',
                'address' => 'nullable|array',
                'status' => 'nullable|string|in:active,inactive',
            ]);

            $organization = Organization::create($validated);

            return response()->json($organization, 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    public function show(Organization $organization): JsonResponse
    {
        $organization->load(['contacts', 'leads', 'activities']);
        return response()->json($organization);
    }

    public function update(Request $request, Organization $organization): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'website' => 'nullable|url',
                'industry' => 'nullable|string|max:255',
                'employees' => 'nullable|integer|min:0',
                'annual_revenue' => 'nullable|numeric|min:0',
                'description' => 'nullable|string',
                'phone' => 'nullable|string|max:255',
                'email' => 'nullable|email',
                'address' => 'nullable|array',
                'status' => 'nullable|string|in:active,inactive',
            ]);

            $organization->update($validated);

            return response()->json($organization);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    public function destroy(Organization $organization): JsonResponse
    {
        $organization->delete();
        return response()->json(['message' => 'Organization deleted successfully']);
    }
}