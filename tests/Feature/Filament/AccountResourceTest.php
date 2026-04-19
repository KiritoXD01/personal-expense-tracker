<?php

declare(strict_types=1);

use App\Enums\AccountTypeEnum;
use App\Enums\CurrencyEnum;
use App\Models\Account;
use App\Models\Bank;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Account Resource - Authentication', function () {
    it('requires authentication to access the index page', function () {
        $response = $this->get('/dashboard/accounts');
        $response->assertRedirect();
    });

    it('requires authentication to access the create page', function () {
        $response = $this->get('/dashboard/accounts/create');
        $response->assertRedirect();
    });

    it('requires authentication to access the view page', function () {
        $account = Account::factory()->create();
        $response = $this->get("/dashboard/accounts/{$account->id}");
        $response->assertRedirect();
    });

    it('requires authentication to access the edit page', function () {
        $account = Account::factory()->create();
        $response = $this->get("/dashboard/accounts/{$account->id}/edit");
        $response->assertRedirect();
    });
});

describe('Account Resource - Index Page', function () {
    it('can load the accounts index page', function () {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/dashboard/accounts');
        $response->assertSuccessful();
        $response->assertSee('Accounts');
    });

    it('displays only the authenticated user\'s accounts', function () {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $userBank = Bank::factory()->for($user)->create();
        $otherBank = Bank::factory()->for($otherUser)->create();
        Account::factory()->for($user)->for($userBank)->create(['name' => 'User Account']);
        Account::factory()->for($otherUser)->for($otherBank)->create(['name' => 'Other Account']);
        $response = $this->actingAs($user)->get('/dashboard/accounts');
        $response->assertSuccessful();
        $response->assertSee('User Account');
        $response->assertDontSee('Other Account');
    });

    it('displays account names in the table', function () {
        $user = User::factory()->create();
        $bank = Bank::factory()->for($user)->create();
        Account::factory()->for($user)->for($bank)->create(['name' => 'Savings Account']);
        Account::factory()->for($user)->for($bank)->create(['name' => 'Checking Account']);
        $response = $this->actingAs($user)->get('/dashboard/accounts');
        $response->assertSuccessful();
        $response->assertSee('Savings Account');
        $response->assertSee('Checking Account');
    });

    it('displays the create action button', function () {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/dashboard/accounts');
        $response->assertSuccessful();
        $response->assertSee('Create');
    });

    it('loads multiple accounts without N+1 queries', function () {
        $user = User::factory()->create();
        $bank = Bank::factory()->for($user)->create();
        Account::factory()->count(5)->for($user)->for($bank)->create();
        $response = $this->actingAs($user)->get('/dashboard/accounts');
        $response->assertSuccessful();
    });
});

describe('Account Resource - View Page', function () {
    it('can load the view page', function () {
        $user = User::factory()->create();
        $bank = Bank::factory()->for($user)->create();
        $account = Account::factory()->for($user)->for($bank)->create();
        $response = $this->actingAs($user)->get("/dashboard/accounts/{$account->id}");
        $response->assertSuccessful();
        $response->assertSee($account->name);
    });

    it('displays account details on view page', function () {
        $user = User::factory()->create();
        $bank = Bank::factory()->for($user)->create(['name' => 'Test Bank']);
        $account = Account::factory()->for($user)->for($bank)->create(['name' => 'View Test Account']);
        $response = $this->actingAs($user)->get("/dashboard/accounts/{$account->id}");
        $response->assertSuccessful();
        $response->assertSee('View Test Account');
        $response->assertSee('Test Bank');
    });

    it('displays the edit action on view page', function () {
        $user = User::factory()->create();
        $bank = Bank::factory()->for($user)->create();
        $account = Account::factory()->for($user)->for($bank)->create();
        $response = $this->actingAs($user)->get("/dashboard/accounts/{$account->id}");
        $response->assertSuccessful();
        $response->assertSee('Edit');
    });
});

describe('Account Resource - Edit Page', function () {
    it('can load the edit page', function () {
        $user = User::factory()->create();
        $bank = Bank::factory()->for($user)->create();
        $account = Account::factory()->for($user)->for($bank)->create();
        $response = $this->actingAs($user)->get("/dashboard/accounts/{$account->id}/edit");
        $response->assertSuccessful();
    });

    it('displays delete action on edit page', function () {
        $user = User::factory()->create();
        $bank = Bank::factory()->for($user)->create();
        $account = Account::factory()->for($user)->for($bank)->create();
        $response = $this->actingAs($user)->get("/dashboard/accounts/{$account->id}/edit");
        $response->assertSuccessful();
        $response->assertSee('Delete');
    });
});

describe('Account Resource - Model Level', function () {
    it('creates accounts with proper ownership', function () {
        $user = User::factory()->create();
        $bank = Bank::factory()->for($user)->create();
        $account = Account::create([
            'user_id' => $user->id,
            'bank_id' => $bank->id,
            'name' => 'New Account',
            'balance' => 1000.50,
            'type' => AccountTypeEnum::SAVINGS,
            'currency' => CurrencyEnum::USD,
        ]);
        expect($account->user_id)->toBe($user->id);
        expect($account->bank_id)->toBe($bank->id);
        expect($account->balance)->toBe('1000.50');
        expect($account->type)->toBe(AccountTypeEnum::SAVINGS);
        expect($account->currency)->toBe(CurrencyEnum::USD);
    });

    it('updates account details', function () {
        $user = User::factory()->create();
        $bank = Bank::factory()->for($user)->create();
        $account = Account::factory()->for($user)->for($bank)->create(['name' => 'Original Name', 'balance' => 1000]);
        $account->update(['name' => 'Updated Name', 'balance' => 2500]);
        expect($account->fresh()->name)->toBe('Updated Name');
        expect($account->fresh()->balance)->toBe('2500.00');
    });

    it('belongs to a user and bank', function () {
        $user = User::factory()->create();
        $bank = Bank::factory()->for($user)->create();
        $account = Account::factory()->for($user)->for($bank)->create();
        expect($account->user()->first()->id)->toBe($user->id);
        expect($account->bank()->first()->id)->toBe($bank->id);
    });

    it('persists balance as decimal', function () {
        $user = User::factory()->create();
        $bank = Bank::factory()->for($user)->create();
        $account = Account::create([
            'user_id' => $user->id,
            'bank_id' => $bank->id,
            'name' => 'Decimal Account',
            'balance' => 1234.56,
            'type' => AccountTypeEnum::SAVINGS,
            'currency' => CurrencyEnum::USD,
        ]);
        expect($account->balance)->toBe('1234.56');
    });

    it('persists account type enum', function () {
        $user = User::factory()->create();
        $bank = Bank::factory()->for($user)->create();
        $account = Account::create([
            'user_id' => $user->id,
            'bank_id' => $bank->id,
            'name' => 'Checking Account',
            'balance' => 500,
            'type' => AccountTypeEnum::CHECKING,
            'currency' => CurrencyEnum::DOP,
        ]);
        expect($account->type)->toBe(AccountTypeEnum::CHECKING);
        expect($account->currency)->toBe(CurrencyEnum::DOP);
    });
});
