<?php

namespace App\Enums;

enum MembershipStatus: string
{
    case ORDINARY = 'ORDINARY';
    case ASSOCIATE = 'ASSOCIATE';
    case AFFILIATE = 'AFFILIATE';
}
