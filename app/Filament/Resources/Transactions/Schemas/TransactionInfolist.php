<?php

declare(strict_types=1);

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

final class TransactionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('transactable_type')
                    ->label('Source Type')
                    ->formatStateUsing(fn (string $state): string => class_basename($state))
                    ->badge(),
                TextEntry::make('transactable.name')
                    ->label('Source'),
                TextEntry::make('type')
                    ->badge(),
                TextEntry::make('currency')
                    ->badge(),
                TextEntry::make('amount')
                    ->formatStateUsing(fn (string $state): string => number_format((float) $state, 2, '.', ',')),
                TextEntry::make('description')
                    ->placeholder('-'),
                TextEntry::make('transacted_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
