<?php

declare(strict_types=1);

use App\Enums\CurrencyEnum;
use App\Enums\TransactionTypeEnum;
use App\Filament\Widgets\FinancialOverviewWidget;
use App\Models\Account;
use App\Models\Bank;
use App\Models\Card;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function financial_overview_stats(FinancialOverviewWidget $widget): array
{
    $method = new ReflectionMethod($widget, 'getStats');
    $method->setAccessible(true);

    return $method->invoke($widget);
}

it('calculates dashboard stats from the authenticated user data', function (): void {
    $user = User::factory()->create();
    $bank = Bank::factory()->for($user)->create();

    $usdAccount = Account::factory()->for($user)->create([
        'bank_id' => $bank->id,
        'currency' => CurrencyEnum::USD,
        'balance' => 125.50,
    ]);

    $dopAccount = Account::factory()->for($user)->create([
        'bank_id' => $bank->id,
        'currency' => CurrencyEnum::DOP,
        'balance' => 200.00,
    ]);

    $card = Card::factory()->for($user)->create([
        'bank_id' => $bank->id,
        'currency' => CurrencyEnum::USD,
    ]);

    Transaction::factory()->create([
        'transactable_type' => Account::class,
        'transactable_id' => $usdAccount->id,
        'type' => TransactionTypeEnum::EXPENSE,
        'amount' => 45.00,
        'transacted_at' => now(),
    ]);

    Transaction::factory()->create([
        'transactable_type' => Account::class,
        'transactable_id' => $usdAccount->id,
        'type' => TransactionTypeEnum::INCOME,
        'amount' => 60.00,
        'transacted_at' => now(),
    ]);

    Transaction::factory()->create([
        'transactable_type' => Card::class,
        'transactable_id' => $card->id,
        'type' => TransactionTypeEnum::EXPENSE,
        'amount' => 20.00,
        'transacted_at' => now()->subDay(),
    ]);

    $this->actingAs($user);

    $stats = collect(financial_overview_stats(new FinancialOverviewWidget()))
        ->mapWithKeys(fn ($stat): array => [(string) $stat->getLabel() => (string) $stat->getValue()]);

    expect($stats['Saved (USD)'])->toBe('125.50');
    expect($stats['Saved (DOP)'])->toBe('200.00');
    expect($stats['Today transactions'])->toBe('2');
    expect($stats['Today spending (USD)'])->toBe('45.00');
    expect($stats['Today income (USD)'])->toBe('60.00');
});

it('renders the dashboard with finance widgets', function (): void {
    $user = User::factory()->create();
    $bank = Bank::factory()->for($user)->create();

    Account::factory()->for($user)->create([
        'bank_id' => $bank->id,
        'currency' => CurrencyEnum::USD,
        'balance' => 125.50,
    ]);

    $this->actingAs($user);

    $this->get('/dashboard')->assertSuccessful();
});
