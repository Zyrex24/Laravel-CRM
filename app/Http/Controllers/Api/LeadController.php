<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{
    /**
     * Display a listing of leads.
     */
    public function index(Request $request): JsonResponse
    {
        $status = $request->get('status', 'all');
        $source = $request->get('source', 'all');
        $perPage = $request->get('per_page', 15);

        // Mock data
        $leads = collect([
            [
                'id' => 1,
                'name' => 'Jane Smith',
                'email' => 'jane@potential.com',
                'phone' => '+1555123456',
                'company' => 'Potential Corp',
                'status' => 'new',
                'source' => 'website',
                'value' => 50000,
                'probability' => 25,
                'expected_close_date' => now()->addDays(30),
                'created_at' => now()->subDays(3)
            ],
            [
                'id' => 2,
                'name' => 'Bob Johnson',
                'email' => 'bob@prospect.com',
                'phone' => '+1555987654',
                'company' => 'Prospect LLC',
                'status' => 'qualified',
                'source' => 'referral',
                'value' => 75000,
                'probability' => 60,
                'expected_close_date' => now()->addDays(45),
                'created_at' => now()->subDays(7)
            ]
        ]);

        if ($status !== 'all') {
            $leads = $leads->where('status', $status);
        }

        if ($source !== 'all') {
            $leads = $leads->where('source', $source);
        }

        return response()->json([
            'success' => true,
            'data' => $leads->values(),
            'meta' => [
                'total' => $leads->count(),
                'per_page' => $perPage,
                'current_page' => 1
            ]
        ]);
    }

    /**
     * Store a newly created lead.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'status' => 'required|in:new,contacted,qualified,proposal,negotiation,closed_won,closed_lost',
            'source' => 'required|in:website,social_media,referral,cold_call,email_campaign,trade_show,other',
            'value' => 'nullable|numeric|min:0',
            'probability' => 'nullable|integer|min:0|max:100',
            'expected_close_date' => 'nullable|date|after:today'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $lead = array_merge($request->all(), [
            'id' => rand(1000, 9999),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lead created successfully',
            'data' => $lead
        ], 201);
    }

    /**
     * Display the specified lead.
     */
    public function show(int $id): JsonResponse
    {
        $lead = [
            'id' => $id,
            'name' => 'Jane Smith',
            'email' => 'jane@potential.com',
            'phone' => '+1555123456',
            'company' => 'Potential Corp',
            'status' => 'qualified',
            'source' => 'website',
            'value' => 50000,
            'probability' => 60,
            'expected_close_date' => now()->addDays(30),
            'notes' => 'Interested in enterprise solution',
            'activities' => [
                ['type' => 'call', 'date' => now()->subDays(1), 'notes' => 'Initial contact made'],
                ['type' => 'email', 'date' => now()->subDays(3), 'notes' => 'Sent product information']
            ],
            'created_at' => now()->subDays(3),
            'updated_at' => now()->subDays(1)
        ];

        return response()->json([
            'success' => true,
            'data' => $lead
        ]);
    }

    /**
     * Update the specified lead.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'status' => 'sometimes|required|in:new,contacted,qualified,proposal,negotiation,closed_won,closed_lost',
            'source' => 'sometimes|required|in:website,social_media,referral,cold_call,email_campaign,trade_show,other',
            'value' => 'nullable|numeric|min:0',
            'probability' => 'nullable|integer|min:0|max:100',
            'expected_close_date' => 'nullable|date|after:today'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $lead = array_merge([
            'id' => $id,
            'name' => 'Jane Smith',
            'email' => 'jane@potential.com',
            'created_at' => now()->subDays(3)
        ], $request->all(), [
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lead updated successfully',
            'data' => $lead
        ]);
    }

    /**
     * Remove the specified lead.
     */
    public function destroy(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Lead deleted successfully'
        ]);
    }

    /**
     * Convert lead to contact.
     */
    public function convert(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Lead converted to contact successfully',
            'data' => [
                'contact_id' => rand(1000, 9999),
                'lead_id' => $id,
                'converted_at' => now()
            ]
        ]);
    }

    /**
     * Get lead pipeline statistics.
     */
    public function pipeline(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'stages' => [
                    ['name' => 'New', 'count' => 25, 'value' => 125000],
                    ['name' => 'Contacted', 'count' => 18, 'value' => 180000],
                    ['name' => 'Qualified', 'count' => 12, 'value' => 240000],
                    ['name' => 'Proposal', 'count' => 8, 'value' => 320000],
                    ['name' => 'Negotiation', 'count' => 5, 'value' => 275000],
                    ['name' => 'Closed Won', 'count' => 3, 'value' => 150000]
                ],
                'total_value' => 1290000,
                'conversion_rate' => 12.5,
                'average_deal_size' => 45000
            ]
        ]);
    }

    /**
     * Get lead sources analytics.
     */
    public function sources(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                ['source' => 'website', 'count' => 45, 'conversion_rate' => 15.2],
                ['source' => 'referral', 'count' => 32, 'conversion_rate' => 28.1],
                ['source' => 'social_media', 'count' => 28, 'conversion_rate' => 8.9],
                ['source' => 'email_campaign', 'count' => 22, 'conversion_rate' => 12.7],
                ['source' => 'cold_call', 'count' => 18, 'conversion_rate' => 22.2],
                ['source' => 'trade_show', 'count' => 12, 'conversion_rate' => 33.3]
            ]
        ]);
    }
}
