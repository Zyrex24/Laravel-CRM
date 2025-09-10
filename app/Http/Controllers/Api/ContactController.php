<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ContactController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Contact::query()->with(['organization']);

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        $perPage = $request->input('per_page', 15);
        $contacts = $query->paginate($perPage);

        return response()->json($contacts);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:contacts,email',
                'phone' => 'nullable|string|max:255',
                'title' => 'nullable|string|max:255',
                'company' => 'nullable|string|max:255',
                'organization_id' => 'nullable|exists:organizations,id',
                'address' => 'nullable|array',
                'notes' => 'nullable|string',
                'source' => 'nullable|string|max:255',
                'status' => 'nullable|string|in:active,inactive',
                'birthday' => 'nullable|date',
                'social_media' => 'nullable|array',
                'lead_score' => 'nullable|string|max:255',
            ]);

            $contact = Contact::create($validated);
            $contact->load('organization');

            return response()->json($contact, 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    public function show(Contact $contact): JsonResponse
    {
        $contact->load(['organization', 'leads', 'activities']);
        return response()->json($contact);
    }

    public function update(Request $request, Contact $contact): JsonResponse
    {
        try {
            $validated = $request->validate([
                'first_name' => 'sometimes|required|string|max:255',
                'last_name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|unique:contacts,email,' . $contact->id,
                'phone' => 'nullable|string|max:255',
                'title' => 'nullable|string|max:255',
                'company' => 'nullable|string|max:255',
                'organization_id' => 'nullable|exists:organizations,id',
                'address' => 'nullable|array',
                'notes' => 'nullable|string',
                'source' => 'nullable|string|max:255',
                'status' => 'nullable|string|in:active,inactive',
                'birthday' => 'nullable|date',
                'social_media' => 'nullable|array',
                'lead_score' => 'nullable|string|max:255',
            ]);

            $contact->update($validated);
            $contact->load('organization');

            return response()->json($contact);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    public function destroy(Contact $contact): JsonResponse
    {
        $contact->delete();
        return response()->json(['message' => 'Contact deleted successfully']);
    }
}
