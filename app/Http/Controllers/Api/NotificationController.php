<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Lead;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $notifications = [];
            
            // Recent activities (last 7 days)
            $recentActivities = Activity::with(['contact', 'lead'])
                ->where('created_at', '>=', Carbon::now()->subDays(7))
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
                
            foreach ($recentActivities as $activity) {
                $notifications[] = [
                    'id' => "activity_{$activity->id}",
                    'type' => 'activity',
                    'title' => 'New Activity',
                    'message' => "Activity scheduled: {$activity->title}",
                    'data' => [
                        'activity_id' => $activity->id,
                        'activity_type' => $activity->type,
                        'due_date' => $activity->due_date,
                    ],
                    'timestamp' => $activity->created_at,
                    'read' => false
                ];
            }
            
            // Recent leads (last 7 days)
            $recentLeads = Lead::with(['contact', 'organization'])
                ->where('created_at', '>=', Carbon::now()->subDays(7))
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
                
            foreach ($recentLeads as $lead) {
                $notifications[] = [
                    'id' => "lead_{$lead->id}",
                    'type' => 'lead',
                    'title' => 'New Lead',
                    'message' => "New lead created: {$lead->title} (\${$lead->value})",
                    'data' => [
                        'lead_id' => $lead->id,
                        'status' => $lead->status,
                        'value' => $lead->value,
                    ],
                    'timestamp' => $lead->created_at,
                    'read' => false
                ];
            }
            
            // Recent contacts (last 7 days)
            $recentContacts = Contact::where('created_at', '>=', Carbon::now()->subDays(7))
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
                
            foreach ($recentContacts as $contact) {
                $notifications[] = [
                    'id' => "contact_{$contact->id}",
                    'type' => 'contact',
                    'title' => 'New Contact',
                    'message' => "New contact added: {$contact->full_name} from {$contact->company}",
                    'data' => [
                        'contact_id' => $contact->id,
                        'email' => $contact->email,
                        'company' => $contact->company,
                    ],
                    'timestamp' => $contact->created_at,
                    'read' => false
                ];
            }
            
            // System notifications
            $systemNotifications = $this->generateSystemNotifications();
            $notifications = array_merge($notifications, $systemNotifications);
            
            // Sort by timestamp descending and limit to latest 20
            usort($notifications, function($a, $b) {
                return strtotime($b['timestamp']) - strtotime($a['timestamp']);
            });
            
            $notifications = array_slice($notifications, 0, 20);
            
            return response()->json($notifications);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load notifications',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    private function generateSystemNotifications(): array
    {
        $notifications = [];
        
        // Check for overdue activities
        $overdueActivities = Activity::where('due_date', '<', Carbon::now())
            ->where('status', '!=', 'completed')
            ->count();
            
        if ($overdueActivities > 0) {
            $notifications[] = [
                'id' => 'system_overdue_activities',
                'type' => 'warning',
                'title' => 'Overdue Activities',
                'message' => "You have {$overdueActivities} overdue activities",
                'data' => ['count' => $overdueActivities],
                'timestamp' => Carbon::now()->toISOString(),
                'read' => false
            ];
        }
        
        // Check for high-value leads without recent activity
        $staleHighValueLeads = Lead::where('value', '>', 10000)
            ->where('status', 'in', ['new', 'qualified'])
            ->whereDoesntHave('activities', function($query) {
                $query->where('created_at', '>=', Carbon::now()->subDays(7));
            })
            ->count();
            
        if ($staleHighValueLeads > 0) {
            $notifications[] = [
                'id' => 'system_stale_leads',
                'type' => 'info',
                'title' => 'Follow-up Needed',
                'message' => "{$staleHighValueLeads} high-value leads need follow-up",
                'data' => ['count' => $staleHighValueLeads],
                'timestamp' => Carbon::now()->subHour()->toISOString(),
                'read' => false
            ];
        }
        
        return $notifications;
    }
    
    public function markAsRead(Request $request, string $notificationId): JsonResponse
    {
        // For demo purposes, just return success
        // In a real system, you'd update the notification status in the database
        return response()->json(['success' => true]);
    }
    
    public function markAllAsRead(Request $request): JsonResponse
    {
        // For demo purposes, just return success
        return response()->json(['success' => true]);
    }
}