<?php

/**
 * Mock data for Report feature testing
 * 
 * @author Gin<gin_vn@haldata.net>
 * @lastupdate Gin<gin_vn@haldata.net>
 */

return [
    'daily_report_request' => [
        'date' => '2024-01-15',
    ],

    'monthly_report_request' => [
        'year' => 2024,
        'month' => 1,
    ],

    'yearly_report_request' => [
        'year' => 2024,
    ],

    'date_range_report_request' => [
        'start_date' => '2024-01-01',
        'end_date' => '2024-01-31',
    ],

    'invalid_date_range_request' => [
        'start_date' => '2024-01-31',
        'end_date' => '2024-01-01', // End date before start date
    ],

    'expected_daily_report_response' => [
        'start_date' => '2024-01-15',
        'end_date' => '2024-01-15',
        'total_revenue' => 50000000,
        'total_expense' => 15000000,
        'profit' => 35000000,
        'transaction_count' => 2,
    ],

    'expected_monthly_report_response' => [
        'start_date' => '2024-01-01',
        'end_date' => '2024-01-31',
        'total_revenue' => 80000000,
        'total_expense' => 23000000,
        'profit' => 57000000,
        'transaction_count' => 6,
    ],

    'date_range_scenarios' => [
        'single_day' => [
            'start_date' => '2024-01-15',
            'end_date' => '2024-01-15',
        ],
        'one_week' => [
            'start_date' => '2024-01-01',
            'end_date' => '2024-01-07',
        ],
        'one_month' => [
            'start_date' => '2024-01-01',
            'end_date' => '2024-01-31',
        ],
        'one_quarter' => [
            'start_date' => '2024-01-01',
            'end_date' => '2024-03-31',
        ],
        'one_year' => [
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
        ],
    ],
];
