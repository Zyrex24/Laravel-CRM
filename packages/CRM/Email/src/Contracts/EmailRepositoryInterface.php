<?php

namespace CRM\Email\Contracts;

use CRM\Core\Contracts\RepositoryInterface;
use CRM\Email\Models\Email;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface EmailRepositoryInterface extends RepositoryInterface
{
    /**
     * Get emails by direction.
     */
    public function getByDirection(string $direction, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get emails by status.
     */
    public function getByStatus(string $status, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get emails by related entity.
     */
    public function getByRelated(string $relatedType, int $relatedId, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get email thread.
     */
    public function getThread(string $threadId): Collection;

    /**
     * Get scheduled emails.
     */
    public function getScheduled(): Collection;

    /**
     * Get emails for analytics.
     */
    public function getForAnalytics(array $filters = []): Collection;

    /**
     * Search emails.
     */
    public function search(string $query, array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Get email statistics.
     */
    public function getStatistics(array $filters = []): array;
}
