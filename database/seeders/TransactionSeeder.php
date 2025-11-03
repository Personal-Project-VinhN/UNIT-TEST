<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $account = Account::first();
        $revenueCategory = Category::where('type', 'revenue')->first();
        $expenseCategory = Category::where('type', 'expense')->first();

        // Sample revenue transactions
        Transaction::create([
            'account_id' => $account->id,
            'category_id' => $revenueCategory->id,
            'type' => 'revenue',
            'amount' => 50000000,
            'description' => 'Software development project payment',
            'transaction_date' => now()->subDays(10),
            'reference_number' => 'INV-2024-001',
        ]);

        Transaction::create([
            'account_id' => $account->id,
            'category_id' => $revenueCategory->id,
            'type' => 'revenue',
            'amount' => 30000000,
            'description' => 'IT consulting service fee',
            'transaction_date' => now()->subDays(5),
            'reference_number' => 'INV-2024-002',
        ]);

        // Sample expense transactions
        Transaction::create([
            'account_id' => $account->id,
            'category_id' => $expenseCategory->id,
            'type' => 'expense',
            'amount' => 15000000,
            'description' => 'Monthly employee salary',
            'transaction_date' => now()->subDays(7),
            'reference_number' => 'EXP-2024-001',
        ]);

        Transaction::create([
            'account_id' => $account->id,
            'category_id' => $expenseCategory->id,
            'type' => 'expense',
            'amount' => 5000000,
            'description' => 'Office rent payment',
            'transaction_date' => now()->subDays(3),
            'reference_number' => 'EXP-2024-002',
        ]);
    }
}
