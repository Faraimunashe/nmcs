<?php

namespace App\Listeners;

use App\Events\PendingPaymentRecorded;
use App\Mail\PendingPaymentAdminNotificationMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendPendingPaymentAdminNotification
{
    public function handle(PendingPaymentRecorded $event): void
    {
        $emails = config('payment_notifications.notify_emails', []);
        if ($emails === []) {
            return;
        }

        $payment = $event->payment->loadMissing([
            'student.user',
            'student.phones',
            'student.institution.region',
            'paymentMethod',
            'paymentRecipient',
        ]);

        try {
            Mail::to($emails)->send(new PendingPaymentAdminNotificationMail($payment));
        } catch (Throwable $e) {
            Log::error('Failed to send pending payment admin notification email.', [
                'exception' => $e->getMessage(),
                'payment_id' => $payment->id,
                'recipients' => $emails,
            ]);
        }
    }
}
