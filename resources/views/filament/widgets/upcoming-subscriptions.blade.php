<div class="space-y-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
    <div>
        <h2 class="text-lg font-semibold text-gray-950 dark:text-white">Upcoming subscriptions</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Recurring payments due this month.</p>
    </div>

    @forelse ($subscriptions as $subscription)
        <div class="flex items-center justify-between gap-4 rounded-xl border border-gray-200 px-4 py-3 dark:border-gray-800">
            <div>
                <div class="font-medium text-gray-950 dark:text-white">{{ $subscription->name }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $subscription->card->name }}
                    &middot;
                    {{ $subscription->interval->getLabel() }}
                </div>
            </div>

            <div class="text-right">
                <div class="font-medium text-gray-950 dark:text-white">
                    {{ $subscription->currency }} {{ number_format((float) $subscription->price, 2, '.', ',') }}
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $subscription->nextPaymentAt()->format('M j, Y') }}
                </div>
            </div>
        </div>
    @empty
        <p class="text-sm text-gray-500 dark:text-gray-400">No subscriptions are due this month.</p>
    @endforelse
</div>
