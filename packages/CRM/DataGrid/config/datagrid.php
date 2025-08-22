<?php

return [
    /*
    |--------------------------------------------------------------------------
    | DataGrid Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration settings for the advanced data grid
    | system including pagination, filtering, sorting, and export options.
    |
    */

    'pagination' => [
        'default_per_page' => 25,
        'per_page_options' => [10, 25, 50, 100],
        'max_per_page' => 500,
        'show_pagination_info' => true,
    ],

    'filtering' => [
        'enabled' => true,
        'global_search' => true,
        'column_filters' => true,
        'advanced_filters' => true,
        'saved_filters' => true,
        'filter_operators' => [
            'equals' => '=',
            'not_equals' => '!=',
            'contains' => 'LIKE',
            'not_contains' => 'NOT LIKE',
            'starts_with' => 'LIKE',
            'ends_with' => 'LIKE',
            'greater_than' => '>',
            'greater_than_or_equal' => '>=',
            'less_than' => '<',
            'less_than_or_equal' => '<=',
            'between' => 'BETWEEN',
            'not_between' => 'NOT BETWEEN',
            'in' => 'IN',
            'not_in' => 'NOT IN',
            'is_null' => 'IS NULL',
            'is_not_null' => 'IS NOT NULL',
        ],
    ],

    'sorting' => [
        'enabled' => true,
        'multi_column' => true,
        'default_direction' => 'asc',
        'remember_sort' => true,
    ],

    'columns' => [
        'resizable' => true,
        'reorderable' => true,
        'hideable' => true,
        'remember_state' => true,
        'auto_width' => false,
        'min_width' => 50,
        'max_width' => 500,
    ],

    'selection' => [
        'enabled' => true,
        'multiple' => true,
        'remember_selection' => false,
        'select_all_pages' => true,
    ],

    'bulk_actions' => [
        'enabled' => true,
        'actions' => [
            'delete' => [
                'label' => 'Delete Selected',
                'icon' => 'trash',
                'color' => 'red',
                'confirmation' => true,
                'permission' => 'delete',
            ],
            'export' => [
                'label' => 'Export Selected',
                'icon' => 'download',
                'color' => 'blue',
                'confirmation' => false,
                'permission' => 'export',
            ],
            'assign' => [
                'label' => 'Assign to User',
                'icon' => 'user',
                'color' => 'green',
                'confirmation' => false,
                'permission' => 'assign',
            ],
            'tag' => [
                'label' => 'Add Tags',
                'icon' => 'tag',
                'color' => 'yellow',
                'confirmation' => false,
                'permission' => 'edit',
            ],
        ],
    ],

    'export' => [
        'enabled' => true,
        'formats' => ['csv', 'xlsx', 'pdf'],
        'max_records' => 10000,
        'chunk_size' => 1000,
        'include_headers' => true,
        'date_format' => 'Y-m-d H:i:s',
        'filename_format' => '{model}_{date}',
    ],

    'import' => [
        'enabled' => true,
        'formats' => ['csv', 'xlsx'],
        'max_file_size' => 10240, // KB
        'chunk_size' => 100,
        'validate_headers' => true,
        'skip_duplicates' => true,
        'update_existing' => false,
    ],

    'caching' => [
        'enabled' => true,
        'ttl' => 300, // seconds
        'key_prefix' => 'datagrid_',
        'cache_queries' => true,
        'cache_results' => true,
    ],

    'ui' => [
        'theme' => 'default',
        'responsive' => true,
        'sticky_header' => true,
        'row_hover' => true,
        'striped_rows' => true,
        'compact_mode' => false,
        'loading_indicator' => true,
        'empty_state_message' => 'No records found',
    ],

    'performance' => [
        'lazy_loading' => true,
        'virtual_scrolling' => false,
        'debounce_search' => 300, // milliseconds
        'optimize_queries' => true,
        'eager_load_relations' => [],
    ],

    'security' => [
        'sanitize_input' => true,
        'validate_columns' => true,
        'check_permissions' => true,
        'rate_limiting' => true,
        'csrf_protection' => true,
    ],

    'column_types' => [
        'text' => [
            'filterable' => true,
            'sortable' => true,
            'searchable' => true,
            'exportable' => true,
        ],
        'number' => [
            'filterable' => true,
            'sortable' => true,
            'searchable' => false,
            'exportable' => true,
            'format' => 'number',
        ],
        'currency' => [
            'filterable' => true,
            'sortable' => true,
            'searchable' => false,
            'exportable' => true,
            'format' => 'currency',
        ],
        'date' => [
            'filterable' => true,
            'sortable' => true,
            'searchable' => false,
            'exportable' => true,
            'format' => 'date',
        ],
        'datetime' => [
            'filterable' => true,
            'sortable' => true,
            'searchable' => false,
            'exportable' => true,
            'format' => 'datetime',
        ],
        'boolean' => [
            'filterable' => true,
            'sortable' => true,
            'searchable' => false,
            'exportable' => true,
            'format' => 'boolean',
        ],
        'enum' => [
            'filterable' => true,
            'sortable' => true,
            'searchable' => false,
            'exportable' => true,
            'format' => 'enum',
        ],
        'image' => [
            'filterable' => false,
            'sortable' => false,
            'searchable' => false,
            'exportable' => false,
            'format' => 'image',
        ],
        'link' => [
            'filterable' => false,
            'sortable' => false,
            'searchable' => true,
            'exportable' => true,
            'format' => 'link',
        ],
        'actions' => [
            'filterable' => false,
            'sortable' => false,
            'searchable' => false,
            'exportable' => false,
            'format' => 'actions',
        ],
    ],
];
