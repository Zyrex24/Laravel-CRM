<?php

namespace CRM\Activity\Models;

use CRM\Core\Models\BaseModel;
use CRM\User\Models\User;

class Activity extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'activities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'type',
        'status',
        'priority',
        'outcome',
        'scheduled_at',
        'started_at',
        'completed_at',
        'duration_minutes',
        'location',
        'meeting_url',
        'reminder_at',
        'reminder_sent',
        'is_all_day',
        'is_recurring',
        'recurring_pattern',
        'recurring_until',
        'parent_activity_id',
        'related_type',
        'related_id',
        'assigned_to',
        'created_by',
        'updated_by',
        'notes',
        'custom_attributes',
        'tags',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'reminder_at' => 'datetime',
            'recurring_until' => 'datetime',
            'reminder_sent' => 'boolean',
            'is_all_day' => 'boolean',
            'is_recurring' => 'boolean',
            'duration_minutes' => 'integer',
            'custom_attributes' => 'array',
            'tags' => 'array',
            'recurring_pattern' => 'array',
        ];
    }

    /**
     * Get the related entity (polymorphic relationship).
     */
    public function related()
    {
        return $this->morphTo();
    }

    /**
     * Get the user assigned to this activity.
     */
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the user who created this activity.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this activity.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the parent activity (for recurring activities).
     */
    public function parentActivity()
    {
        return $this->belongsTo(Activity::class, 'parent_activity_id');
    }

    /**
     * Get the child activities (for recurring activities).
     */
    public function childActivities()
    {
        return $this->hasMany(Activity::class, 'parent_activity_id');
    }

    /**
     * Get the activity type configuration.
     *
     * @return array
     */
    public function getTypeConfigAttribute(): array
    {
        $types = config('crm.activity.types', []);
        return $types[$this->type] ?? [];
    }

    /**
     * Get the activity type name.
     *
     * @return string
     */
    public function getTypeNameAttribute(): string
    {
        return $this->type_config['name'] ?? ucfirst($this->type);
    }

    /**
     * Get the activity type icon.
     *
     * @return string
     */
    public function getTypeIconAttribute(): string
    {
        return $this->type_config['icon'] ?? 'calendar';
    }

    /**
     * Get the activity type color.
     *
     * @return string
     */
    public function getTypeColorAttribute(): string
    {
        return $this->type_config['color'] ?? '#6B7280';
    }

    /**
     * Get the end time of the activity.
     *
     * @return \Carbon\Carbon|null
     */
    public function getEndTimeAttribute()
    {
        if (!$this->scheduled_at || !$this->duration_minutes) {
            return null;
        }
        return $this->scheduled_at->addMinutes($this->duration_minutes);
    }

    /**
     * Check if the activity is overdue.
     *
     * @return bool
     */
    public function getIsOverdueAttribute(): bool
    {
        if ($this->status === 'completed' || $this->status === 'cancelled') {
            return false;
        }
        return $this->scheduled_at && $this->scheduled_at->isPast();
    }

    /**
     * Check if the activity is due today.
     *
     * @return bool
     */
    public function getIsDueTodayAttribute(): bool
    {
        return $this->scheduled_at && $this->scheduled_at->isToday();
    }

    /**
     * Check if the activity is due this week.
     *
     * @return bool
     */
    public function getIsDueThisWeekAttribute(): bool
    {
        return $this->scheduled_at && $this->scheduled_at->isCurrentWeek();
    }

    /**
     * Get the duration in human readable format.
     *
     * @return string
     */
    public function getFormattedDurationAttribute(): string
    {
        if (!$this->duration_minutes) {
            return 'No duration set';
        }

        $hours = intval($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        if ($hours > 0 && $minutes > 0) {
            return "{$hours}h {$minutes}m";
        } elseif ($hours > 0) {
            return "{$hours}h";
        } else {
            return "{$minutes}m";
        }
    }

    /**
     * Scope a query to only include scheduled activities.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    /**
     * Scope a query to only include completed activities.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include overdue activities.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', '!=', 'completed')
                    ->where('status', '!=', 'cancelled')
                    ->where('scheduled_at', '<', now());
    }

    /**
     * Scope a query to only include activities due today.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDueToday($query)
    {
        return $query->whereDate('scheduled_at', today());
    }

    /**
     * Scope a query to only include activities due this week.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDueThisWeek($query)
    {
        return $query->whereBetween('scheduled_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    /**
     * Scope a query to only include activities of a specific type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to search activities.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('notes', 'like', "%{$search}%");
        });
    }

    /**
     * Mark the activity as completed.
     *
     * @param string|null $outcome
     * @param string|null $notes
     * @return void
     */
    public function markAsCompleted(?string $outcome = null, ?string $notes = null): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'outcome' => $outcome ?: $this->outcome,
            'notes' => $notes ?: $this->notes,
        ]);

        // Update related lead's last activity timestamp
        if ($this->related_type && $this->related_id) {
            $relatedModel = $this->related_type::find($this->related_id);
            if ($relatedModel && method_exists($relatedModel, 'update')) {
                $relatedModel->update(['last_activity_at' => now()]);
            }
        }
    }

    /**
     * Mark the activity as cancelled.
     *
     * @param string|null $reason
     * @return void
     */
    public function markAsCancelled(?string $reason = null): void
    {
        $this->update([
            'status' => 'cancelled',
            'notes' => $reason ? ($this->notes . "\n\nCancelled: " . $reason) : $this->notes,
        ]);
    }

    /**
     * Reschedule the activity.
     *
     * @param \Carbon\Carbon $newDateTime
     * @param string|null $reason
     * @return void
     */
    public function reschedule($newDateTime, ?string $reason = null): void
    {
        $oldDateTime = $this->scheduled_at;
        
        $this->update([
            'scheduled_at' => $newDateTime,
            'reminder_sent' => false,
            'notes' => $reason ? ($this->notes . "\n\nRescheduled from {$oldDateTime} to {$newDateTime}: " . $reason) : $this->notes,
        ]);
    }

    /**
     * Create recurring activities based on the pattern.
     *
     * @param int $occurrences
     * @return void
     */
    public function createRecurringActivities(int $occurrences = 10): void
    {
        if (!$this->is_recurring || !$this->recurring_pattern) {
            return;
        }

        $pattern = $this->recurring_pattern;
        $frequency = $pattern['frequency'] ?? 'weekly';
        $interval = $pattern['interval'] ?? 1;
        
        $currentDate = $this->scheduled_at->copy();
        
        for ($i = 0; $i < $occurrences; $i++) {
            switch ($frequency) {
                case 'daily':
                    $currentDate->addDays($interval);
                    break;
                case 'weekly':
                    $currentDate->addWeeks($interval);
                    break;
                case 'monthly':
                    $currentDate->addMonths($interval);
                    break;
                case 'yearly':
                    $currentDate->addYears($interval);
                    break;
            }

            if ($this->recurring_until && $currentDate->gt($this->recurring_until)) {
                break;
            }

            $this->childActivities()->create([
                'title' => $this->title,
                'description' => $this->description,
                'type' => $this->type,
                'status' => 'scheduled',
                'priority' => $this->priority,
                'scheduled_at' => $currentDate->copy(),
                'duration_minutes' => $this->duration_minutes,
                'location' => $this->location,
                'meeting_url' => $this->meeting_url,
                'related_type' => $this->related_type,
                'related_id' => $this->related_id,
                'assigned_to' => $this->assigned_to,
                'created_by' => $this->created_by,
                'custom_attributes' => $this->custom_attributes,
                'tags' => $this->tags,
            ]);
        }
    }
}
