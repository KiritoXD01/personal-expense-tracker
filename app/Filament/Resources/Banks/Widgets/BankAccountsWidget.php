<?php

declare(strict_types=1);

namespace App\Filament\Resources\Banks\Widgets;

use App\Models\Account;
use App\Models\Bank;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

final class BankAccountsWidget extends TableWidget
{
    public ?Bank $record = null;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Accounts')
            ->query(
                Account::query()
                    ->where('bank_id', $this->record?->id ?? 0)
                    ->latest('id'),
            )
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->badge(),
                TextColumn::make('currency')
                    ->badge(),
                TextColumn::make('balance')
                    ->formatStateUsing(fn ($state): string => filled($state) ? number_format((float) $state, 2, '.', ',') : '-'),
            ])
            ->defaultPaginationPageOption(5)
            ->paginationPageOptions([5, 10, 25]);
    }
}
