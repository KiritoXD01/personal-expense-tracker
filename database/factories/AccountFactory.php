<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AccountTypeEnum;
use App\Enums\CurrencyEnum;
use App\Models\Account;
use App\Models\Bank;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Account>
 */
final class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'bank_id' => Bank::factory(),
            'name' => fake()->word(),
            'type' => AccountTypeEnum::random(),
            'currency' => CurrencyEnum::random(),
        ];
    }
}
