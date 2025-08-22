<?php

namespace CRM\Lead\Models;

use CRM\Core\Models\BaseModel;
use CRM\User\Models\User;

class Pipeline extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pipelines';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'is_default',
        'is_active',
        'color',
        'sort_order',
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
            'is_default' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Get the stages for this pipeline.
     */
    public function stages()
    {
        return $this->hasMany(Stage::class)->orderBy('sort_order');
    }

    /**
     * Get the leads in this pipeline.
     */
    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    /**
     * Get the user who created this pipeline.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this pipeline.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the total value of leads in this pipeline.
     *
     * @return float
     */
    public function getTotalValueAttribute(): float
    {
        return $this->leads()->sum('value') ?? 0;
    }

    /**
     * Get the weighted value of leads in this pipeline.
     *
     * @return float
     */
    public function getWeightedValueAttribute(): float
    {
        return $this->leads()->get()->sum('weighted_value') ?? 0;
    }

    /**
     * Get the number of leads in this pipeline.
     *
     * @return int
     */
    public function getLeadsCountAttribute(): int
    {
        return $this->leads()->count();
    }

    /**
     * Get the conversion rate for this pipeline.
     *
     * @return float
     */
    public function getConversionRateAttribute(): float
    {
        $totalLeads = $this->leads()->count();
        if ($totalLeads === 0) {
            return 0;
        }

        $wonLeads = $this->leads()->won()->count();
        return ($wonLeads / $totalLeads) * 100;
    }

    /**
     * Scope a query to only include active pipelines.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include default pipeline.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
