<?php

declare(strict_types=1);

namespace App\Filament\Resources\Cards\Schemas;

use App\Enums\CardBrandEnum;
use App\Enums\CardTypeEnum;
use App\Enums\CurrencyEnum;
use App\Models\Bank;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

final class CardForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('user_id')
                    ->default(Auth::id()),
                Select::make('bank_id')
                    ->label('Bank')
                    ->options(fn (): array => Bank::query()
                        ->where('user_id', Auth::id())
                        ->pluck('name', 'id')
                        ->all())
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('name')
                    ->maxLength(255)
                    ->required(),
                Select::make('brand')
                    ->label('Card Brand')
                    ->options(CardBrandEnum::class)
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('type')
                    ->label('Card Type')
                    ->options(CardTypeEnum::class)
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('currency')
                    ->label('Currency')
                    ->options(CurrencyEnum::class)
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('last_four_digits')
                    ->label('Last Four Digits')
                    ->maxLength(4)
                    ->numeric()
                    ->required(),
            ]);
    }
}
