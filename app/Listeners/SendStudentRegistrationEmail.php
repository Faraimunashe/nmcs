<?php

namespace App\Listeners;

use App\Events\StudentRegistered;
use App\Mail\StudentRegisteredMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendStudentRegistrationEmail
{
    public function handle(StudentRegistered $event): void
    {
        $user = $event->student->user;
        if (! $user || empty($user->email)) {
            return;
        }

        try {
            Mail::to($user->email)->send(new StudentRegisteredMail($event->student));
        } catch (Throwable $e) {
            Log::error('Failed to send student registration email.', [
                'exception' => $e->getMessage(),
                'user_id' => $user->id,
                'student_id' => $event->student->id,
                'email' => $user->email,
            ]);
        }
    }
}
