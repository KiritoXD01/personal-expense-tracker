<?php

declare(strict_types=1);

namespace App\Filament\Resources\Accounts\Schemas;

use App\Enums\AccountTypeEnum;
use App\Enums\CurrencyEnum;
use App\Models\Bank;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

final class AccountForm
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
                Select::make('type')
                    ->options(AccountTypeEnum::class)
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('currency')
                    ->options(CurrencyEnum::class)
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }
}
