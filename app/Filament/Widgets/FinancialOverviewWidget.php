<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\TransactionTypeEnum;
use App\Models\Account;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

final class FinancialOverviewWidget extends StatsOverviewWidget
{
    protected int|string|array $columnSpan = 'full';

    protected ?string $heading = 'Financial overview';

    protected function getStats(): array
    {
        $stats = [];

        $accountBalances = Account::query()
            ->select('currency')
            ->selectRaw('COALESCE(SUM(balance), 0) as total')
            ->where('user_id', Auth::id())
            ->groupBy('currency')
            ->orderBy('currency')
            ->pluck('total', 'currency');

        foreach ($accountBalances as $currency => $total) {
            $stats[] = Stat::make("Saved ({$currency})", $this->formatAmount($total))
                ->description('Across bank accounts')
                ->color('success');
        }

        if ($stats === []) {
            $stats[] = Stat::make('Saved balance', $this->formatAmount(0))
                ->description('Across bank accounts')
                ->color('success');
        }

        $stats[] = Stat::make(
            'Today transactions',
            (string) Transaction::query()
                ->where('user_id', Auth::id())
                ->whereDate('transacted_at', today())
                ->count(),
        )
            ->description('Recorded today')
            ->color('info');

        foreach ($this->getTodayTotals(TransactionTypeEnum::EXPENSE) as $currency => $total) {
            $stats[] = Stat::make("Today spending ({$currency})", $this->formatAmount($total))
                ->description('Expense transactions today')
                ->color('danger');
        }

        foreach ($this->getTodayTotals(TransactionTypeEnum::INCOME) as $currency => $total) {
            $stats[] = Stat::make("Today income ({$currency})", $this->formatAmount($total))
                ->description('Income transactions today')
                ->color('success');
        }

        return $stats;
    }

    protected function getTodayTotals(TransactionTypeEnum $type): array
    {
        return Transaction::query()
            ->select('currency')
            ->selectRaw('COALESCE(SUM(amount), 0) as total')
            ->where('user_id', Auth::id())
            ->whereDate('transacted_at', today())
            ->where('type', $type->value)
            ->groupBy('currency')
            ->orderBy('currency')
            ->pluck('total', 'currency')
            ->all();
    }

    protected function formatAmount(mixed $amount): string
    {
        return number_format((float) $amount, 2, '.', ',');
    }
}
