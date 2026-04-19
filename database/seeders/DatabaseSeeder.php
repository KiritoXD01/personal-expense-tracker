<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Bank;
use App\Models\Card;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()
            ->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $banks = Bank::factory()
            ->count(3)
            ->create([
                'user_id' => $user->id,
            ]);

        foreach ($banks as $bank) {
            $cards = Card::factory()
                ->count(2)
                ->create([
                    'user_id' => $user->id,
                    'bank_id' => $bank->id,
                ]);

            $accounts = Account::factory()
                ->count(2)
                ->create([
                    'user_id' => $user->id,
                    'bank_id' => $bank->id,
                ]);

            foreach ($cards as $card) {
                Subscription::factory()
                    ->create([
                        'user_id' => $user->id,
                        'linked_to' => $card->id,
                    ]);

                Transaction::factory()
                    ->count(2)
                    ->create([
                        'transactable_id' => $card->id,
                    ]);
            }

            foreach ($accounts as $account) {
                Transaction::factory()
                    ->forAccount()
                    ->count(2)
                    ->create([
                        'transactable_id' => $account->id,
                    ]);
            }
        }
    }
}
