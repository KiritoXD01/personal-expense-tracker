<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\HasEnumUtils;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum CardTypeEnum: string implements HasLabel
{
    use HasEnumUtils;

    case DEBIT = 'debit';
    case CREDIT = 'credit';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::DEBIT => 'Debit',
            self::CREDIT => 'Credit',
        };
    }
}
