<?php

declare(strict_types=1);

namespace App\Filament\Resources\Cards\Widgets;

use App\Filament\Widgets\RecordTransactionsWidget;
use App\Models\Card;

final class CardTransactionsWidget extends RecordTransactionsWidget
{
    protected function getTransactableType(): string
    {
        return Card::class;
    }
}
