<?php

declare(strict_types=1);

namespace App\Filament\Resources\Banks\Widgets;

use App\Models\Bank;
use App\Models\Card;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

final class BankCardsWidget extends TableWidget
{
    public ?Bank $record = null;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Cards')
            ->query(
                Card::query()
                    ->where('bank_id', $this->record?->id ?? 0)
                    ->latest('id'),
            )
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->badge(),
                TextColumn::make('credit_limit')
                    ->label('Credit Limit')
                    ->formatStateUsing(fn ($state): string => filled($state) ? number_format((float) $state, 2, '.', ',') : '-'),
                TextColumn::make('currency')
                    ->badge(),
                TextColumn::make('last_four_digits')
                    ->label('Last 4'),
            ])
            ->defaultPaginationPageOption(5)
            ->paginationPageOptions([5, 10, 25]);
    }
}
