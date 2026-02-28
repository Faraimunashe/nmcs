<?php

namespace App\Enums;

enum PaymentPurpose: string
{
    case DEPOSIT = 'DEPOSIT';
    case FULL_PAYMENT = 'FULL_PAYMENT';
    case BALANCE = 'BALANCE';
}
