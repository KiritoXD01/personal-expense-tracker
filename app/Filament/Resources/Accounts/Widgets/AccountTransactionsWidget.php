<?php

declare(strict_types=1);

namespace App\Filament\Resources\Accounts\Widgets;

use App\Filament\Widgets\RecordTransactionsWidget;
use App\Models\Account;

final class AccountTransactionsWidget extends RecordTransactionsWidget
{
    protected function getTransactableType(): string
    {
        return Account::class;
    }
}
