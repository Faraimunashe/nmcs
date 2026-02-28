<?php

namespace Database\Seeders;

use App\Models\Institution;
use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $harare = Region::create(['name' => 'Harare']);
        $bulawayo = Region::create(['name' => 'Bulawayo']);
        $masvingo = Region::create(['name' => 'Masvingo']);
        $mutare = Region::create(['name' => 'Mutare']);
        $gweru = Region::create(['name' => 'Gweru']);

        Institution::create([
            'region_id' => $harare->id,
            'code' => 'UZ',
            'name' => 'University of Zimbabwe',
        ]);

        Institution::create([
            'region_id' => $harare->id,
            'code' => 'MSU',
            'name' => 'Midlands State University',
        ]);

        Institution::create([
            'region_id' => $bulawayo->id,
            'code' => 'NUST',
            'name' => 'National University of Science and Technology',
        ]);

        Institution::create([
            'region_id' => $masvingo->id,
            'code' => 'MU',
            'name' => 'Masvingo University',
        ]);

        Institution::create([
            'region_id' => $mutare->id,
            'code' => 'AU',
            'name' => 'Africa University',
        ]);
    }
}
