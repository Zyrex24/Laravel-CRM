<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\Activity;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    /**
     * Search across all CRM entities
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        
        if (empty(trim($query))) {
            return response()->json([]);
        }
        
        $results = [];
        
        // Search contacts
        $contacts = Contact::where('first_name', 'LIKE', "%{$query}%")
            ->orWhere('last_name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->orWhere('company', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get();
            
        foreach ($contacts as $contact) {
            $results[] = [
                'id' => $contact->id,
                'type' => 'contact',
                'title' => $contact->first_name . ' ' . $contact->last_name,
                'description' => $contact->email . ($contact->company ? ' - ' . $contact->company : ''),
            ];
        }
        
        // Search leads
        $leads = Lead::where('title', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->orWhere('source', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get();
            
        foreach ($leads as $lead) {
            $results[] = [
                'id' => $lead->id,
                'type' => 'lead',
                'title' => $lead->title,
                'description' => $lead->description ?: 'Lead - ' . $lead->status,
            ];
        }
        
        // Search activities
        $activities = Activity::where('title', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get();
            
        foreach ($activities as $activity) {
            $results[] = [
                'id' => $activity->id,
                'type' => 'activity',
                'title' => $activity->title,
                'description' => $activity->description ?: 'Activity - ' . $activity->type,
            ];
        }
        
        // Search organizations
        $organizations = Organization::where('name', 'LIKE', "%{$query}%")
            ->orWhere('industry', 'LIKE', "%{$query}%")
            ->orWhere('website', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get();
            
        foreach ($organizations as $organization) {
            $results[] = [
                'id' => $organization->id,
                'type' => 'organization',
                'title' => $organization->name,
                'description' => $organization->industry ?: 'Organization',
            ];
        }
        
        // Sort results by relevance (you can implement more sophisticated scoring)
        usort($results, function($a, $b) use ($query) {
            $aScore = stripos($a['title'], $query) !== false ? 1 : 0;
            $bScore = stripos($b['title'], $query) !== false ? 1 : 0;
            return $bScore - $aScore;
        });
        
        return response()->json(array_slice($results, 0, 10));
    }
}