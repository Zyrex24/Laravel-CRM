<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request): JsonResponse
    {
        $role = $request->get('role', 'all');
        $status = $request->get('status', 'all');
        $perPage = $request->get('per_page', 15);

        // Mock data
        $users = collect([
            [
                'id' => 1,
                'name' => 'Admin User',
                'email' => 'admin@crm.com',
                'role' => 'admin',
                'status' => 'active',
                'avatar' => null,
                'last_login' => now()->subHours(1),
                'created_at' => now()->subMonths(6)
            ],
            [
                'id' => 2,
                'name' => 'Sales Manager',
                'email' => 'sales@crm.com',
                'role' => 'manager',
                'status' => 'active',
                'avatar' => 'avatars/sales-manager.jpg',
                'last_login' => now()->subHours(3),
                'created_at' => now()->subMonths(3)
            ],
            [
                'id' => 3,
                'name' => 'Sales Rep',
                'email' => 'rep@crm.com',
                'role' => 'user',
                'status' => 'active',
                'avatar' => null,
                'last_login' => now()->subDays(1),
                'created_at' => now()->subMonth()
            ]
        ]);

        if ($role !== 'all') {
            $users = $users->where('role', $role);
        }

        if ($status !== 'all') {
            $users = $users->where('status', $status);
        }

        return response()->json([
            'success' => true,
            'data' => $users->values(),
            'meta' => [
                'total' => $users->count(),
                'per_page' => $perPage,
                'current_page' => 1
            ]
        ]);
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,manager,user',
            'status' => 'sometimes|in:active,inactive,suspended'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = [
            'id' => rand(1000, 9999),
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'role' => $request->get('role'),
            'status' => $request->get('status', 'active'),
            'avatar' => null,
            'last_login' => null,
            'created_at' => now(),
            'updated_at' => now()
        ];

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], 201);
    }

    /**
     * Display the specified user.
     */
    public function show(int $id): JsonResponse
    {
        $user = [
            'id' => $id,
            'name' => 'Admin User',
            'email' => 'admin@crm.com',
            'role' => 'admin',
            'status' => 'active',
            'avatar' => null,
            'permissions' => [
                'contacts.view', 'contacts.create', 'contacts.edit', 'contacts.delete',
                'leads.view', 'leads.create', 'leads.edit', 'leads.delete',
                'activities.view', 'activities.create', 'activities.edit', 'activities.delete',
                'emails.view', 'emails.send', 'users.manage', 'settings.manage'
            ],
            'preferences' => [
                'timezone' => 'UTC',
                'language' => 'en',
                'notifications_email' => true,
                'notifications_browser' => true
            ],
            'stats' => [
                'contacts_created' => 45,
                'leads_converted' => 12,
                'activities_completed' => 156,
                'emails_sent' => 89
            ],
            'last_login' => now()->subHours(1),
            'created_at' => now()->subMonths(6),
            'updated_at' => now()->subDays(2)
        ];

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
            'password' => 'sometimes|required|string|min:8|confirmed',
            'role' => 'sometimes|required|in:admin,manager,user',
            'status' => 'sometimes|in:active,inactive,suspended'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = array_merge([
            'id' => $id,
            'name' => 'Admin User',
            'email' => 'admin@crm.com',
            'role' => 'admin',
            'created_at' => now()->subMonths(6)
        ], $request->except(['password', 'password_confirmation']), [
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $user
        ]);
    }

    /**
     * Remove the specified user.
     */
    public function destroy(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }

    /**
     * Upload user avatar.
     */
    public function uploadAvatar(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Mock avatar upload
        $avatarPath = 'avatars/user_' . $id . '_' . time() . '.jpg';

        return response()->json([
            'success' => true,
            'message' => 'Avatar uploaded successfully',
            'data' => [
                'avatar_url' => $avatarPath,
                'uploaded_at' => now()
            ]
        ]);
    }

    /**
     * Update user permissions.
     */
    public function updatePermissions(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'permissions' => 'required|array',
            'permissions.*' => 'string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'User permissions updated successfully',
            'data' => [
                'user_id' => $id,
                'permissions' => $request->get('permissions'),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Update user preferences.
     */
    public function updatePreferences(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'timezone' => 'sometimes|string|max:50',
            'language' => 'sometimes|string|max:10',
            'notifications_email' => 'sometimes|boolean',
            'notifications_browser' => 'sometimes|boolean',
            'theme' => 'sometimes|in:light,dark,auto'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'User preferences updated successfully',
            'data' => [
                'user_id' => $id,
                'preferences' => $request->all(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Get user roles and permissions.
     */
    public function rolesAndPermissions(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'roles' => [
                    [
                        'name' => 'admin',
                        'display_name' => 'Administrator',
                        'description' => 'Full system access'
                    ],
                    [
                        'name' => 'manager',
                        'display_name' => 'Manager',
                        'description' => 'Team management and reporting'
                    ],
                    [
                        'name' => 'user',
                        'display_name' => 'User',
                        'description' => 'Standard user access'
                    ]
                ],
                'permissions' => [
                    'contacts.view', 'contacts.create', 'contacts.edit', 'contacts.delete',
                    'leads.view', 'leads.create', 'leads.edit', 'leads.delete',
                    'activities.view', 'activities.create', 'activities.edit', 'activities.delete',
                    'emails.view', 'emails.send', 'users.manage', 'settings.manage',
                    'reports.view', 'exports.create'
                ]
            ]
        ]);
    }

    /**
     * Get user activity log.
     */
    public function activityLog(int $id): JsonResponse
    {
        $activities = [
            [
                'id' => 1,
                'action' => 'login',
                'description' => 'User logged in',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0...',
                'created_at' => now()->subHours(1)
            ],
            [
                'id' => 2,
                'action' => 'contact.create',
                'description' => 'Created new contact: John Doe',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0...',
                'created_at' => now()->subHours(2)
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $activities
        ]);
    }
}
