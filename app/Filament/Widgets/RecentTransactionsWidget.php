<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\Auth;

final class RecentTransactionsWidget extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaction::query()
                    ->with(['transactable'])
                    ->where('user_id', Auth::id())
                    ->latest('transacted_at'),
            )
            ->columns([
                TextColumn::make('transactable_type')
                    ->label('Source type')
                    ->formatStateUsing(fn (string $state): string => class_basename($state))
                    ->badge(),
                TextColumn::make('transactable.name')
                    ->label('Source'),
                TextColumn::make('type')
                    ->badge(),
                TextColumn::make('amount')
                    ->label('Amount')
                    ->formatStateUsing(fn (Transaction $record): string => "{$record->currency} ".number_format((float) $record->amount, 2, '.', ',')),
                TextColumn::make('transacted_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultPaginationPageOption(5)
            ->paginationPageOptions([5]);
    }
}
