<?php

namespace Database\Seeders;

use App\Models\PaymentRecipient;
use Illuminate\Database\Seeder;

class PaymentRecipientSeeder extends Seeder
{
    public function run(): void
    {
        PaymentRecipient::create([
            'name' => 'John Moyo',
            'details' => 'EcoCash: 0771234567 | Bank: CBZ Account 1234567890',
            'is_active' => true,
        ]);

        PaymentRecipient::create([
            'name' => 'Sarah Chikwava',
            'details' => 'EcoCash: 0777654321 | Bank: NMB Account 0987654321',
            'is_active' => true,
        ]);

        PaymentRecipient::create([
            'name' => 'Tendai Nkomo',
            'details' => 'EcoCash: 0778889999 | Bank: Stanbic Account 1122334455',
            'is_active' => true,
        ]);
    }
}
