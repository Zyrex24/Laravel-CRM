<?php

namespace CRM\Lead\Models;

use CRM\Core\Models\BaseModel;
use CRM\User\Models\User;

class Stage extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'probability',
        'color',
        'is_closed',
        'is_won',
        'sort_order',
        'pipeline_id',
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
            'probability' => 'integer',
            'is_closed' => 'boolean',
            'is_won' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Get the pipeline this stage belongs to.
     */
    public function pipeline()
    {
        return $this->belongsTo(Pipeline::class);
    }

    /**
     * Get the leads in this stage.
     */
    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    /**
     * Get the user who created this stage.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this stage.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the total value of leads in this stage.
     *
     * @return float
     */
    public function getTotalValueAttribute(): float
    {
        return $this->leads()->sum('value') ?? 0;
    }

    /**
     * Get the weighted value of leads in this stage.
     *
     * @return float
     */
    public function getWeightedValueAttribute(): float
    {
        return $this->leads()->get()->sum('weighted_value') ?? 0;
    }

    /**
     * Get the number of leads in this stage.
     *
     * @return int
     */
    public function getLeadsCountAttribute(): int
    {
        return $this->leads()->count();
    }

    /**
     * Get the average time leads spend in this stage.
     *
     * @return float
     */
    public function getAverageTimeInStageAttribute(): float
    {
        $stageHistory = LeadStageHistory::where('to_stage_id', $this->id)
            ->whereNotNull('duration_days')
            ->avg('duration_days');

        return $stageHistory ?? 0;
    }

    /**
     * Scope a query to only include open stages.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOpen($query)
    {
        return $query->where('is_closed', false);
    }

    /**
     * Scope a query to only include closed stages.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeClosed($query)
    {
        return $query->where('is_closed', true);
    }

    /**
     * Scope a query to only include won stages.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWon($query)
    {
        return $query->where('is_closed', true)->where('is_won', true);
    }

    /**
     * Scope a query to only include lost stages.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLost($query)
    {
        return $query->where('is_closed', true)->where('is_won', false);
    }
}
