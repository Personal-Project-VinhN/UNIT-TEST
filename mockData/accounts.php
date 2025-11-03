<?php

/**
 * Mock data for Account feature testing
 * 
 * @author Gin<gin_vn@haldata.net>
 * @lastupdate Gin<gin_vn@haldata.net>
 */

return [
    'valid_bank_account' => [
        'name' => 'Vietcombank Main',
        'account_type' => 'bank',
        'account_number' => '1234567890',
        'bank_name' => 'Vietcombank',
        'balance' => 100000000,
        'currency' => 'VND',
        'is_active' => true,
    ],

    'valid_cash_account' => [
        'name' => 'Cash',
        'account_type' => 'cash',
        'balance' => 5000000,
        'currency' => 'VND',
        'is_active' => true,
    ],

    'valid_credit_card_account' => [
        'name' => 'Credit Card',
        'account_type' => 'credit_card',
        'account_number' => '9876543210',
        'balance' => 0,
        'currency' => 'VND',
        'is_active' => true,
    ],

    'invalid_account_missing_name' => [
        'account_type' => 'bank',
        'balance' => 1000000,
    ],

    'invalid_account_invalid_type' => [
        'name' => 'Test Account',
        'account_type' => 'invalid_type',
        'balance' => 1000000,
    ],

    'invalid_account_negative_balance' => [
        'name' => 'Test Account',
        'account_type' => 'bank',
        'balance' => -10000,
    ],

    'account_for_update' => [
        'name' => 'Updated Account Name',
        'balance' => 200000000,
        'is_active' => false,
    ],

    'inactive_account' => [
        'name' => 'Inactive Account',
        'account_type' => 'bank',
        'balance' => 0,
        'is_active' => false,
    ],

    'multiple_accounts' => [
        [
            'name' => 'Vietcombank Main',
            'account_type' => 'bank',
            'account_number' => '1234567890',
            'bank_name' => 'Vietcombank',
            'balance' => 100000000,
            'currency' => 'VND',
            'is_active' => true,
        ],
        [
            'name' => 'Cash',
            'account_type' => 'cash',
            'balance' => 5000000,
            'currency' => 'VND',
            'is_active' => true,
        ],
        [
            'name' => 'Credit Card',
            'account_type' => 'credit_card',
            'account_number' => '9876543210',
            'balance' => 0,
            'currency' => 'VND',
            'is_active' => true,
        ],
    ],
];
