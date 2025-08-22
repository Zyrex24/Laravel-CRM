<?php

namespace CRM\DataGrid\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

interface DataGridInterface
{
    /**
     * Set the query builder for the data grid.
     *
     * @param Builder $query
     * @return self
     */
    public function query(Builder $query): self;

    /**
     * Add a column to the data grid.
     *
     * @param string $key
     * @param string $label
     * @param array $options
     * @return self
     */
    public function addColumn(string $key, string $label, array $options = []): self;

    /**
     * Apply filters to the query.
     *
     * @param Request $request
     * @return self
     */
    public function applyFilters(Request $request): self;

    /**
     * Apply sorting to the query.
     *
     * @param Request $request
     * @return self
     */
    public function applySorting(Request $request): self;

    /**
     * Apply pagination to the query.
     *
     * @param Request $request
     * @return self
     */
    public function applyPagination(Request $request): self;

    /**
     * Get the data grid results.
     *
     * @return array
     */
    public function get(): array;

    /**
     * Export the data grid results.
     *
     * @param string $format
     * @param Request $request
     * @return mixed
     */
    public function export(string $format, Request $request);

    /**
     * Get the data grid configuration.
     *
     * @return array
     */
    public function getConfig(): array;
}
