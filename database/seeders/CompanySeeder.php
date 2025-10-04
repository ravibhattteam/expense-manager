<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        Company::create([
            'name' => 'Demo Company',
            'default_currency' => 'USD' // 👈 fixed column name
        ]);
    }
}
