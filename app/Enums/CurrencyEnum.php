<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\HasEnumUtils;

enum CurrencyEnum: string
{
    use HasEnumUtils;

    case USD = 'USD';
    case DOP = 'DOP';
}
