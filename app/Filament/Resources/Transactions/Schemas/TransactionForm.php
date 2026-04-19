<?php

declare(strict_types=1);

namespace App\Filament\Resources\Transactions\Schemas;

use App\Enums\TransactionTypeEnum;
use App\Models\Account;
use App\Models\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

final class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('user_id')
                    ->default(Auth::id()),
                Select::make('transactable_type')
                    ->label('Source Type')
                    ->options([
                        Card::class => 'Card',
                        Account::class => 'Account',
                    ])
                    ->live()
                    ->afterStateUpdated(fn (Set $set) => $set('transactable_id', null))
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('transactable_id')
                    ->label('Source')
                    ->options(fn (Get $get): array => match ($get('transactable_type')) {
                        Card::class => Card::query()
                            ->where('user_id', Auth::id())
                            ->pluck('name', 'id')
                            ->all(),
                        Account::class => Account::query()
                            ->where('user_id', Auth::id())
                            ->pluck('name', 'id')
                            ->all(),
                        default => [],
                    })
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('type')
                    ->options(TransactionTypeEnum::class)
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('amount')
                    ->numeric()
                    ->required(),
                TextInput::make('description')
                    ->maxLength(255),
                DateTimePicker::make('transacted_at')
                    ->required()
                    ->default(now()),
            ]);
    }
}
