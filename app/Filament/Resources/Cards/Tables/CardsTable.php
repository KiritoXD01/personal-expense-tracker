<?php

declare(strict_types=1);

namespace App\Filament\Resources\Cards\Tables;

use App\Enums\CardTypeEnum;
use App\Models\Card;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

final class CardsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('bank.name')
                    ->label('Bank')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('brand')
                    ->badge()
                    ->searchable(),
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (CardTypeEnum $state): string => match ($state) {
                        CardTypeEnum::DEBIT => 'success',
                        CardTypeEnum::CREDIT => 'info',
                    }),
                TextColumn::make('credit_limit')
                    ->label('Credit Limit')
                    ->visible(fn (Card $record): bool => $record->type === CardTypeEnum::CREDIT)
                    ->formatStateUsing(fn (Card $record): string => "{$record->currency->value} ".number_format((float) $record->credit_limit, 2, '.', ',')),
                TextColumn::make('currency')
                    ->badge()
                    ->searchable(),
                TextColumn::make('last_four_digits')
                    ->label('Card Number')
                    ->formatStateUsing(fn (Card $record): string => "**** **** **** {$record->last_four_digits}"),
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
            ->modifyQueryUsing(fn (Builder $query) => $query->where('user_id', Auth::id()))
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
