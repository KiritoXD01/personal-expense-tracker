<?php

declare(strict_types=1);

use App\Enums\CardBrandEnum;
use App\Enums\CardTypeEnum;
use App\Enums\CurrencyEnum;
use App\Enums\TransactionTypeEnum;
use App\Filament\Resources\Cards\Widgets\CardTransactionsWidget;
use App\Models\Bank;
use App\Models\Card;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('card view page loads with its transactions', function (): void {
    $user = User::factory()->create();
    $bank = Bank::factory()->for($user)->create();
    $card = Card::factory()->creditCard()->for($user)->create([
        'bank_id' => $bank->id,
        'name' => 'Travel Card',
        'brand' => CardBrandEnum::VISA,
        'type' => CardTypeEnum::CREDIT,
        'currency' => CurrencyEnum::USD,
        'credit_limit' => 5000.00,
    ]);

    $transaction = Transaction::factory()->create([
        'user_id' => $user->id,
        'transactable_type' => Card::class,
        'transactable_id' => $card->id,
        'type' => TransactionTypeEnum::EXPENSE,
        'description' => 'Flight',
        'amount' => 300.00,
    ]);

    $this->actingAs($user)
        ->get("/dashboard/cards/{$card->id}")
        ->assertSuccessful()
        ->assertSeeInOrder([
            'Travel Card',
            'Transactions',
        ]);

    Livewire::test(CardTransactionsWidget::class, [
        'record' => $card,
    ])->assertOk()
        ->assertCanSeeTableRecords([$transaction]);
});
