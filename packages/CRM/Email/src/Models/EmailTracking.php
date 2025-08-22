<?php

namespace CRM\Email\Models;

use CRM\Core\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class EmailTracking extends BaseModel
{
    /**
     * The table associated with the model.
     */
    protected $table = 'email_tracking';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'email_id',
        'event_type',
        'ip_address',
        'user_agent',
        'location',
        'device_type',
        'browser',
        'operating_system',
        'url',
        'clicked_url',
        'timestamp',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'timestamp' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Event types
     */
    const EVENT_SENT = 'sent';
    const EVENT_DELIVERED = 'delivered';
    const EVENT_OPENED = 'opened';
    const EVENT_CLICKED = 'clicked';
    const EVENT_BOUNCED = 'bounced';
    const EVENT_SPAM = 'spam';
    const EVENT_UNSUBSCRIBED = 'unsubscribed';

    /**
     * Get the email this tracking belongs to.
     */
    public function email(): BelongsTo
    {
        return $this->belongsTo(Email::class);
    }

    /**
     * Scope for tracking events by type.
     */
    public function scopeByEventType(Builder $query, string $eventType): Builder
    {
        return $query->where('event_type', $eventType);
    }

    /**
     * Scope for open events.
     */
    public function scopeOpens(Builder $query): Builder
    {
        return $query->where('event_type', self::EVENT_OPENED);
    }

    /**
     * Scope for click events.
     */
    public function scopeClicks(Builder $query): Builder
    {
        return $query->where('event_type', self::EVENT_CLICKED);
    }

    /**
     * Scope for bounce events.
     */
    public function scopeBounces(Builder $query): Builder
    {
        return $query->where('event_type', self::EVENT_BOUNCED);
    }
}
