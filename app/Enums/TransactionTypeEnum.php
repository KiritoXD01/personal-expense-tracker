<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\HasEnumUtils;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum TransactionTypeEnum: string implements HasLabel
{
    use HasEnumUtils;

    case INCOME = 'income';
    case EXPENSE = 'expense';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::INCOME => 'Income',
            self::EXPENSE => 'Expense',
        };
    }
}
