<?php

namespace CRM\DataGrid;

use CRM\DataGrid\Contracts\DataGridInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class DataGrid implements DataGridInterface
{
    protected Builder $query;
    protected array $columns = [];
    protected array $filters = [];
    protected array $sorts = [];
    protected int $perPage = 25;
    protected string $cacheKey;
    protected array $config;

    public function __construct()
    {
        $this->config = config('crm.datagrid', []);
        $this->perPage = $this->config['pagination']['default_per_page'] ?? 25;
    }

    /**
     * Set the query builder for the data grid.
     */
    public function query(Builder $query): self
    {
        $this->query = $query;
        $this->cacheKey = 'datagrid_' . md5($query->toSql() . serialize($query->getBindings()));
        return $this;
    }

    /**
     * Add a column to the data grid.
     */
    public function addColumn(string $key, string $label, array $options = []): self
    {
        $defaultOptions = [
            'sortable' => true,
            'filterable' => true,
            'searchable' => true,
            'exportable' => true,
            'type' => 'text',
            'width' => null,
            'align' => 'left',
            'format' => null,
            'relationship' => null,
            'accessor' => null,
        ];

        $this->columns[$key] = array_merge($defaultOptions, $options, [
            'key' => $key,
            'label' => $label,
        ]);

        return $this;
    }

    /**
     * Apply filters to the query.
     */
    public function applyFilters(Request $request): self
    {
        $filters = $request->get('filters', []);
        $globalSearch = $request->get('search');

        // Apply global search
        if ($globalSearch && $this->config['filtering']['global_search']) {
            $this->applyGlobalSearch($globalSearch);
        }

        // Apply column filters
        if (!empty($filters) && $this->config['filtering']['column_filters']) {
            foreach ($filters as $column => $filter) {
                $this->applyColumnFilter($column, $filter);
            }
        }

        $this->filters = $filters;
        return $this;
    }

    /**
     * Apply global search across searchable columns.
     */
    protected function applyGlobalSearch(string $search): void
    {
        $searchableColumns = collect($this->columns)
            ->filter(fn($column) => $column['searchable'])
            ->keys()
            ->toArray();

        if (empty($searchableColumns)) {
            return;
        }

        $this->query->where(function ($query) use ($search, $searchableColumns) {
            foreach ($searchableColumns as $column) {
                if (Str::contains($column, '.')) {
                    // Handle relationship columns
                    $parts = explode('.', $column);
                    $relation = $parts[0];
                    $field = $parts[1];
                    
                    $query->orWhereHas($relation, function ($q) use ($field, $search) {
                        $q->where($field, 'LIKE', "%{$search}%");
                    });
                } else {
                    $query->orWhere($column, 'LIKE', "%{$search}%");
                }
            }
        });
    }

    /**
     * Apply individual column filter.
     */
    protected function applyColumnFilter(string $column, array $filter): void
    {
        if (!isset($this->columns[$column]) || !$this->columns[$column]['filterable']) {
            return;
        }

        $operator = $filter['operator'] ?? 'equals';
        $value = $filter['value'] ?? null;

        if ($value === null || $value === '') {
            return;
        }

        $operatorMap = $this->config['filtering']['filter_operators'] ?? [];
        $sqlOperator = $operatorMap[$operator] ?? '=';

        switch ($operator) {
            case 'contains':
                $this->query->where($column, 'LIKE', "%{$value}%");
                break;
            case 'not_contains':
                $this->query->where($column, 'NOT LIKE', "%{$value}%");
                break;
            case 'starts_with':
                $this->query->where($column, 'LIKE', "{$value}%");
                break;
            case 'ends_with':
                $this->query->where($column, 'LIKE', "%{$value}");
                break;
            case 'between':
                if (is_array($value) && count($value) === 2) {
                    $this->query->whereBetween($column, $value);
                }
                break;
            case 'not_between':
                if (is_array($value) && count($value) === 2) {
                    $this->query->whereNotBetween($column, $value);
                }
                break;
            case 'in':
                if (is_array($value)) {
                    $this->query->whereIn($column, $value);
                }
                break;
            case 'not_in':
                if (is_array($value)) {
                    $this->query->whereNotIn($column, $value);
                }
                break;
            case 'is_null':
                $this->query->whereNull($column);
                break;
            case 'is_not_null':
                $this->query->whereNotNull($column);
                break;
            default:
                $this->query->where($column, $sqlOperator, $value);
                break;
        }
    }

    /**
     * Apply sorting to the query.
     */
    public function applySorting(Request $request): self
    {
        $sorts = $request->get('sort', []);

        if (empty($sorts)) {
            // Apply default sorting if no sort specified
            $this->query->orderBy('created_at', 'desc');
            return $this;
        }

        foreach ($sorts as $sort) {
            $column = $sort['column'] ?? null;
            $direction = $sort['direction'] ?? 'asc';

            if (!$column || !isset($this->columns[$column]) || !$this->columns[$column]['sortable']) {
                continue;
            }

            if (Str::contains($column, '.')) {
                // Handle relationship sorting
                $parts = explode('.', $column);
                $relation = $parts[0];
                $field = $parts[1];
                
                $this->query->join(
                    Str::plural($relation),
                    $this->query->getModel()->getTable() . '.' . $relation . '_id',
                    '=',
                    Str::plural($relation) . '.id'
                )->orderBy(Str::plural($relation) . '.' . $field, $direction);
            } else {
                $this->query->orderBy($column, $direction);
            }
        }

        $this->sorts = $sorts;
        return $this;
    }

    /**
     * Apply pagination to the query.
     */
    public function applyPagination(Request $request): self
    {
        $perPage = $request->get('per_page', $this->perPage);
        $maxPerPage = $this->config['pagination']['max_per_page'] ?? 500;
        
        $this->perPage = min($perPage, $maxPerPage);
        return $this;
    }

    /**
     * Get the data grid results.
     */
    public function get(): array
    {
        $cacheEnabled = $this->config['caching']['enabled'] ?? false;
        $cacheTtl = $this->config['caching']['ttl'] ?? 300;

        if ($cacheEnabled) {
            return Cache::remember($this->cacheKey, $cacheTtl, function () {
                return $this->executeQuery();
            });
        }

        return $this->executeQuery();
    }

    /**
     * Execute the query and format results.
     */
    protected function executeQuery(): array
    {
        $paginator = $this->query->paginate($this->perPage);
        
        $data = $paginator->getCollection()->map(function ($item) {
            return $this->formatRow($item);
        });

        return [
            'data' => $data,
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
            'columns' => $this->columns,
            'filters' => $this->filters,
            'sorts' => $this->sorts,
        ];
    }

    /**
     * Format a single row of data.
     */
    protected function formatRow($item): array
    {
        $row = [];

        foreach ($this->columns as $key => $column) {
            $value = $this->getColumnValue($item, $key, $column);
            $row[$key] = $this->formatValue($value, $column);
        }

        return $row;
    }

    /**
     * Get the value for a specific column.
     */
    protected function getColumnValue($item, string $key, array $column)
    {
        if ($column['accessor']) {
            return call_user_func($column['accessor'], $item);
        }

        if (Str::contains($key, '.')) {
            // Handle nested relationships
            $parts = explode('.', $key);
            $value = $item;
            
            foreach ($parts as $part) {
                $value = $value?->{$part};
            }
            
            return $value;
        }

        return $item->{$key} ?? null;
    }

    /**
     * Format a value based on column type.
     */
    protected function formatValue($value, array $column)
    {
        if ($value === null) {
            return null;
        }

        switch ($column['type']) {
            case 'date':
                return $value instanceof \Carbon\Carbon ? $value->format('Y-m-d') : $value;
            case 'datetime':
                return $value instanceof \Carbon\Carbon ? $value->format('Y-m-d H:i:s') : $value;
            case 'currency':
                return number_format((float)$value, 2);
            case 'number':
                return is_numeric($value) ? number_format((float)$value) : $value;
            case 'boolean':
                return $value ? 'Yes' : 'No';
            case 'image':
                return $value ? asset('storage/' . $value) : null;
            default:
                return $value;
        }
    }

    /**
     * Export the data grid results.
     */
    public function export(string $format, Request $request)
    {
        // Remove pagination for export
        $originalPerPage = $this->perPage;
        $this->perPage = $this->config['export']['max_records'] ?? 10000;

        $data = $this->executeQuery();
        
        // Restore original pagination
        $this->perPage = $originalPerPage;

        switch ($format) {
            case 'csv':
                return $this->exportToCsv($data);
            case 'xlsx':
                return $this->exportToExcel($data);
            case 'pdf':
                return $this->exportToPdf($data);
            default:
                throw new \InvalidArgumentException("Unsupported export format: {$format}");
        }
    }

    /**
     * Export to CSV format.
     */
    protected function exportToCsv(array $data): string
    {
        $output = fopen('php://temp', 'r+');
        
        // Add headers
        $headers = collect($this->columns)
            ->filter(fn($column) => $column['exportable'])
            ->pluck('label')
            ->toArray();
        
        fputcsv($output, $headers);
        
        // Add data rows
        foreach ($data['data'] as $row) {
            $exportRow = [];
            foreach ($this->columns as $key => $column) {
                if ($column['exportable']) {
                    $exportRow[] = $row[$key] ?? '';
                }
            }
            fputcsv($output, $exportRow);
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return $csv;
    }

    /**
     * Export to Excel format (placeholder).
     */
    protected function exportToExcel(array $data): string
    {
        // This would require a package like PhpSpreadsheet
        // For now, return CSV format
        return $this->exportToCsv($data);
    }

    /**
     * Export to PDF format (placeholder).
     */
    protected function exportToPdf(array $data): string
    {
        // This would require a package like DomPDF or TCPDF
        // For now, return CSV format
        return $this->exportToCsv($data);
    }

    /**
     * Get the data grid configuration.
     */
    public function getConfig(): array
    {
        return [
            'columns' => $this->columns,
            'pagination' => $this->config['pagination'] ?? [],
            'filtering' => $this->config['filtering'] ?? [],
            'sorting' => $this->config['sorting'] ?? [],
            'bulk_actions' => $this->config['bulk_actions'] ?? [],
            'export' => $this->config['export'] ?? [],
            'ui' => $this->config['ui'] ?? [],
        ];
    }
}
