<?php

/**
 * Mock data for Transaction feature testing
 * 
 * @author Gin<gin_vn@haldata.net>
 * @lastupdate Gin<gin_vn@haldata.net>
 */

return [
    'valid_revenue_transaction' => [
        'account_id' => 1,
        'category_id' => 1,
        'type' => 'revenue',
        'amount' => 50000000,
        'description' => 'Software development project payment',
        'transaction_date' => '2024-01-15',
        'reference_number' => 'INV-2024-001',
        'notes' => 'Payment for completed project phase 1',
    ],

    'valid_expense_transaction' => [
        'account_id' => 1,
        'category_id' => 4,
        'type' => 'expense',
        'amount' => 15000000,
        'description' => 'Monthly employee salary',
        'transaction_date' => '2024-01-10',
        'reference_number' => 'EXP-2024-001',
        'notes' => 'Salary payment for January',
    ],

    'invalid_transaction_missing_account' => [
        'category_id' => 1,
        'type' => 'revenue',
        'amount' => 50000000,
        'transaction_date' => '2024-01-15',
    ],

    'invalid_transaction_negative_amount' => [
        'account_id' => 1,
        'category_id' => 1,
        'type' => 'revenue',
        'amount' => -10000,
        'transaction_date' => '2024-01-15',
    ],

    'invalid_transaction_invalid_type' => [
        'account_id' => 1,
        'category_id' => 1,
        'type' => 'invalid_type',
        'amount' => 50000000,
        'transaction_date' => '2024-01-15',
    ],

    'transaction_for_update' => [
        'amount' => 75000000,
        'description' => 'Updated description',
        'notes' => 'Updated notes',
    ],

    'date_range_test' => [
        'start_date' => '2024-01-01',
        'end_date' => '2024-01-31',
    ],

    'multiple_revenue_transactions' => [
        [
            'account_id' => 1,
            'category_id' => 1,
            'type' => 'revenue',
            'amount' => 30000000,
            'description' => 'Project A payment',
            'transaction_date' => '2024-01-05',
        ],
        [
            'account_id' => 1,
            'category_id' => 2,
            'type' => 'revenue',
            'amount' => 20000000,
            'description' => 'Consulting fee',
            'transaction_date' => '2024-01-10',
        ],
        [
            'account_id' => 1,
            'category_id' => 3,
            'type' => 'revenue',
            'amount' => 10000000,
            'description' => 'Product sales',
            'transaction_date' => '2024-01-15',
        ],
    ],

    'multiple_expense_transactions' => [
        [
            'account_id' => 1,
            'category_id' => 4,
            'type' => 'expense',
            'amount' => 15000000,
            'description' => 'Employee salary',
            'transaction_date' => '2024-01-08',
        ],
        [
            'account_id' => 1,
            'category_id' => 5,
            'type' => 'expense',
            'amount' => 5000000,
            'description' => 'Office rent',
            'transaction_date' => '2024-01-12',
        ],
        [
            'account_id' => 1,
            'category_id' => 6,
            'type' => 'expense',
            'amount' => 3000000,
            'description' => 'Hardware purchase',
            'transaction_date' => '2024-01-18',
        ],
    ],
];
