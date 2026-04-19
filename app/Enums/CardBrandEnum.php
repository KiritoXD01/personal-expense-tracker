<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\HasEnumUtils;

enum CardBrandEnum: string
{
    use HasEnumUtils;

    case VISA = 'visa';
    case MASTERCARD = 'mastercard';
    case AMERICAN_EXPRESS = 'american_express';
}
