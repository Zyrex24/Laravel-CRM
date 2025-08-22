<?php

namespace CRM\Email\Contracts;

use CRM\Core\Contracts\RepositoryInterface;
use CRM\Email\Models\EmailTemplate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface EmailTemplateRepositoryInterface extends RepositoryInterface
{
    /**
     * Get templates by category.
     */
    public function getByCategory(string $category, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get templates by type.
     */
    public function getByType(string $type, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get active templates.
     */
    public function getActive(): Collection;

    /**
     * Get default templates.
     */
    public function getDefault(): Collection;

    /**
     * Get template by slug.
     */
    public function getBySlug(string $slug): ?EmailTemplate;

    /**
     * Search templates.
     */
    public function search(string $query, array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Get template versions.
     */
    public function getVersions(int $templateId): Collection;
}
