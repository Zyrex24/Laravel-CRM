<?php

namespace CRM\Email\Repositories;

use CRM\Core\Repositories\BaseRepository;
use CRM\Email\Contracts\EmailTemplateRepositoryInterface;
use CRM\Email\Models\EmailTemplate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class EmailTemplateRepository extends BaseRepository implements EmailTemplateRepositoryInterface
{
    /**
     * Create a new repository instance.
     */
    public function __construct(EmailTemplate $model)
    {
        parent::__construct($model);
    }

    /**
     * Get templates by category.
     */
    public function getByCategory(string $category, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where('category', $category)
            ->active()
            ->orderBy('name', 'asc')
            ->paginate($perPage);
    }

    /**
     * Get templates by type.
     */
    public function getByType(string $type, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where('type', $type)
            ->active()
            ->orderBy('name', 'asc')
            ->paginate($perPage);
    }

    /**
     * Get active templates.
     */
    public function getActive(): Collection
    {
        return $this->model->active()
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Get default templates.
     */
    public function getDefault(): Collection
    {
        return $this->model->default()
            ->active()
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Get template by slug.
     */
    public function getBySlug(string $slug): ?EmailTemplate
    {
        return $this->model->where('slug', $slug)
            ->active()
            ->first();
    }

    /**
     * Search templates.
     */
    public function search(string $query, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $builder = $this->model->newQuery();

        // Text search
        $builder->where(function ($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('subject', 'LIKE', "%{$query}%")
              ->orWhere('description', 'LIKE', "%{$query}%");
        });

        // Apply filters
        if (isset($filters['category'])) {
            $builder->where('category', $filters['category']);
        }

        if (isset($filters['type'])) {
            $builder->where('type', $filters['type']);
        }

        if (isset($filters['language'])) {
            $builder->where('language', $filters['language']);
        }

        if (isset($filters['user_id'])) {
            $builder->where('user_id', $filters['user_id']);
        }

        if (isset($filters['is_active'])) {
            $builder->where('is_active', $filters['is_active']);
        }

        return $builder->orderBy('name', 'asc')->paginate($perPage);
    }

    /**
     * Get template versions.
     */
    public function getVersions(int $templateId): Collection
    {
        $template = $this->find($templateId);
        
        if (!$template) {
            return new Collection();
        }

        $parentId = $template->parent_id ?? $template->id;
        
        return $this->model->where('parent_id', $parentId)
            ->orWhere('id', $parentId)
            ->orderBy('version', 'desc')
            ->get();
    }
}
