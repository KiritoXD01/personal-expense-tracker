<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Subscription;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

final class UpcomingSubscriptionsWidget extends Widget
{
    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.widgets.upcoming-subscriptions';

    protected function getViewData(): array
    {
        return [
            'subscriptions' => Subscription::query()
                ->with('card')
                ->where('user_id', Auth::id())
                ->get()
                ->filter(fn (Subscription $subscription): bool => $subscription->isDueInMonth(today()))
                ->sortBy(fn (Subscription $subscription): int => $subscription->nextPaymentAt()->getTimestamp())
                ->values(),
        ];
    }
}
