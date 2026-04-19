<?php

declare(strict_types=1);

use App\Enums\CurrencyEnum;
use App\Models\Account;
use App\Models\Bank;
use App\Models\Card;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('links a transaction to a card source', function (): void {
    $user = User::factory()->create();
    $bank = Bank::factory()->for($user)->create();
    $card = Card::factory()->for($user)->create([
        'bank_id' => $bank->id,
        'currency' => CurrencyEnum::USD,
    ]);

    $transaction = Transaction::factory()->create([
        'transactable_type' => Card::class,
        'transactable_id' => $card->id,
        'amount' => 24.50,
    ]);

    expect($transaction->user)->toBeInstanceOf(User::class);
    expect($transaction->user->is($user))->toBeTrue();
    expect($transaction->transactable)->toBeInstanceOf(Card::class);
    expect($transaction->transactable->is($card))->toBeTrue();
    expect($transaction->currency)->toBe(CurrencyEnum::USD);
});

it('links a transaction to an account source', function (): void {
    $user = User::factory()->create();
    $bank = Bank::factory()->for($user)->create();
    $account = Account::factory()->for($user)->create([
        'bank_id' => $bank->id,
        'currency' => CurrencyEnum::DOP,
        'balance' => 2500.00,
    ]);

    $transaction = Transaction::factory()->forAccount()->create([
        'transactable_type' => Account::class,
        'transactable_id' => $account->id,
        'amount' => 120.00,
    ]);

    expect($transaction->user)->toBeInstanceOf(User::class);
    expect($transaction->user->is($user))->toBeTrue();
    expect($transaction->transactable)->toBeInstanceOf(Account::class);
    expect($transaction->transactable->is($account))->toBeTrue();
    expect($transaction->currency)->toBe(CurrencyEnum::DOP);
});
