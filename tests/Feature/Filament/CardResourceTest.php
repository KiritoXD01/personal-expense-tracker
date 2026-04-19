<?php

declare(strict_types=1);

use App\Enums\CardBrandEnum;
use App\Enums\CardTypeEnum;
use App\Enums\CurrencyEnum;
use App\Models\Bank;
use App\Models\Card;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('card resource pages are accessible for the authenticated owner', function (): void {
    $user = User::factory()->create();
    $bank = Bank::factory()->for($user)->create();
    $card = Card::factory()->creditCard()->for($user)->create([
        'bank_id' => $bank->id,
        'name' => 'User Card',
        'brand' => CardBrandEnum::VISA,
        'currency' => CurrencyEnum::USD,
    ]);

    $this->actingAs($user);

    $this->get('/dashboard/cards')->assertSuccessful();
    $this->get('/dashboard/cards/create')->assertSuccessful();
    $this->get("/dashboard/cards/{$card->id}")->assertSuccessful();
    $this->get("/dashboard/cards/{$card->id}/edit")->assertSuccessful();
});

test('card resource index is scoped to the authenticated user', function (): void {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $bank = Bank::factory()->for($user)->create();
    $otherBank = Bank::factory()->for($otherUser)->create();

    Card::factory()->for($user)->for($bank)->create(['name' => 'User Card']);
    Card::factory()->for($otherUser)->for($otherBank)->create(['name' => 'Other Card']);

    $response = $this->actingAs($user)->get('/dashboard/cards');

    $response->assertSuccessful();
    $response->assertSee('User Card');
    $response->assertDontSee('Other Card');
});

test('credit cards can carry a credit limit', function (): void {
    $user = User::factory()->create();
    $bank = Bank::factory()->for($user)->create();

    $card = Card::factory()->creditCard()->for($user)->create([
        'bank_id' => $bank->id,
        'brand' => CardBrandEnum::MASTERCARD,
        'currency' => CurrencyEnum::USD,
        'credit_limit' => 5000.00,
    ]);

    expect($card->type)->toBe(CardTypeEnum::CREDIT);
    expect($card->credit_limit)->toBe('5000.00');
});
