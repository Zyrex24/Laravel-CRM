<?php

namespace CRM\Contact\Models;

use CRM\Core\Models\BaseModel;
use CRM\User\Models\User;

class Person extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'persons';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'title',
        'email',
        'phone',
        'mobile',
        'fax',
        'website',
        'avatar',
        'date_of_birth',
        'gender',
        'marital_status',
        'job_title',
        'department',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country',
        'timezone',
        'locale',
        'notes',
        'tags',
        'social_profiles',
        'custom_attributes',
        'lead_source',
        'lead_status',
        'is_vip',
        'do_not_call',
        'do_not_email',
        'email_opt_out',
        'owner_id',
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
            'date_of_birth' => 'date',
            'tags' => 'array',
            'social_profiles' => 'array',
            'custom_attributes' => 'array',
            'is_vip' => 'boolean',
            'do_not_call' => 'boolean',
            'do_not_email' => 'boolean',
            'email_opt_out' => 'boolean',
        ];
    }

    /**
     * Get the owner of the person.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the user who created the person.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the person.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the organizations this person belongs to.
     */
    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'person_organizations')
                    ->withPivot(['role', 'is_primary', 'start_date', 'end_date'])
                    ->withTimestamps();
    }

    /**
     * Get the primary organization for this person.
     */
    public function primaryOrganization()
    {
        return $this->belongsToMany(Organization::class, 'person_organizations')
                    ->wherePivot('is_primary', true)
                    ->withPivot(['role', 'is_primary', 'start_date', 'end_date'])
                    ->withTimestamps()
                    ->first();
    }

    /**
     * Get the activities for this person.
     */
    public function activities()
    {
        return $this->morphMany(\CRM\Activity\Models\Activity::class, 'related');
    }

    /**
     * Get the leads for this person.
     */
    public function leads()
    {
        return $this->morphMany(\CRM\Lead\Models\Lead::class, 'contact');
    }

    /**
     * Get the emails sent to this person.
     */
    public function emails()
    {
        return $this->morphMany(\CRM\Email\Models\Email::class, 'recipient');
    }

    /**
     * Get the full name attribute.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        $format = config('crm.contact.person.name_format', 'first_last');
        
        return match($format) {
            'last_first' => trim($this->last_name . ', ' . $this->first_name),
            'full' => trim($this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name),
            default => trim($this->first_name . ' ' . $this->last_name),
        };
    }

    /**
     * Get the display name attribute.
     *
     * @return string
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->title ? $this->title . ' ' . $this->full_name : $this->full_name;
    }

    /**
     * Get the avatar URL attribute.
     *
     * @return string
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/avatars/' . $this->avatar);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name) . '&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Get the formatted address attribute.
     *
     * @return string
     */
    public function getFormattedAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address_line_1,
            $this->address_line_2,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Scope a query to only include VIP persons.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVip($query)
    {
        return $query->where('is_vip', true);
    }

    /**
     * Scope a query to only include persons with email.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithEmail($query)
    {
        return $query->whereNotNull('email');
    }

    /**
     * Scope a query to only include persons without email opt-out.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEmailOptIn($query)
    {
        return $query->where('email_opt_out', false);
    }

    /**
     * Scope a query to search persons by name or email.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        });
    }
}
