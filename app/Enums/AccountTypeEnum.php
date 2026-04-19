<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum AccountTypeEnum: string implements HasLabel
{
    case CHECKING = 'checking';
    case SAVINGS = 'savings';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::CHECKING => 'Checking',
            self::SAVINGS => 'Savings',
        };
    }
}
