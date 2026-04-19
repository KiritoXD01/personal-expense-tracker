<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Widgets\FinancialOverviewWidget;
use App\Filament\Widgets\RecentTransactionsWidget;
use App\Filament\Widgets\UpcomingSubscriptionsWidget;
use Filament\Pages\Dashboard as BaseDashboard;

final class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            FinancialOverviewWidget::class,
            UpcomingSubscriptionsWidget::class,
            RecentTransactionsWidget::class,
        ];
    }

    public function getColumns(): int|array
    {
        return 2;
    }
}
