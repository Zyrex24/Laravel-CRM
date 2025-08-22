<?php

namespace CRM\Email\Models;

use CRM\Core\Models\BaseModel;
use CRM\User\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Email extends BaseModel
{
    /**
     * The table associated with the model.
     */
    protected $table = 'emails';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'subject',
        'body',
        'body_html',
        'body_text',
        'from_email',
        'from_name',
        'to_email',
        'to_name',
        'cc_emails',
        'bcc_emails',
        'reply_to_email',
        'reply_to_name',
        'message_id',
        'thread_id',
        'in_reply_to',
        'references',
        'direction',
        'status',
        'priority',
        'type',
        'template_id',
        'campaign_id',
        'sent_at',
        'delivered_at',
        'opened_at',
        'first_opened_at',
        'clicked_at',
        'first_clicked_at',
        'bounced_at',
        'replied_at',
        'unsubscribed_at',
        'spam_reported_at',
        'open_count',
        'click_count',
        'bounce_reason',
        'error_message',
        'tracking_enabled',
        'scheduled_at',
        'user_id',
        'related_type',
        'related_id',
        'metadata',
        'headers',
        'raw_content',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'cc_emails' => 'array',
        'bcc_emails' => 'array',
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'opened_at' => 'datetime',
        'first_opened_at' => 'datetime',
        'clicked_at' => 'datetime',
        'first_clicked_at' => 'datetime',
        'bounced_at' => 'datetime',
        'replied_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
        'spam_reported_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'open_count' => 'integer',
        'click_count' => 'integer',
        'tracking_enabled' => 'boolean',
        'metadata' => 'array',
        'headers' => 'array',
    ];

    /**
     * Email directions
     */
    const DIRECTION_INBOUND = 'inbound';
    const DIRECTION_OUTBOUND = 'outbound';

    /**
     * Email statuses
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_QUEUED = 'queued';
    const STATUS_SENDING = 'sending';
    const STATUS_SENT = 'sent';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_OPENED = 'opened';
    const STATUS_CLICKED = 'clicked';
    const STATUS_BOUNCED = 'bounced';
    const STATUS_FAILED = 'failed';
    const STATUS_SPAM = 'spam';

    /**
     * Email priorities
     */
    const PRIORITY_LOW = 'low';
    const PRIORITY_NORMAL = 'normal';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    /**
     * Email types
     */
    const TYPE_REGULAR = 'regular';
    const TYPE_TEMPLATE = 'template';
    const TYPE_CAMPAIGN = 'campaign';
    const TYPE_AUTOMATED = 'automated';
    const TYPE_TRANSACTIONAL = 'transactional';

    /**
     * Get the user who sent/received the email.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the email template if used.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(EmailTemplate::class, 'template_id');
    }

    /**
     * Get the related entity (polymorphic).
     */
    public function related(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get email attachments.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(EmailAttachment::class);
    }

    /**
     * Get email tracking events.
     */
    public function trackingEvents(): HasMany
    {
        return $this->hasMany(EmailTracking::class);
    }

    /**
     * Get email replies.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Email::class, 'in_reply_to', 'message_id');
    }

    /**
     * Get the original email this is a reply to.
     */
    public function originalEmail(): BelongsTo
    {
        return $this->belongsTo(Email::class, 'in_reply_to', 'message_id');
    }

    /**
     * Scope for sent emails.
     */
    public function scopeSent(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_SENT);
    }

    /**
     * Scope for draft emails.
     */
    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    /**
     * Scope for scheduled emails.
     */
    public function scopeScheduled(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_SCHEDULED);
    }

    /**
     * Scope for inbound emails.
     */
    public function scopeInbound(Builder $query): Builder
    {
        return $query->where('direction', self::DIRECTION_INBOUND);
    }

    /**
     * Scope for outbound emails.
     */
    public function scopeOutbound(Builder $query): Builder
    {
        return $query->where('direction', self::DIRECTION_OUTBOUND);
    }

    /**
     * Scope for opened emails.
     */
    public function scopeOpened(Builder $query): Builder
    {
        return $query->whereNotNull('first_opened_at');
    }

    /**
     * Scope for clicked emails.
     */
    public function scopeClicked(Builder $query): Builder
    {
        return $query->whereNotNull('first_clicked_at');
    }

    /**
     * Scope for bounced emails.
     */
    public function scopeBounced(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_BOUNCED);
    }

    /**
     * Scope for emails by priority.
     */
    public function scopeByPriority(Builder $query, string $priority): Builder
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope for emails by type.
     */
    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for emails in date range.
     */
    public function scopeInDateRange(Builder $query, Carbon $start, Carbon $end): Builder
    {
        return $query->whereBetween('created_at', [$start, $end]);
    }

    /**
     * Check if email was opened.
     */
    public function wasOpened(): bool
    {
        return !is_null($this->first_opened_at);
    }

    /**
     * Check if email was clicked.
     */
    public function wasClicked(): bool
    {
        return !is_null($this->first_clicked_at);
    }

    /**
     * Check if email bounced.
     */
    public function bounced(): bool
    {
        return $this->status === self::STATUS_BOUNCED;
    }

    /**
     * Check if email is a reply.
     */
    public function isReply(): bool
    {
        return !is_null($this->in_reply_to);
    }

    /**
     * Mark email as opened.
     */
    public function markAsOpened(): void
    {
        if (!$this->wasOpened()) {
            $this->first_opened_at = now();
        }
        
        $this->opened_at = now();
        $this->open_count = $this->open_count + 1;
        $this->status = self::STATUS_OPENED;
        $this->save();
    }

    /**
     * Mark email as clicked.
     */
    public function markAsClicked(): void
    {
        if (!$this->wasClicked()) {
            $this->first_clicked_at = now();
        }
        
        $this->clicked_at = now();
        $this->click_count = $this->click_count + 1;
        $this->save();
    }

    /**
     * Mark email as bounced.
     */
    public function markAsBounced(string $reason = null): void
    {
        $this->status = self::STATUS_BOUNCED;
        $this->bounced_at = now();
        if ($reason) {
            $this->bounce_reason = $reason;
        }
        $this->save();
    }

    /**
     * Get email open rate.
     */
    public function getOpenRate(): float
    {
        if ($this->status !== self::STATUS_SENT) {
            return 0;
        }
        
        return $this->wasOpened() ? 100.0 : 0.0;
    }

    /**
     * Get email click rate.
     */
    public function getClickRate(): float
    {
        if ($this->status !== self::STATUS_SENT) {
            return 0;
        }
        
        return $this->wasClicked() ? 100.0 : 0.0;
    }

    /**
     * Generate tracking pixel URL.
     */
    public function getTrackingPixelUrl(): string
    {
        return url(config('crm.email.tracking.pixel_url') . '/' . $this->id);
    }

    /**
     * Generate link tracking URL.
     */
    public function getLinkTrackingUrl(string $originalUrl): string
    {
        return url(config('crm.email.tracking.link_redirect_url') . '/' . $this->id . '?url=' . urlencode($originalUrl));
    }
}
