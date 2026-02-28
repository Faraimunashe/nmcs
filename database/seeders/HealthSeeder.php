<?php

namespace Database\Seeders;

use App\Models\ChronicCondition;
use App\Models\Dietary;
use App\Models\Disability;
use Illuminate\Database\Seeder;

class HealthSeeder extends Seeder
{
    public function run(): void
    {
        Disability::create([
            'name' => 'Visual Impairment',
            'description' => 'Partial or complete loss of vision',
        ]);

        Disability::create([
            'name' => 'Hearing Impairment',
            'description' => 'Partial or complete loss of hearing',
        ]);

        Disability::create([
            'name' => 'Mobility Impairment',
            'description' => 'Difficulty with movement or mobility',
        ]);

        Dietary::create([
            'name' => 'Vegetarian',
            'description' => 'No meat or fish',
        ]);

        Dietary::create([
            'name' => 'Vegan',
            'description' => 'No animal products',
        ]);

        Dietary::create([
            'name' => 'Halal',
            'description' => 'Halal dietary requirements',
        ]);

        Dietary::create([
            'name' => 'Gluten Free',
            'description' => 'No gluten-containing foods',
        ]);

        Dietary::create([
            'name' => 'Lactose Free',
            'description' => 'No dairy products',
        ]);

        ChronicCondition::create([
            'name' => 'Diabetes',
            'description' => 'Diabetes mellitus',
        ]);

        ChronicCondition::create([
            'name' => 'Hypertension',
            'description' => 'High blood pressure',
        ]);

        ChronicCondition::create([
            'name' => 'Asthma',
            'description' => 'Respiratory condition',
        ]);

        ChronicCondition::create([
            'name' => 'Epilepsy',
            'description' => 'Seizure disorder',
        ]);
    }
}
