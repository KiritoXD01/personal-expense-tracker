<?php

declare(strict_types=1);

use App\Enums\CurrencyEnum;
use App\Enums\SubscriptionIntervalEnum;
use App\Models\Bank;
use App\Models\Card;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Carbon::setTestNow(Carbon::parse('2026-04-19 00:00:00'));
});

afterEach(function (): void {
    Carbon::setTestNow();
});

test('dashboard is accessible for authenticated users', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertSuccessful();
});

test('dashboard shows subscriptions due this month', function (): void {
    $user = User::factory()->create();
    $bank = Bank::factory()->for($user)->create();

    $monthlyCard = Card::factory()->for($user)->create([
        'bank_id' => $bank->id,
        'name' => 'Monthly Card',
        'currency' => CurrencyEnum::USD,
    ]);

    $yearlyCard = Card::factory()->for($user)->create([
        'bank_id' => $bank->id,
        'name' => 'Yearly Card',
        'currency' => CurrencyEnum::USD,
    ]);

    $laterCard = Card::factory()->for($user)->create([
        'bank_id' => $bank->id,
        'name' => 'Later Card',
        'currency' => CurrencyEnum::USD,
    ]);

    Subscription::create([
        'user_id' => $user->id,
        'linked_to' => $monthlyCard->id,
        'name' => 'Monthly Subscription',
        'price' => 12.99,
        'currency' => CurrencyEnum::USD,
        'interval' => SubscriptionIntervalEnum::MONTHLY,
        'pay_month' => null,
        'pay_date' => 20,
    ]);

    Subscription::create([
        'user_id' => $user->id,
        'linked_to' => $yearlyCard->id,
        'name' => 'Yearly Subscription',
        'price' => 99.99,
        'currency' => CurrencyEnum::USD,
        'interval' => SubscriptionIntervalEnum::YEARLY,
        'pay_month' => 4,
        'pay_date' => 25,
    ]);

    Subscription::create([
        'user_id' => $user->id,
        'linked_to' => $laterCard->id,
        'name' => 'Later Subscription',
        'price' => 7.50,
        'currency' => CurrencyEnum::USD,
        'interval' => SubscriptionIntervalEnum::MONTHLY,
        'pay_month' => null,
        'pay_date' => 10,
    ]);

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertSuccessful();
    $response->assertSee('Subscriptions this month');
    $response->assertSee('Monthly Subscription');
    $response->assertSee('Yearly Subscription');
    $response->assertSee('Apr 20, 2026');
    $response->assertSee('Apr 25, 2026');
    $response->assertDontSee('Later Subscription');
    $response->assertSee('Subscriptions this month');
});
