<?php

declare(strict_types=1);

namespace App\Filament\Resources\Accounts\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

final class AccountInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('bank.name')
                    ->label('Bank'),
                TextEntry::make('name'),
                TextEntry::make('balance')
                    ->formatStateUsing(fn (string $state): string => number_format((float) $state, 2, '.', ',')),
                TextEntry::make('type')
                    ->badge(),
                TextEntry::make('currency')
                    ->badge(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
