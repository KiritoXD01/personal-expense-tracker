<?php

declare(strict_types=1);

use App\Enums\CurrencyEnum;
use App\Enums\SubscriptionIntervalEnum;
use App\Models\Bank;
use App\Models\Card;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('subscription resource page is accessible for the authenticated owner', function (): void {
    $user = User::factory()->create();
    $bank = Bank::factory()->for($user)->create();
    $card = Card::factory()->for($user)->create([
        'bank_id' => $bank->id,
        'name' => 'User Card',
        'currency' => CurrencyEnum::USD,
    ]);

    Subscription::factory()->create([
        'user_id' => $user->id,
        'linked_to' => $card->id,
        'name' => 'User Subscription',
        'currency' => CurrencyEnum::USD,
        'interval' => SubscriptionIntervalEnum::MONTHLY,
        'pay_month' => null,
    ]);

    $response = $this->actingAs($user)->get('/dashboard/subscriptions');

    $response->assertSuccessful();
    $response->assertSee('User Subscription');
});

test('subscription resource index is scoped to the authenticated user', function (): void {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $userBank = Bank::factory()->for($user)->create();
    $otherBank = Bank::factory()->for($otherUser)->create();

    $userCard = Card::factory()->for($user)->create([
        'bank_id' => $userBank->id,
        'name' => 'User Card',
        'currency' => CurrencyEnum::USD,
    ]);

    $otherCard = Card::factory()->for($otherUser)->create([
        'bank_id' => $otherBank->id,
        'name' => 'Other Card',
        'currency' => CurrencyEnum::USD,
    ]);

    Subscription::factory()->create([
        'user_id' => $user->id,
        'linked_to' => $userCard->id,
        'name' => 'User Subscription',
        'currency' => CurrencyEnum::USD,
        'interval' => SubscriptionIntervalEnum::MONTHLY,
        'pay_month' => null,
    ]);

    Subscription::factory()->create([
        'user_id' => $otherUser->id,
        'linked_to' => $otherCard->id,
        'name' => 'Other Subscription',
        'currency' => CurrencyEnum::USD,
        'interval' => SubscriptionIntervalEnum::MONTHLY,
        'pay_month' => null,
    ]);

    $response = $this->actingAs($user)->get('/dashboard/subscriptions');

    $response->assertSuccessful();
    $response->assertSee('User Subscription');
    $response->assertDontSee('Other Subscription');
});

test('subscription pay dates are persisted as a day of the month', function (): void {
    $user = User::factory()->create();
    $bank = Bank::factory()->for($user)->create();
    $card = Card::factory()->for($user)->create([
        'bank_id' => $bank->id,
        'name' => 'User Card',
        'currency' => CurrencyEnum::USD,
    ]);

    $subscription = Subscription::create([
        'user_id' => $user->id,
        'linked_to' => $card->id,
        'name' => 'User Subscription',
        'price' => 12.99,
        'currency' => CurrencyEnum::USD,
        'interval' => SubscriptionIntervalEnum::MONTHLY,
        'pay_date' => 15,
    ]);

    expect($subscription->pay_date)->toBe(15);
    expect($subscription->fresh()->pay_date)->toBe(15);
});
