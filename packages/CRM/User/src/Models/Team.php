<?php

namespace CRM\User\Models;

use CRM\Core\Models\BaseModel;

class Team extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'teams';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'color',
        'status',
        'lead_assignment_strategy',
        'manager_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => 'string',
        ];
    }

    /**
     * Get the users that belong to the team.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the team manager.
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Get the leads assigned to this team.
     */
    public function leads()
    {
        return $this->hasManyThrough(
            \CRM\Lead\Models\Lead::class,
            User::class,
            'team_id',
            'assigned_to'
        );
    }
}
