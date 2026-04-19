<?php

declare(strict_types=1);

namespace App\Filament\Resources\Banks\Widgets;

use App\Models\Account;
use App\Models\Bank;
use App\Models\Card;
use App\Models\Transaction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

final class BankTransactionsWidget extends TableWidget
{
    public ?Bank $record = null;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Transactions')
            ->query(
                Transaction::query()
                    ->with(['transactable'])
                    ->where(function ($query): void {
                        $query->whereHasMorph('transactable', [Account::class, Card::class], function ($relatedQuery): void {
                            $relatedQuery->where('bank_id', $this->record?->id ?? 0);
                        });
                    })
                    ->latest('transacted_at'),
            )
            ->columns([
                TextColumn::make('transactable_type')
                    ->label('Source Type')
                    ->formatStateUsing(fn (string $state): string => class_basename($state))
                    ->badge(),
                TextColumn::make('transactable.name')
                    ->label('Source'),
                TextColumn::make('type')
                    ->badge(),
                TextColumn::make('amount')
                    ->formatStateUsing(fn ($state): string => number_format((float) $state, 2, '.', ',')),
                TextColumn::make('currency')
                    ->badge(),
                TextColumn::make('transacted_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultPaginationPageOption(5)
            ->paginationPageOptions([5, 10, 25]);
    }
}
