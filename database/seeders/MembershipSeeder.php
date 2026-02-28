<?php

namespace Database\Seeders;

use App\Enums\MembershipStatus;
use App\Models\Membership;
use Illuminate\Database\Seeder;

class MembershipSeeder extends Seeder
{
    public function run(): void
    {
        Membership::create([
            'status' => MembershipStatus::ORDINARY,
            'description' => 'Ordinary membership for students paying subscriptions',
        ]);

        Membership::create([
            'status' => MembershipStatus::ASSOCIATE,
            'description' => 'Associate membership for graduates/alimni',
        ]);

        Membership::create([
            'status' => MembershipStatus::AFFILIATE,
            'description' => 'Affiliate membership for donors and supporters',
        ]);
    }
}
