<?php

declare(strict_types=1);

namespace App\Filament\Resources\Cards;

use App\Filament\Resources\Cards\Pages\CreateCard;
use App\Filament\Resources\Cards\Pages\EditCard;
use App\Filament\Resources\Cards\Pages\ListCards;
use App\Filament\Resources\Cards\Pages\ViewCard;
use App\Filament\Resources\Cards\Schemas\CardForm;
use App\Filament\Resources\Cards\Schemas\CardInfolist;
use App\Filament\Resources\Cards\Tables\CardsTable;
use App\Models\Card;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

final class CardResource extends Resource
{
    protected static ?string $model = Card::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CreditCard;

    public static function form(Schema $schema): Schema
    {
        return CardForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CardInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CardsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCards::route('/'),
            'create' => CreateCard::route('/create'),
            'view' => ViewCard::route('/{record}'),
            'edit' => EditCard::route('/{record}/edit'),
        ];
    }
}
