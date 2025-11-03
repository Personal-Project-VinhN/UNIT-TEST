<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Revenue categories
        Category::create([
            'name' => 'Software Development',
            'type' => 'revenue',
            'description' => 'Revenue from software development projects',
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'IT Consulting',
            'type' => 'revenue',
            'description' => 'Revenue from IT consulting services',
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Product Sales',
            'type' => 'revenue',
            'description' => 'Revenue from product sales',
            'is_active' => true,
        ]);

        // Expense categories
        Category::create([
            'name' => 'Employee Salary',
            'type' => 'expense',
            'description' => 'Employee salary and benefits',
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Office Rent',
            'type' => 'expense',
            'description' => 'Office rental expenses',
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Hardware & Equipment',
            'type' => 'expense',
            'description' => 'Hardware and equipment purchases',
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Software Licenses',
            'type' => 'expense',
            'description' => 'Software license fees',
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Marketing & Advertising',
            'type' => 'expense',
            'description' => 'Marketing and advertising expenses',
            'is_active' => true,
        ]);
    }
}
