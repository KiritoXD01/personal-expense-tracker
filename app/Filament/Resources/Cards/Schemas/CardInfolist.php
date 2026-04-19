<?php

declare(strict_types=1);

namespace App\Filament\Resources\Cards\Schemas;

use App\Enums\CardTypeEnum;
use App\Models\Card;
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
                TextEntry::make('credit_limit')
                    ->label('Credit Limit')
                    ->visible(fn (Card $record): bool => $record->type === CardTypeEnum::CREDIT)
                    ->formatStateUsing(fn (Card $record): string => "{$record->currency->value} ".number_format((float) $record->credit_limit, 2, '.', ',')),
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
