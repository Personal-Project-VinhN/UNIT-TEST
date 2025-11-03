<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Account::create([
            'name' => 'Vietcombank Main',
            'account_type' => 'bank',
            'account_number' => '1234567890',
            'bank_name' => 'Vietcombank',
            'balance' => 100000000,
            'currency' => 'VND',
            'is_active' => true,
        ]);

        Account::create([
            'name' => 'Cash',
            'account_type' => 'cash',
            'balance' => 5000000,
            'currency' => 'VND',
            'is_active' => true,
        ]);

        Account::create([
            'name' => 'Credit Card',
            'account_type' => 'credit_card',
            'account_number' => '9876543210',
            'balance' => 0,
            'currency' => 'VND',
            'is_active' => true,
        ]);
    }
}
