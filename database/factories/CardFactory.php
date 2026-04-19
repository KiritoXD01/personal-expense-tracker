<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\CardBrandEnum;
use App\Enums\CardTypeEnum;
use App\Enums\CurrencyEnum;
use App\Models\Bank;
use App\Models\Card;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Card>
 */
final class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = CardTypeEnum::random();

        return [
            'user_id' => User::factory(),
            'bank_id' => Bank::factory(),
            'name' => fake()->name(),
            'last_four_digits' => fake()->numerify('####'),
            'type' => $type,
            'brand' => CardBrandEnum::random(),
            'currency' => CurrencyEnum::random(),
            'credit_limit' => $type === CardTypeEnum::CREDIT ? fake()->randomFloat(2, 1000, 50000) : null,
        ];
    }

    public function creditCard(): static
    {
        return $this->state(fn (): array => [
            'type' => CardTypeEnum::CREDIT,
            'credit_limit' => fake()->randomFloat(2, 1000, 50000),
        ]);
    }
}
