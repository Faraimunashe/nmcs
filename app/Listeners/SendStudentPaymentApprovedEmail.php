<?php

namespace App\Listeners;

use App\Events\PaymentApproved;
use App\Mail\StudentPaymentApprovedMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendStudentPaymentApprovedEmail
{
    public function handle(PaymentApproved $event): void
    {
        $user = $event->payment->student?->user;
        if (! $user || empty($user->email)) {
            return;
        }

        try {
            Mail::to($user->email)->send(new StudentPaymentApprovedMail($event->payment));
        } catch (Throwable $e) {
            Log::error('Failed to send student payment approved email.', [
                'exception' => $e->getMessage(),
                'user_id' => $user->id,
                'payment_id' => $event->payment->id,
                'student_id' => $event->payment->student_id,
                'email' => $user->email,
            ]);
        }
    }
}
