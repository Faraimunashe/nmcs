<?php

namespace App\Listeners;

use App\Events\PaymentRejected;
use App\Mail\StudentPaymentRejectedMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendStudentPaymentRejectedEmail
{
    public function handle(PaymentRejected $event): void
    {
        $user = $event->payment->student?->user;
        if (! $user || empty($user->email)) {
            return;
        }

        try {
            Mail::to($user->email)->send(new StudentPaymentRejectedMail($event->payment));
        } catch (Throwable $e) {
            Log::error('Failed to send student payment rejected email.', [
                'exception' => $e->getMessage(),
                'user_id' => $user->id,
                'payment_id' => $event->payment->id,
                'student_id' => $event->payment->student_id,
                'email' => $user->email,
            ]);
        }
    }
}
