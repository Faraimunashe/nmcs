<?php

namespace App\Console\Commands;

use App\Enums\PaymentStatus;
use App\Models\ConferenceFee;
use App\Models\Payment;
use App\Models\Student;
use App\Services\TwilioSmsService;
use Illuminate\Console\Command;

class SendPaymentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send SMS reminders to attendants with outstanding conference balances';

    public function handle(): int
    {
        $this->info('Sending payment reminders...');

        $conferenceFee = ConferenceFee::getActiveFee();
        if (!$conferenceFee) {
            $this->warn('No active conference fee configured. Aborting.');
            return self::SUCCESS;
        }

        $amount = $conferenceFee->amount;
        if ($amount <= 0) {
            $this->warn('Active conference fee amount is zero. Aborting.');
            return self::SUCCESS;
        }

        $sms = app(TwilioSmsService::class);

        Student::with(['phones', 'payments' => function ($query) {
            $query->where('status', PaymentStatus::APPROVED);
        }])->chunk(100, function ($students) use ($sms, $amount) {
            foreach ($students as $student) {
                $totalPaid = $student->payments->sum('final_amount');
                $balance = $amount - $totalPaid;

                if ($balance <= 0) {
                    continue;
                }

                $phone = optional($student->phones()->first())->phone;
                if (!$phone) {
                    continue;
                }

                $appUrl = config('app.url');
                $message = sprintf(
                    'Hi %s, this is a gentle reminder that you still have an outstanding balance of %s for the NMCS Easter Conference. We sincerely encourage you to complete your payment, as we would truly love to have you with us. %s',
                    trim($student->firstnames . ' ' . $student->surname),
                    number_format($balance, 2),
                    $appUrl ? 'More details: ' . $appUrl : ''
                );

                $sms->send($phone, $message);
            }
        });

        $this->info('Payment reminders processed.');

        return self::SUCCESS;
    }
}

