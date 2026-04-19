<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\HasEnumUtils;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum CurrencyEnum: string implements HasLabel
{
    use HasEnumUtils;

    case USD = 'USD';
    case DOP = 'DOP';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::USD => 'US Dollar',
            self::DOP => 'Dominican Peso',
        };
    }
}
