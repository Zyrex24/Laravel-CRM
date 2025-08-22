<?php

namespace CRM\Lead\Models;

use CRM\Core\Models\BaseModel;
use CRM\User\Models\User;
use CRM\Contact\Models\Person;
use CRM\Contact\Models\Organization;

class Lead extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'leads';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'contact_type',
        'contact_id',
        'value',
        'currency',
        'probability',
        'expected_close_date',
        'actual_close_date',
        'source',
        'type',
        'priority',
        'score',
        'last_activity_at',
        'next_follow_up_at',
        'is_rotten',
        'rotten_days',
        'tags',
        'custom_attributes',
        'notes',
        'pipeline_id',
        'stage_id',
        'assigned_to',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'probability' => 'integer',
            'expected_close_date' => 'date',
            'actual_close_date' => 'date',
            'last_activity_at' => 'datetime',
            'next_follow_up_at' => 'datetime',
            'is_rotten' => 'boolean',
            'rotten_days' => 'integer',
            'score' => 'integer',
            'tags' => 'array',
            'custom_attributes' => 'array',
        ];
    }

    /**
     * Get the contact (polymorphic relationship).
     */
    public function contact()
    {
        return $this->morphTo();
    }

    /**
     * Get the pipeline this lead belongs to.
     */
    public function pipeline()
    {
        return $this->belongsTo(Pipeline::class);
    }

    /**
     * Get the current stage of this lead.
     */
    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    /**
     * Get the user assigned to this lead.
     */
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the user who created this lead.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this lead.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the activities for this lead.
     */
    public function activities()
    {
        return $this->morphMany(\CRM\Activity\Models\Activity::class, 'related');
    }

    /**
     * Get the emails for this lead.
     */
    public function emails()
    {
        return $this->morphMany(\CRM\Email\Models\Email::class, 'related');
    }

    /**
     * Get the stage history for this lead.
     */
    public function stageHistory()
    {
        return $this->hasMany(LeadStageHistory::class);
    }

    /**
     * Get the weighted value of the lead.
     *
     * @return float
     */
    public function getWeightedValueAttribute(): float
    {
        return $this->value * ($this->probability / 100);
    }

    /**
     * Get the contact name.
     *
     * @return string
     */
    public function getContactNameAttribute(): string
    {
        if ($this->contact) {
            return $this->contact_type === Person::class 
                ? $this->contact->full_name 
                : $this->contact->name;
        }
        return 'Unknown Contact';
    }

    /**
     * Get the contact email.
     *
     * @return string|null
     */
    public function getContactEmailAttribute(): ?string
    {
        return $this->contact?->email;
    }

    /**
     * Get the days since last activity.
     *
     * @return int
     */
    public function getDaysSinceLastActivityAttribute(): int
    {
        if (!$this->last_activity_at) {
            return $this->created_at->diffInDays(now());
        }
        return $this->last_activity_at->diffInDays(now());
    }

    /**
     * Get the days until expected close.
     *
     * @return int|null
     */
    public function getDaysUntilExpectedCloseAttribute(): ?int
    {
        if (!$this->expected_close_date) {
            return null;
        }
        return now()->diffInDays($this->expected_close_date, false);
    }

    /**
     * Check if the lead is overdue.
     *
     * @return bool
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->expected_close_date && $this->expected_close_date->isPast();
    }

    /**
     * Check if the lead is won.
     *
     * @return bool
     */
    public function getIsWonAttribute(): bool
    {
        return $this->stage && $this->stage->is_closed && $this->stage->is_won;
    }

    /**
     * Check if the lead is lost.
     *
     * @return bool
     */
    public function getIsLostAttribute(): bool
    {
        return $this->stage && $this->stage->is_closed && !$this->stage->is_won;
    }

    /**
     * Check if the lead is open.
     *
     * @return bool
     */
    public function getIsOpenAttribute(): bool
    {
        return !$this->stage || !$this->stage->is_closed;
    }

    /**
     * Scope a query to only include open leads.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOpen($query)
    {
        return $query->whereHas('stage', function ($q) {
            $q->where('is_closed', false);
        });
    }

    /**
     * Scope a query to only include won leads.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWon($query)
    {
        return $query->whereHas('stage', function ($q) {
            $q->where('is_closed', true)->where('is_won', true);
        });
    }

    /**
     * Scope a query to only include lost leads.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLost($query)
    {
        return $query->whereHas('stage', function ($q) {
            $q->where('is_closed', true)->where('is_won', false);
        });
    }

    /**
     * Scope a query to only include rotten leads.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRotten($query)
    {
        return $query->where('is_rotten', true);
    }

    /**
     * Scope a query to only include high-score leads.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $threshold
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHighScore($query, int $threshold = 80)
    {
        return $query->where('score', '>=', $threshold);
    }

    /**
     * Scope a query to search leads.
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
              ->orWhereHas('contact', function ($contactQuery) use ($search) {
                  $contactQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
              });
        });
    }

    /**
     * Move the lead to a new stage.
     *
     * @param Stage $stage
     * @param string|null $reason
     * @return void
     */
    public function moveToStage(Stage $stage, ?string $reason = null): void
    {
        $oldStage = $this->stage;
        
        $this->update([
            'stage_id' => $stage->id,
            'probability' => $stage->probability,
        ]);

        // Record stage history
        $this->stageHistory()->create([
            'from_stage_id' => $oldStage?->id,
            'to_stage_id' => $stage->id,
            'reason' => $reason,
            'changed_by' => auth()->id(),
        ]);
    }

    /**
     * Calculate and update the lead score.
     *
     * @return void
     */
    public function calculateScore(): void
    {
        $score = 0;
        $factors = config('crm.lead.scoring.factors', []);

        // Calculate score based on activities
        foreach ($this->activities as $activity) {
            $activityType = $activity->type;
            if (isset($factors[$activityType])) {
                $score += $factors[$activityType];
            }
        }

        // Apply decay if enabled
        if (config('crm.lead.scoring.decay_enabled', true)) {
            $decayDays = config('crm.lead.scoring.decay_days', 30);
            $decayPercentage = config('crm.lead.scoring.decay_percentage', 10);
            
            if ($this->last_activity_at && $this->last_activity_at->diffInDays(now()) > $decayDays) {
                $score = $score * (1 - ($decayPercentage / 100));
            }
        }

        $maxScore = config('crm.lead.scoring.max_score', 100);
        $this->update(['score' => min($score, $maxScore)]);
    }

    /**
     * Check and update rotten status.
     *
     * @return void
     */
    public function checkRottenStatus(): void
    {
        $threshold = config('crm.lead.rotten_leads.days_threshold', 30);
        $daysSinceActivity = $this->days_since_last_activity;
        
        $this->update([
            'is_rotten' => $daysSinceActivity > $threshold,
            'rotten_days' => $daysSinceActivity > $threshold ? $daysSinceActivity : 0,
        ]);
    }
}
