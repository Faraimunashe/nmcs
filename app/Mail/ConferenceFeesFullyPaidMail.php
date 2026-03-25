<?php

namespace App\Mail;

use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConferenceFeesFullyPaidMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Student $student,
        public string $totalPaid,
        public string $conferenceFeeAmount,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Conference fees fully paid — NMCS',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.conference-fees-fully-paid',
        );
    }
}
