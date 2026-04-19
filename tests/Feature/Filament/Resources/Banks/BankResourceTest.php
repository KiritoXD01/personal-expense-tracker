<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Resources\Banks;

use App\Filament\Resources\Banks\BankResource;
use App\Filament\Resources\Banks\Pages\ManageBanks;
use App\Models\Bank;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\Testing\TestAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('can render page', function () {
    Livewire::test(ManageBanks::class)
        ->assertSuccessful();
});

test('can list banks', function () {
    $banks = Bank::factory()->count(3)->create();

    Livewire::test(ManageBanks::class)
        ->assertCanSeeTableRecords($banks);
});

test('can create bank', function () {
    $user = User::factory()->create();
    $bank = Bank::factory()->make();

    Livewire::test(ManageBanks::class)
        ->callAction('create', data: [
            'user_id' => $user->id,
            'name' => $bank->name,
        ])
        ->assertHasNoActionErrors();

    $this->assertDatabaseHas(Bank::class, [
        'user_id' => $user->id,
        'name' => $bank->name,
    ]);
});

test('can edit bank', function () {
    $bank = Bank::factory()->create();
    $newBank = Bank::factory()->make();

    Livewire::test(ManageBanks::class)
        ->callTableAction('edit', $bank, data: [
            'name' => $newBank->name,
        ])
        ->assertHasNoTableActionErrors();

    expect($bank->refresh()->name)->toBe($newBank->name);
});

test('can delete bank', function () {
    $bank = Bank::factory()->create();

    Livewire::test(ManageBanks::class)
        ->callTableAction('delete', $bank)
        ->assertHasNoTableActionErrors();

    $this->assertDatabaseMissing(Bank::class, [
        'id' => $bank->id,
    ]);
});
