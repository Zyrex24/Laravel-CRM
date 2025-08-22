<?php

namespace CRM\Lead\Models;

use CRM\Core\Models\BaseModel;
use CRM\User\Models\User;

class LeadStageHistory extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lead_stage_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lead_id',
        'from_stage_id',
        'to_stage_id',
        'reason',
        'duration_days',
        'changed_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'duration_days' => 'integer',
        ];
    }

    /**
     * Get the lead this history belongs to.
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    /**
     * Get the stage the lead moved from.
     */
    public function fromStage()
    {
        return $this->belongsTo(Stage::class, 'from_stage_id');
    }

    /**
     * Get the stage the lead moved to.
     */
    public function toStage()
    {
        return $this->belongsTo(Stage::class, 'to_stage_id');
    }

    /**
     * Get the user who made the change.
     */
    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if ($model->from_stage_id) {
                // Calculate duration in the previous stage
                $lastHistory = static::where('lead_id', $model->lead_id)
                    ->where('to_stage_id', $model->from_stage_id)
                    ->latest()
                    ->first();

                if ($lastHistory) {
                    $model->duration_days = $lastHistory->created_at->diffInDays(now());
                }
            }
        });
    }
}
