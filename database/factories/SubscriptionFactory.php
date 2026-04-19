<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\CurrencyEnum;
use App\Enums\SubscriptionIntervalEnum;
use App\Models\Card;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subscription>
 */
final class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $interval = fake()->randomElement([
            SubscriptionIntervalEnum::MONTHLY,
            SubscriptionIntervalEnum::YEARLY,
        ]);

        return [
            'user_id' => User::factory(),
            'linked_to' => Card::factory(),
            'name' => fake()->company(),
            'price' => fake()->randomFloat(2, 1, 100),
            'currency' => CurrencyEnum::random(),
            'interval' => $interval,
            'pay_month' => $interval === SubscriptionIntervalEnum::YEARLY
                ? fake()->numberBetween(1, 12)
                : null,
            'pay_date' => fake()->numberBetween(1, 31),
        ];
    }
}
