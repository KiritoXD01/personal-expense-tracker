<?php

declare(strict_types=1);

namespace App\Filament\Resources\Banks\Pages;

use App\Filament\Resources\Banks\BankResource;
use App\Filament\Resources\Banks\Widgets\BankAccountsWidget;
use App\Filament\Resources\Banks\Widgets\BankCardsWidget;
use App\Filament\Resources\Banks\Widgets\BankTransactionsWidget;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

final class ViewBank extends ViewRecord
{
    protected static string $resource = BankResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            BankAccountsWidget::class,
            BankCardsWidget::class,
            BankTransactionsWidget::class,
        ];
    }
}
