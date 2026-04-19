<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\HasEnumUtils;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum CardBrandEnum: string implements HasLabel
{
    use HasEnumUtils;

    case VISA = 'visa';
    case MASTERCARD = 'mastercard';
    case AMERICAN_EXPRESS = 'american_express';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::VISA => 'Visa',
            self::MASTERCARD => 'Mastercard',
            self::AMERICAN_EXPRESS => 'American Express',
        };
    }
}
