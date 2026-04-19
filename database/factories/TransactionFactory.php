<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\TransactionTypeEnum;
use App\Models\Account;
use App\Models\Card;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
final class TransactionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'transactable_type' => Card::class,
            'transactable_id' => Card::factory(),
            'type' => TransactionTypeEnum::EXPENSE,
            'amount' => fake()->randomFloat(2, 1, 500),
            'description' => fake()->sentence(),
            'transacted_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }

    public function forAccount(): static
    {
        return $this->state(fn (): array => [
            'transactable_type' => Account::class,
            'transactable_id' => Account::factory(),
        ]);
    }

    public function income(): static
    {
        return $this->state(fn (): array => [
            'type' => TransactionTypeEnum::INCOME,
        ]);
    }
}
