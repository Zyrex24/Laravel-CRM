<?php

namespace CRM\Contact\Models;

use CRM\Core\Models\BaseModel;
use CRM\User\Models\User;

class Organization extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'organizations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'legal_name',
        'email',
        'phone',
        'fax',
        'website',
        'logo',
        'industry',
        'size_category',
        'annual_revenue',
        'number_of_employees',
        'founded_year',
        'description',
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
        'is_customer',
        'is_vendor',
        'is_partner',
        'parent_organization_id',
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
            'annual_revenue' => 'decimal:2',
            'number_of_employees' => 'integer',
            'founded_year' => 'integer',
            'tags' => 'array',
            'social_profiles' => 'array',
            'custom_attributes' => 'array',
            'is_customer' => 'boolean',
            'is_vendor' => 'boolean',
            'is_partner' => 'boolean',
        ];
    }

    /**
     * Get the owner of the organization.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the user who created the organization.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the organization.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the parent organization.
     */
    public function parentOrganization()
    {
        return $this->belongsTo(Organization::class, 'parent_organization_id');
    }

    /**
     * Get the child organizations.
     */
    public function childOrganizations()
    {
        return $this->hasMany(Organization::class, 'parent_organization_id');
    }

    /**
     * Get the persons that belong to this organization.
     */
    public function persons()
    {
        return $this->belongsToMany(Person::class, 'person_organizations')
                    ->withPivot(['role', 'is_primary', 'start_date', 'end_date'])
                    ->withTimestamps();
    }

    /**
     * Get the primary contact for this organization.
     */
    public function primaryContact()
    {
        return $this->belongsToMany(Person::class, 'person_organizations')
                    ->wherePivot('is_primary', true)
                    ->withPivot(['role', 'is_primary', 'start_date', 'end_date'])
                    ->withTimestamps()
                    ->first();
    }

    /**
     * Get the activities for this organization.
     */
    public function activities()
    {
        return $this->morphMany(\CRM\Activity\Models\Activity::class, 'related');
    }

    /**
     * Get the leads for this organization.
     */
    public function leads()
    {
        return $this->morphMany(\CRM\Lead\Models\Lead::class, 'contact');
    }

    /**
     * Get the emails sent to this organization.
     */
    public function emails()
    {
        return $this->morphMany(\CRM\Email\Models\Email::class, 'recipient');
    }

    /**
     * Get the logo URL attribute.
     *
     * @return string
     */
    public function getLogoUrlAttribute(): string
    {
        if ($this->logo) {
            return asset('storage/logos/' . $this->logo);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
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
     * Get the size category display name.
     *
     * @return string
     */
    public function getSizeCategoryDisplayAttribute(): string
    {
        $categories = config('crm.contact.organization.size_categories', []);
        return $categories[$this->size_category] ?? $this->size_category;
    }

    /**
     * Scope a query to only include customers.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCustomers($query)
    {
        return $query->where('is_customer', true);
    }

    /**
     * Scope a query to only include vendors.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVendors($query)
    {
        return $query->where('is_vendor', true);
    }

    /**
     * Scope a query to only include partners.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePartners($query)
    {
        return $query->where('is_partner', true);
    }

    /**
     * Scope a query to search organizations by name or email.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('legal_name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('website', 'like', "%{$search}%");
        });
    }
}
