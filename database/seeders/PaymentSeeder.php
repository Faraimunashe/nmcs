<?php

namespace Database\Seeders;

use App\Models\PaymentCharge;
use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $noCharge = PaymentCharge::create([
            'narration' => 'No charge',
            'amount' => 0.00,
        ]);

        $ecocashCharge = PaymentCharge::create([
            'narration' => 'EcoCash transaction fee',
            'amount' => 1,
        ]);

        PaymentMethod::create([
            'payment_charge_id' => $noCharge->id,
            'name' => 'Cash',
            'requires_reference' => false,
            'is_active' => true,
        ]);

        PaymentMethod::create([
            'payment_charge_id' => $ecocashCharge->id,
            'name' => 'EcoCash',
            'requires_reference' => true,
            'is_active' => true,
        ]);

    }
}
