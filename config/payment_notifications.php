<?php

$keys = [
    'PENDING_PAYMENT_NOTIFY_EMAIL_1',
    'PENDING_PAYMENT_NOTIFY_EMAIL_2',
    'PENDING_PAYMENT_NOTIFY_EMAIL_3',
];

$emails = [];
foreach ($keys as $key) {
    $value = env($key);
    if (! is_string($value)) {
        continue;
    }
    $email = trim($value);
    if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL) !== false) {
        $emails[] = $email;
    }
}

return [
    'notify_emails' => $emails,
];
