<?php

namespace App\Listeners;

use App\Events\ConferenceFeesFullyPaid;
use App\Mail\ConferenceFeesFullyPaidMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendConferenceFeesFullyPaidEmail
{
    public function handle(ConferenceFeesFullyPaid $event): void
    {
        $user = $event->student->user;
        if (! $user || empty($user->email)) {
            return;
        }

        try {
            Mail::to($user->email)->send(
                new ConferenceFeesFullyPaidMail(
                    $event->student,
                    $event->totalPaid,
                    $event->conferenceFeeAmount,
                )
            );
        } catch (Throwable $e) {
            Log::error('Failed to send conference fees fully paid email.', [
                'exception' => $e->getMessage(),
                'user_id' => $user->id,
                'student_id' => $event->student->id,
                'email' => $user->email,
            ]);
        }
    }
}
