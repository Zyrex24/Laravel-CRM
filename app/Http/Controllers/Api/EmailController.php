<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class EmailController extends Controller
{
    /**
     * Display a listing of emails.
     */
    public function index(Request $request): JsonResponse
    {
        $type = $request->get('type', 'all'); // inbox, sent, draft
        $contact_id = $request->get('contact_id');
        $perPage = $request->get('per_page', 15);

        // Mock data
        $emails = collect([
            [
                'id' => 1,
                'subject' => 'Project Proposal Discussion',
                'from' => 'john@example.com',
                'to' => 'admin@crm.com',
                'type' => 'inbox',
                'status' => 'read',
                'contact_id' => 1,
                'contact_name' => 'John Doe',
                'body_preview' => 'Thank you for the detailed proposal. I have a few questions...',
                'has_attachments' => true,
                'received_at' => now()->subHours(2),
                'created_at' => now()->subHours(2)
            ],
            [
                'id' => 2,
                'subject' => 'Follow-up on Meeting',
                'from' => 'admin@crm.com',
                'to' => 'jane@potential.com',
                'type' => 'sent',
                'status' => 'sent',
                'contact_id' => 2,
                'contact_name' => 'Jane Smith',
                'body_preview' => 'Hi Jane, Following up on our meeting yesterday...',
                'has_attachments' => false,
                'sent_at' => now()->subDays(1),
                'created_at' => now()->subDays(1)
            ]
        ]);

        if ($type !== 'all') {
            $emails = $emails->where('type', $type);
        }

        if ($contact_id) {
            $emails = $emails->where('contact_id', (int)$contact_id);
        }

        return response()->json([
            'success' => true,
            'data' => $emails->values(),
            'meta' => [
                'total' => $emails->count(),
                'per_page' => $perPage,
                'current_page' => 1
            ]
        ]);
    }

    /**
     * Send a new email.
     */
    public function send(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'to' => 'required|email',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'cc' => 'nullable|array',
            'cc.*' => 'email',
            'bcc' => 'nullable|array',
            'bcc.*' => 'email',
            'contact_id' => 'nullable|integer|exists:contacts,id',
            'template_id' => 'nullable|integer|exists:email_templates,id',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240' // 10MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Mock email sending
        $email = [
            'id' => rand(1000, 9999),
            'to' => $request->get('to'),
            'subject' => $request->get('subject'),
            'body' => $request->get('body'),
            'cc' => $request->get('cc', []),
            'bcc' => $request->get('bcc', []),
            'contact_id' => $request->get('contact_id'),
            'status' => 'sent',
            'sent_at' => now(),
            'created_at' => now()
        ];

        return response()->json([
            'success' => true,
            'message' => 'Email sent successfully',
            'data' => $email
        ], 201);
    }

    /**
     * Display the specified email.
     */
    public function show(int $id): JsonResponse
    {
        $email = [
            'id' => $id,
            'subject' => 'Project Proposal Discussion',
            'from' => 'john@example.com',
            'to' => 'admin@crm.com',
            'cc' => [],
            'bcc' => [],
            'body' => 'Thank you for the detailed proposal. I have reviewed the requirements and have a few questions about the timeline and budget allocation.',
            'type' => 'inbox',
            'status' => 'read',
            'contact_id' => 1,
            'contact_name' => 'John Doe',
            'thread_id' => 'thread_123',
            'attachments' => [
                ['name' => 'requirements.pdf', 'size' => '1.2MB', 'type' => 'application/pdf'],
                ['name' => 'budget_breakdown.xlsx', 'size' => '456KB', 'type' => 'application/vnd.ms-excel']
            ],
            'tracking' => [
                'opened' => true,
                'opened_at' => now()->subHours(1),
                'clicks' => 2,
                'last_clicked_at' => now()->subMinutes(30)
            ],
            'received_at' => now()->subHours(2),
            'created_at' => now()->subHours(2)
        ];

        return response()->json([
            'success' => true,
            'data' => $email
        ]);
    }

    /**
     * Reply to an email.
     */
    public function reply(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'body' => 'required|string',
            'cc' => 'nullable|array',
            'cc.*' => 'email',
            'bcc' => 'nullable|array',
            'bcc.*' => 'email',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $reply = [
            'id' => rand(1000, 9999),
            'original_email_id' => $id,
            'subject' => 'Re: Project Proposal Discussion',
            'body' => $request->get('body'),
            'status' => 'sent',
            'sent_at' => now(),
            'created_at' => now()
        ];

        return response()->json([
            'success' => true,
            'message' => 'Reply sent successfully',
            'data' => $reply
        ], 201);
    }

    /**
     * Forward an email.
     */
    public function forward(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'to' => 'required|email',
            'body' => 'nullable|string',
            'cc' => 'nullable|array',
            'cc.*' => 'email',
            'bcc' => 'nullable|array',
            'bcc.*' => 'email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $forward = [
            'id' => rand(1000, 9999),
            'original_email_id' => $id,
            'to' => $request->get('to'),
            'subject' => 'Fwd: Project Proposal Discussion',
            'body' => $request->get('body', ''),
            'status' => 'sent',
            'sent_at' => now(),
            'created_at' => now()
        ];

        return response()->json([
            'success' => true,
            'message' => 'Email forwarded successfully',
            'data' => $forward
        ], 201);
    }

    /**
     * Get email templates.
     */
    public function templates(): JsonResponse
    {
        $templates = [
            [
                'id' => 1,
                'name' => 'Welcome Email',
                'subject' => 'Welcome to our CRM system',
                'category' => 'onboarding',
                'variables' => ['name', 'company'],
                'created_at' => now()->subDays(10)
            ],
            [
                'id' => 2,
                'name' => 'Follow-up Template',
                'subject' => 'Following up on our conversation',
                'category' => 'sales',
                'variables' => ['name', 'meeting_date', 'next_steps'],
                'created_at' => now()->subDays(5)
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $templates
        ]);
    }

    /**
     * Get email statistics.
     */
    public function stats(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'total_emails' => 1250,
                'sent' => 780,
                'received' => 470,
                'drafts' => 15,
                'open_rate' => 68.5,
                'click_rate' => 12.3,
                'reply_rate' => 8.7,
                'bounce_rate' => 2.1,
                'recent_activity' => [
                    'today' => 45,
                    'this_week' => 280,
                    'this_month' => 1120
                ]
            ]
        ]);
    }

    /**
     * Get email thread.
     */
    public function thread(string $threadId): JsonResponse
    {
        $emails = [
            [
                'id' => 1,
                'subject' => 'Project Proposal Discussion',
                'from' => 'john@example.com',
                'to' => 'admin@crm.com',
                'body_preview' => 'Initial inquiry about the project...',
                'sent_at' => now()->subDays(3)
            ],
            [
                'id' => 2,
                'subject' => 'Re: Project Proposal Discussion',
                'from' => 'admin@crm.com',
                'to' => 'john@example.com',
                'body_preview' => 'Thank you for your interest. Here are the details...',
                'sent_at' => now()->subDays(2)
            ],
            [
                'id' => 3,
                'subject' => 'Re: Project Proposal Discussion',
                'from' => 'john@example.com',
                'to' => 'admin@crm.com',
                'body_preview' => 'I have a few questions about the timeline...',
                'sent_at' => now()->subHours(2)
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'thread_id' => $threadId,
                'emails' => $emails,
                'participants' => ['john@example.com', 'admin@crm.com'],
                'total_emails' => count($emails)
            ]
        ]);
    }

    /**
     * Mark email as read/unread.
     */
    public function markAsRead(int $id, Request $request): JsonResponse
    {
        $read = $request->get('read', true);
        
        return response()->json([
            'success' => true,
            'message' => $read ? 'Email marked as read' : 'Email marked as unread',
            'data' => [
                'id' => $id,
                'status' => $read ? 'read' : 'unread',
                'read_at' => $read ? now() : null
            ]
        ]);
    }
}
