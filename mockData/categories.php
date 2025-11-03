<?php

/**
 * Mock data for Category feature testing
 * 
 * @author Gin<gin_vn@haldata.net>
 * @lastupdate Gin<gin_vn@haldata.net>
 */

return [
    'valid_revenue_category' => [
        'name' => 'Software Development',
        'type' => 'revenue',
        'description' => 'Revenue from software development projects',
        'is_active' => true,
    ],

    'valid_expense_category' => [
        'name' => 'Employee Salary',
        'type' => 'expense',
        'description' => 'Employee salary and benefits',
        'is_active' => true,
    ],

    'invalid_category_missing_name' => [
        'type' => 'revenue',
        'description' => 'Test category',
    ],

    'invalid_category_invalid_type' => [
        'name' => 'Test Category',
        'type' => 'invalid_type',
        'description' => 'Test category',
    ],

    'category_for_update' => [
        'name' => 'Updated Category Name',
        'description' => 'Updated description',
        'is_active' => false,
    ],

    'inactive_category' => [
        'name' => 'Inactive Category',
        'type' => 'revenue',
        'description' => 'This category is inactive',
        'is_active' => false,
    ],

    'multiple_revenue_categories' => [
        [
            'name' => 'Software Development',
            'type' => 'revenue',
            'description' => 'Revenue from software development',
            'is_active' => true,
        ],
        [
            'name' => 'IT Consulting',
            'type' => 'revenue',
            'description' => 'Revenue from IT consulting',
            'is_active' => true,
        ],
        [
            'name' => 'Product Sales',
            'type' => 'revenue',
            'description' => 'Revenue from product sales',
            'is_active' => true,
        ],
    ],

    'multiple_expense_categories' => [
        [
            'name' => 'Employee Salary',
            'type' => 'expense',
            'description' => 'Employee salary and benefits',
            'is_active' => true,
        ],
        [
            'name' => 'Office Rent',
            'type' => 'expense',
            'description' => 'Office rental expenses',
            'is_active' => true,
        ],
        [
            'name' => 'Hardware & Equipment',
            'type' => 'expense',
            'description' => 'Hardware and equipment purchases',
            'is_active' => true,
        ],
    ],
];
