<?php

declare(strict_types=1);

use App\Enums\CardTypeEnum;
use App\Models\Bank;
use App\Models\Card;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('stores a credit limit for credit cards', function (): void {
    $user = User::factory()->create();
    $bank = Bank::factory()->for($user)->create();

    $card = Card::factory()->creditCard()->create([
        'user_id' => $user->id,
        'bank_id' => $bank->id,
    ]);

    expect($card->type)->toBe(CardTypeEnum::CREDIT);
    expect($card->credit_limit)->not->toBeNull();
});

it('allows debit cards without a credit limit', function (): void {
    $user = User::factory()->create();
    $bank = Bank::factory()->for($user)->create();

    $card = Card::factory()->create([
        'user_id' => $user->id,
        'bank_id' => $bank->id,
        'type' => CardTypeEnum::DEBIT,
        'credit_limit' => null,
    ]);

    expect($card->type)->toBe(CardTypeEnum::DEBIT);
    expect($card->credit_limit)->toBeNull();
});
