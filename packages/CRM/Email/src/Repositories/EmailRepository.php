<?php

namespace CRM\Email\Repositories;

use CRM\Core\Repositories\BaseRepository;
use CRM\Email\Contracts\EmailRepositoryInterface;
use CRM\Email\Models\Email;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class EmailRepository extends BaseRepository implements EmailRepositoryInterface
{
    /**
     * Create a new repository instance.
     */
    public function __construct(Email $model)
    {
        parent::__construct($model);
    }

    /**
     * Get emails by direction.
     */
    public function getByDirection(string $direction, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where('direction', $direction)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get emails by status.
     */
    public function getByStatus(string $status, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get emails by related entity.
     */
    public function getByRelated(string $relatedType, int $relatedId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where('related_type', $relatedType)
            ->where('related_id', $relatedId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get email thread.
     */
    public function getThread(string $threadId): Collection
    {
        return $this->model->where('thread_id', $threadId)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Get scheduled emails.
     */
    public function getScheduled(): Collection
    {
        return $this->model->where('status', Email::STATUS_SCHEDULED)
            ->where('scheduled_at', '<=', now())
            ->orderBy('scheduled_at', 'asc')
            ->get();
    }

    /**
     * Get emails for analytics.
     */
    public function getForAnalytics(array $filters = []): Collection
    {
        $query = $this->model->newQuery();

        if (isset($filters['start_date'])) {
            $query->where('created_at', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $query->where('created_at', '<=', $filters['end_date']);
        }

        if (isset($filters['direction'])) {
            $query->where('direction', $filters['direction']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        return $query->get();
    }

    /**
     * Search emails.
     */
    public function search(string $query, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $builder = $this->model->newQuery();

        // Text search
        $builder->where(function ($q) use ($query) {
            $q->where('subject', 'LIKE', "%{$query}%")
              ->orWhere('body_text', 'LIKE', "%{$query}%")
              ->orWhere('from_email', 'LIKE', "%{$query}%")
              ->orWhere('to_email', 'LIKE', "%{$query}%");
        });

        // Apply filters
        if (isset($filters['direction'])) {
            $builder->where('direction', $filters['direction']);
        }

        if (isset($filters['status'])) {
            $builder->where('status', $filters['status']);
        }

        if (isset($filters['type'])) {
            $builder->where('type', $filters['type']);
        }

        if (isset($filters['user_id'])) {
            $builder->where('user_id', $filters['user_id']);
        }

        if (isset($filters['start_date'])) {
            $builder->where('created_at', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $builder->where('created_at', '<=', $filters['end_date']);
        }

        return $builder->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Get email statistics.
     */
    public function getStatistics(array $filters = []): array
    {
        $query = $this->model->newQuery();

        // Apply date filters
        if (isset($filters['start_date'])) {
            $query->where('created_at', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $query->where('created_at', '<=', $filters['end_date']);
        }

        // Apply other filters
        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['direction'])) {
            $query->where('direction', $filters['direction']);
        }

        $total = $query->count();
        $sent = $query->clone()->where('status', Email::STATUS_SENT)->count();
        $opened = $query->clone()->whereNotNull('first_opened_at')->count();
        $clicked = $query->clone()->whereNotNull('first_clicked_at')->count();
        $bounced = $query->clone()->where('status', Email::STATUS_BOUNCED)->count();
        $failed = $query->clone()->where('status', Email::STATUS_FAILED)->count();

        return [
            'total' => $total,
            'sent' => $sent,
            'opened' => $opened,
            'clicked' => $clicked,
            'bounced' => $bounced,
            'failed' => $failed,
            'open_rate' => $sent > 0 ? round(($opened / $sent) * 100, 2) : 0,
            'click_rate' => $sent > 0 ? round(($clicked / $sent) * 100, 2) : 0,
            'bounce_rate' => $sent > 0 ? round(($bounced / $sent) * 100, 2) : 0,
            'failure_rate' => $total > 0 ? round(($failed / $total) * 100, 2) : 0,
        ];
    }
}
