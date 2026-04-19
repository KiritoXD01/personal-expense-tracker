<?php

declare(strict_types=1);

namespace App\Filament\Resources\Cards\Pages;

use App\Filament\Resources\Cards\CardResource;
use App\Filament\Resources\Cards\Widgets\CardTransactionsWidget;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

final class ViewCard extends ViewRecord
{
    protected static string $resource = CardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            CardTransactionsWidget::class,
        ];
    }
}
