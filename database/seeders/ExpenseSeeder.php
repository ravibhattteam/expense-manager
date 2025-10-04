<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Expense;

class ExpenseSeeder extends Seeder
{
    public function run(): void
    {
        Expense::create([
            'user_id' => 3, // Employee
            'company_id' => 1,
            'amount' => 200,
            'currency' => 'USD',
            'category' => 'Travel',
            'description' => 'Taxi fare for client meeting',
            'status' => 'pending',
            'expense_date' => now()->subDays(2), // 👈 FIXED
            'created_at' => now()->subDays(2),
            'updated_at' => now()
        ]);
    }
}
