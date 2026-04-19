<?php

declare(strict_types=1);

namespace App\Filament\Resources\Cards;

use App\Enums\CardBrandEnum;
use App\Enums\CardTypeEnum;
use App\Enums\CurrencyEnum;
use App\Filament\Resources\Cards\Pages\ManageCards;
use App\Models\Bank;
use App\Models\Card;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

final class CardResource extends Resource
{
    protected static ?string $model = Card::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CreditCard;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('user_id')
                    ->default(Auth::id()),
                TextInput::make('name')
                    ->maxLength(255)
                    ->required(),
                Select::make('bank_id')
                    ->label('Bank')
                    ->options(fn (): array => Bank::query()->where('user_id', Auth::id())->pluck('name', 'id')->all())
                    ->searchable()
                    ->preload()
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
                    ->required(),
                Select::make('currency')
                    ->label('Currency')
                    ->options(CurrencyEnum::class)
                    ->required(),
                TextInput::make('last_four_digits')
                    ->label('Last Four Digits')
                    ->maxLength(4)
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('brand')
                    ->label('Brand')
                    ->formatStateUsing(fn (CardBrandEnum $state): string => $state->value)
                    ->view('filament.columns.card-brand-column'),
                TextColumn::make('type')
                    ->label('Type')
                    ->formatStateUsing(fn (CardTypeEnum $state): string => $state->value)
                    ->badge()
                    ->color(fn (CardTypeEnum $state): string => match ($state) {
                        CardTypeEnum::DEBIT => 'success',
                        CardTypeEnum::CREDIT => 'info',
                    }),
                TextColumn::make('currency')
                    ->label('Currency')
                    ->formatStateUsing(fn (CurrencyEnum $state): string => $state->value),
                TextColumn::make('last_four_digits')
                    ->label('Card Number')
                    ->formatStateUsing(fn (Card $record): string => "**** **** **** {$record->last_four_digits}"),
                TextColumn::make('bank.name')
                    ->label('Bank')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->where('user_id', auth()->id()))
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCards::route('/'),
        ];
    }
}
