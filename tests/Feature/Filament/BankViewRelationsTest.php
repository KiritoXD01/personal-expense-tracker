<?php

declare(strict_types=1);

use App\Enums\CardBrandEnum;
use App\Enums\CardTypeEnum;
use App\Enums\CurrencyEnum;
use App\Enums\TransactionTypeEnum;
use App\Models\Account;
use App\Models\Bank;
use App\Models\Card;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('shows the related accounts, cards, and transactions when viewing a bank', function (): void {
    $user = User::factory()->create();
    Storage::fake('public');
    Storage::disk('public')->putFileAs(
        'bank-logos',
        UploadedFile::fake()->image('logo.png'),
        'logo.png',
    );

    $bank = Bank::factory()->for($user)->create([
        'name' => 'Primary Bank',
        'logo' => 'bank-logos/logo.png',
    ]);

    $account = Account::factory()->for($user)->create([
        'bank_id' => $bank->id,
        'name' => 'Savings Account',
        'currency' => CurrencyEnum::USD,
        'balance' => 1500.00,
    ]);

    $card = Card::factory()->creditCard()->for($user)->create([
        'bank_id' => $bank->id,
        'name' => 'Travel Card',
        'brand' => CardBrandEnum::VISA,
        'type' => CardTypeEnum::CREDIT,
        'currency' => CurrencyEnum::USD,
        'credit_limit' => 5000.00,
    ]);

    Transaction::factory()->create([
        'user_id' => $user->id,
        'transactable_type' => Account::class,
        'transactable_id' => $account->id,
        'type' => TransactionTypeEnum::INCOME,
        'description' => 'Paycheck',
        'amount' => 2500.00,
    ]);

    Transaction::factory()->create([
        'user_id' => $user->id,
        'transactable_type' => Card::class,
        'transactable_id' => $card->id,
        'type' => TransactionTypeEnum::EXPENSE,
        'description' => 'Flight',
        'amount' => 300.00,
    ]);

    $this->actingAs($user)
        ->get("/dashboard/banks/{$bank->id}")
        ->assertSuccessful()
        ->assertSeeInOrder([
            'Primary Bank',
            'Accounts',
            'Cards',
            'Transactions',
        ])
        ->assertSee('bank-logos/logo.png');

    Livewire::test(App\Filament\Resources\Banks\Widgets\BankAccountsWidget::class, [
        'record' => $bank,
    ])->assertOk()
        ->assertCanSeeTableRecords([$account]);

    Livewire::test(App\Filament\Resources\Banks\Widgets\BankCardsWidget::class, [
        'record' => $bank,
    ])->assertOk()
        ->assertCanSeeTableRecords([$card]);

    Livewire::test(App\Filament\Resources\Banks\Widgets\BankTransactionsWidget::class, [
        'record' => $bank,
    ])->assertOk()
        ->assertCanSeeTableRecords(Transaction::query()->where('user_id', $user->id)->get());
});
