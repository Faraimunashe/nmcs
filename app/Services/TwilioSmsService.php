<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class TwilioSmsService
{
    protected ?Client $client;

    public function __construct()
    {
        $sid = config('twilio.sid');
        $token = config('twilio.token');

        if ($sid && $token) {
            $this->client = new Client($sid, $token);
        } else {
            $this->client = null;
            Log::warning('Twilio credentials are not configured. SMS will not be sent.');
        }
    }

    public function send(string $to, string $message): void
    {
        if (!$this->client) {
            return;
        }

        if (empty($to)) {
            return;
        }

        try {
            $from = config('twilio.from');

            $this->client->messages->create($to, [
                'from' => $from,
                'body' => $message,
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to send Twilio SMS', [
                'to' => $to,
                'message' => $message,
                'exception' => $e->getMessage(),
            ]);
        }
    }
}

