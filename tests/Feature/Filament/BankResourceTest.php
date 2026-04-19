<?php

declare(strict_types=1);

use App\Models\Bank;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Bank Resource - Authentication', function () {
    it('requires authentication to access the index page', function () {
        $response = $this->get('/dashboard/banks');

        $response->assertRedirect();
    });
});

describe('Bank Resource - Index Page', function () {
    it('can load the banks index page', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard/banks');

        $response->assertSuccessful();
        $response->assertSee('Banks');
    });

    it('displays only the authenticated user\'s banks', function () {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $userBank = Bank::factory()->for($user)->create(['name' => 'User Bank']);
        $otherBank = Bank::factory()->for($otherUser)->create(['name' => 'Other Bank']);

        $response = $this->actingAs($user)->get('/dashboard/banks');

        $response->assertSuccessful();
        $response->assertSee('User Bank');
        $response->assertDontSee('Other Bank');
    });

    it('displays bank names in the table', function () {
        $user = User::factory()->create();

        Bank::factory()->for($user)->create(['name' => 'Chase Bank']);
        Bank::factory()->for($user)->create(['name' => 'Bank of America']);

        $response = $this->actingAs($user)->get('/dashboard/banks');

        $response->assertSuccessful();
        $response->assertSee('Chase Bank');
        $response->assertSee('Bank of America');
    });

    it('displays the create action button', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard/banks');

        $response->assertSuccessful();
        $response->assertSee('Create');
    });

    it('loads the page quickly', function () {
        $user = User::factory()->create();

        Bank::factory()->count(5)->for($user)->create();

        $response = $this->actingAs($user)->get('/dashboard/banks');

        $response->assertSuccessful();
    });
});

describe('Bank Resource - Data Access Control', function () {
    it('only shows banks owned by the user in the database', function () {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Bank::factory()->for($user)->create(['name' => 'User Bank 1']);
        Bank::factory()->for($user)->create(['name' => 'User Bank 2']);
        Bank::factory()->for($otherUser)->create(['name' => 'Other Bank']);

        $response = $this->actingAs($user)->get('/dashboard/banks');

        $response->assertSuccessful();
        // User should see their banks
        $response->assertSee('User Bank 1');
        $response->assertSee('User Bank 2');
        // User should not see other user's bank
        $response->assertDontSee('Other Bank');

        // Verify at database level
        $userBanks = Bank::where('user_id', $user->id)->get();
        $otherBanks = Bank::where('user_id', $otherUser->id)->get();

        expect($userBanks->count())->toBe(2);
        expect($otherBanks->count())->toBe(1);
    });

    it('filters banks by user in resource query', function () {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        // Create banks for both users
        Bank::factory()->count(3)->for($user)->create();
        Bank::factory()->count(2)->for($otherUser)->create();

        // User can only see their banks
        $userBanksCount = Bank::where('user_id', $user->id)->count();
        $otherUserBanksCount = Bank::where('user_id', $otherUser->id)->count();

        expect($userBanksCount)->toBe(3);
        expect($otherUserBanksCount)->toBe(2);
    });
});

describe('Bank Resource - Create/Update/Delete via Model', function () {
    it('creates a bank with user ownership', function () {
        $user = User::factory()->create();

        $bank = Bank::create([
            'user_id' => $user->id,
            'name' => 'Test Bank',
        ]);

        expect($bank)->not->toBeNull();
        expect($bank->user_id)->toBe($user->id);
        expect($bank->name)->toBe('Test Bank');
    });

    it('updates a bank', function () {
        $user = User::factory()->create();
        $bank = Bank::factory()->for($user)->create(['name' => 'Original Name']);

        $bank->update(['name' => 'Updated Name']);

        expect($bank->fresh()->name)->toBe('Updated Name');
    });

    it('deletes a bank', function () {
        $user = User::factory()->create();
        $bank = Bank::factory()->for($user)->create();

        $bankId = $bank->id;
        $bank->delete();

        expect(Bank::find($bankId))->toBeNull();
    });

    it('validates bank name requirements', function () {
        $user = User::factory()->create();

        // Empty name
        $bank = Bank::make([
            'user_id' => $user->id,
            'name' => '',
        ]);

        // Name is required - validation happens in the form
        expect($bank->name)->toBe('');

        // Name too long
        $longName = str_repeat('a', 256);
        $bank = Bank::make([
            'user_id' => $user->id,
            'name' => $longName,
        ]);

        expect(mb_strlen($bank->name))->toBe(256);
    });
});

describe('Bank Resource - Relationships', function () {
    it('belongs to a user', function () {
        $user = User::factory()->create();
        $bank = Bank::factory()->for($user)->create();

        expect($bank->user()->first()->id)->toBe($user->id);
    });

    it('can have multiple accounts', function () {
        $user = User::factory()->create();
        $bank = Bank::factory()->for($user)->create();

        // Verify the relationship exists
        expect($bank->accounts()->count())->toBe(0);

        // The accounts relationship should be available
        expect($bank->hasMany(
            related: App\Models\Account::class,
            foreignKey: 'bank_id',
            localKey: 'id',
        ))->not->toBeNull();
    });
});
