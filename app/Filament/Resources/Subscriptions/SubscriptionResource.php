<?php

declare(strict_types=1);

namespace App\Filament\Resources\Subscriptions;

use App\Enums\CurrencyEnum;
use App\Enums\SubscriptionIntervalEnum;
use App\Filament\Resources\Subscriptions\Pages\ManageSubscriptions;
use App\Models\Card;
use App\Models\Subscription;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

final class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('user_id')
                    ->default(Auth::id()),
                Select::make('linked_to')
                    ->label('Card')
                    ->options(fn (): array => Card::query()
                        ->where('user_id', Auth::id())
                        ->pluck('name', 'id')
                        ->all())
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('name')
                    ->maxLength(255)
                    ->required(),
                TextInput::make('price')
                    ->numeric()
                    ->required(),
                TextInput::make('pay_date')
                    ->label('Pay day')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(31)
                    ->helperText('Day of the month the card should be charged.')
                    ->required(),
                Select::make('pay_month')
                    ->label('Pay month')
                    ->options(self::monthOptions())
                    ->visible(fn (Get $get): bool => $get('interval') === SubscriptionIntervalEnum::YEARLY->value)
                    ->required(fn (Get $get): bool => $get('interval') === SubscriptionIntervalEnum::YEARLY->value)
                    ->searchable()
                    ->preload(),
                Select::make('currency')
                    ->options(CurrencyEnum::class)
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('interval')
                    ->options([
                        SubscriptionIntervalEnum::MONTHLY->value => SubscriptionIntervalEnum::MONTHLY->getLabel(),
                        SubscriptionIntervalEnum::YEARLY->value => SubscriptionIntervalEnum::YEARLY->getLabel(),
                    ])
                    ->live()
                    ->afterStateUpdated(function (Set $set): void {
                        $set('pay_month', null);
                    })
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('card.name')
                    ->label('Card')
                    ->searchable(),
                TextColumn::make('price')
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => number_format((float) $state, 2, '.', ',')),
                TextColumn::make('pay_date')
                    ->label('Pay day')
                    ->badge(),
                TextColumn::make('pay_month')
                    ->label('Pay month')
                    ->badge()
                    ->formatStateUsing(fn (mixed $state): string => $state === null ? '—' : self::monthOptions()[(int) $state]),
                TextColumn::make('currency')
                    ->badge(),
                TextColumn::make('interval')
                    ->badge(),
                TextColumn::make('next_payment_at')
                    ->label('Next payment')
                    ->formatStateUsing(fn (Subscription $record): string => $record->nextPaymentAt()->format('M j, Y')),
            ])
            ->filters([
                //
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->where('user_id', Auth::id())
                ->with('card'))
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
            'index' => ManageSubscriptions::route('/'),
        ];
    }

    private static function monthOptions(): array
    {
        return [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ];
    }
}
