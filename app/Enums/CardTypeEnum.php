<?php

declare(strict_types=1);

namespace App\Enums;

enum CardTypeEnum: string
{
    case DEBIT = 'debit';
    case CREDIT = 'credit';
}
