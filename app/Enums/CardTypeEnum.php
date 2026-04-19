<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\HasEnumUtils;

enum CardTypeEnum: string
{
    use HasEnumUtils;

    case DEBIT = 'debit';
    case CREDIT = 'credit';
}
