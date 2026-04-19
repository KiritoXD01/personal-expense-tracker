<?php

declare(strict_types=1);

use App\Enums\AccountTypeEnum;
use App\Enums\CurrencyEnum;
use App\Enums\TransactionTypeEnum;
use App\Filament\Resources\Accounts\Widgets\AccountTransactionsWidget;
use App\Models\Account;
use App\Models\Bank;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('account view page loads with its transactions', function (): void {
    $user = User::factory()->create();
    $bank = Bank::factory()->for($user)->create();
    $account = Account::factory()->for($user)->create([
        'bank_id' => $bank->id,
        'name' => 'Savings Account',
        'type' => AccountTypeEnum::SAVINGS,
        'currency' => CurrencyEnum::USD,
        'balance' => 1500.00,
    ]);

    $transaction = Transaction::factory()->forAccount()->create([
        'user_id' => $user->id,
        'transactable_type' => Account::class,
        'transactable_id' => $account->id,
        'type' => TransactionTypeEnum::INCOME,
        'description' => 'Paycheck',
        'amount' => 2500.00,
    ]);

    $this->actingAs($user)
        ->get("/dashboard/accounts/{$account->id}")
        ->assertSuccessful()
        ->assertSeeInOrder([
            'Savings Account',
            'Transactions',
        ]);

    Livewire::test(AccountTransactionsWidget::class, [
        'record' => $account,
    ])->assertOk()
        ->assertCanSeeTableRecords([$transaction]);
});
