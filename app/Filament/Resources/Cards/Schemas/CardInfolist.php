<?php

declare(strict_types=1);

namespace App\Filament\Resources\Cards\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

final class CardInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('bank.name')
                    ->label('Bank'),
                TextEntry::make('name'),
                TextEntry::make('brand')
                    ->badge(),
                TextEntry::make('type')
                    ->badge(),
                TextEntry::make('currency')
                    ->badge(),
                TextEntry::make('last_four_digits'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
