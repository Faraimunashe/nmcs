<?php

namespace Database\Seeders;

use App\Models\ConferenceFee;
use Illuminate\Database\Seeder;

class ConferenceFeeSeeder extends Seeder
{
    public function run(): void
    {
        ConferenceFee::create([
            'name' => 'Easter Conference 2026',
            'description' => 'Registration fee for NMCS Zimbabwe Easter Conference 2026',
            'amount' => 25.00,
            'is_active' => true,
        ]);
    }
}
