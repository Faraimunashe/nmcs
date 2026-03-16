<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Twilio Credentials
    |--------------------------------------------------------------------------
    |
    | Set these values in your .env file:
    | TWILIO_SID, TWILIO_TOKEN, TWILIO_FROM.
    |
    */

    'sid' => env('TWILIO_SID'),

    'token' => env('TWILIO_TOKEN'),

    'from' => env('TWILIO_FROM'),

    /*
    |--------------------------------------------------------------------------
    | Admin Phone Numbers
    |--------------------------------------------------------------------------
    |
    | Comma-separated list of admin phone numbers to notify about new
    | pending payments. Example:
    |
    | TWILIO_ADMIN_PHONES="+263771234567,+263781234567"
    |
    */

    'admin_phones' => array_filter(array_map('trim', explode(',', (string) env('TWILIO_ADMIN_PHONES', '')))),
];

