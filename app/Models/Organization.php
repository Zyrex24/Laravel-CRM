<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'website',
        'industry',
        'employees',
        'annual_revenue',
        'description',
        'phone',
        'email',
        'address',
        'status',
    ];

    protected $casts = [
        'address' => 'array',
        'annual_revenue' => 'decimal:2',
    ];

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }
}
