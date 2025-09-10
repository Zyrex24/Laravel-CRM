<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\Organization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $totalContacts = Contact::count();
        $activeContacts = Contact::where('status', 'active')->count();
        
        $totalLeads = Lead::count();
        $activeLeads = Lead::whereIn('status', ['new', 'qualified', 'proposal', 'negotiation'])->count();
        $closedWonLeads = Lead::where('status', 'closed_won')->count();
        $conversionRate = $totalLeads > 0 ? round(($closedWonLeads / $totalLeads) * 100, 2) : 0;
        
        $totalOrganizations = Organization::count();
        $activeOrganizations = Organization::where('status', 'active')->count();
        
        $totalActivities = Activity::count();
        $pendingActivities = Activity::where('status', 'pending')->count();
        $completedActivities = Activity::where('status', 'completed')->count();
        
        $totalRevenue = Lead::where('status', 'closed_won')->sum('value') ?? 0;
        $pipelineValue = Lead::whereIn('status', ['new', 'qualified', 'proposal', 'negotiation'])->sum('value') ?? 0;
        
        $recentActivities = Activity::with(['contact', 'lead', 'organization', 'assignedTo', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        $recentLeads = Lead::with(['contact', 'organization', 'assignedTo'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        $leadsByStatus = Lead::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');
        
        $activitiesByType = Activity::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->get()
            ->pluck('count', 'type');

        // Monthly revenue data
        $monthlyRevenue = $this->getMonthlyRevenueData();

        return response()->json([
            'statistics' => [
                'contacts' => [
                    'total' => $totalContacts,
                    'active' => $activeContacts,
                ],
                'leads' => [
                    'total' => $totalLeads,
                    'active' => $activeLeads,
                    'closed_won' => $closedWonLeads,
                    'conversion_rate' => $conversionRate,
                ],
                'organizations' => [
                    'total' => $totalOrganizations,
                    'active' => $activeOrganizations,
                ],
                'activities' => [
                    'total' => $totalActivities,
                    'pending' => $pendingActivities,
                    'completed' => $completedActivities,
                ],
                'revenue' => [
                    'total' => $totalRevenue,
                    'pipeline' => $pipelineValue,
                ],
            ],
            'recent_activities' => $recentActivities,
            'recent_leads' => $recentLeads,
            'charts' => [
                'leads_by_status' => $leadsByStatus,
                'activities_by_type' => $activitiesByType,
                'monthly_revenue' => $monthlyRevenue,
            ],
            'api_status' => [
                'contacts' => 'operational',
                'leads' => 'operational',
                'activities' => 'operational',
                'organizations' => 'operational',
            ],
            'system_info' => [
                'laravel_version' => app()->version(),
                'php_version' => phpversion(),
                'environment' => app()->environment(),
            ],
        ]);
    }

    private function getMonthlyRevenueData(): array
    {
        $months = [];
        $revenue = [];
        $pipeline = [];
        
        // Get last 6 months data
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();
            
            // Month label
            $months[] = $date->format('M');
            
            // Revenue from closed won leads in this month
            $monthRevenue = Lead::where('status', 'closed_won')
                ->whereBetween('updated_at', [$monthStart, $monthEnd])
                ->sum('value');
            $revenue[] = (float) $monthRevenue;
            
            // Pipeline value (all active leads at end of month)
            $monthPipeline = Lead::whereIn('status', ['new', 'qualified', 'proposal', 'negotiation'])
                ->where('created_at', '<=', $monthEnd)
                ->sum('value');
            $pipeline[] = (float) $monthPipeline;
        }
        
        return [
            'labels' => $months,
            'revenue' => $revenue,
            'pipeline' => $pipeline
        ];
    }
}