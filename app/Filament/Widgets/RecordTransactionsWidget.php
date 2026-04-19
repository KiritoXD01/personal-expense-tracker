<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Model;

abstract class RecordTransactionsWidget extends TableWidget
{
    public ?Model $record = null;

    protected int|string|array $columnSpan = 'full';

    abstract protected function getTransactableType(): string;

    final public function table(Table $table): Table
    {
        return $table
            ->heading('Transactions')
            ->query(
                Transaction::query()
                    ->with(['transactable'])
                    ->where('transactable_type', $this->getTransactableType())
                    ->where('transactable_id', $this->record?->getKey() ?? 0)
                    ->latest('transacted_at'),
            )
            ->columns([
                TextColumn::make('type')
                    ->badge(),
                TextColumn::make('description')
                    ->searchable()
                    ->wrap(),
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
