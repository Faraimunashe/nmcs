<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(LaratrustSeeder::class);
        $this->call(MembershipSeeder::class);
        $this->call(RegionSeeder::class);
        $this->call(PaymentSeeder::class);
        $this->call(PaymentRecipientSeeder::class);
        $this->call(HealthSeeder::class);
        $this->call(ConferenceFeeSeeder::class);
        $this->call(AdminUserSeeder::class);
    }
}
