<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CurrencyEnum;
use App\Enums\SubscriptionIntervalEnum;
use Database\Factories\SubscriptionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use LogicException;

/**
 * @property int $id
 * @property int $user_id
 * @property int $linked_to
 * @property string $name
 * @property string $price
 * @property CurrencyEnum $currency
 * @property SubscriptionIntervalEnum $interval
 * @property int|null $pay_month
 * @property int|null $pay_date
 * @property-read User $user
 * @property-read Card $card
 */
#[Fillable([
    'user_id',
    'linked_to',
    'name',
    'price',
    'currency',
    'interval',
    'pay_month',
    'pay_date',
])]
#[UseFactory(SubscriptionFactory::class)]
final class Subscription extends Model
{
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'user_id',
            ownerKey: 'id',
        );
    }

    public function card(): BelongsTo
    {
        return $this->belongsTo(
            related: Card::class,
            foreignKey: 'linked_to',
            ownerKey: 'id',
        );
    }

    public function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'interval' => SubscriptionIntervalEnum::class,
            'currency' => CurrencyEnum::class,
            'pay_month' => 'integer',
            'pay_date' => 'integer',
        ];
    }

    public function nextPaymentAt(?Carbon $reference = null): Carbon
    {
        $reference ??= now();
        $payDate = max(1, min(31, (int) ($this->pay_date ?? 1)));

        return match ($this->interval) {
            SubscriptionIntervalEnum::MONTHLY => $this->nextMonthlyPaymentAt($reference, $payDate),
            SubscriptionIntervalEnum::YEARLY => $this->nextYearlyPaymentAt($reference, $payDate),
            default => throw new LogicException('Unsupported subscription interval.'),
        };
    }

    public function isDueInMonth(?Carbon $reference = null): bool
    {
        $reference ??= now();

        return $this->nextPaymentAt($reference)->isSameMonth($reference);
    }

    private function nextMonthlyPaymentAt(Carbon $reference, int $payDate): Carbon
    {
        $candidate = $this->createPaymentDate(
            year: $reference->year,
            month: $reference->month,
            payDate: $payDate,
        );

        if ($candidate->lt($reference->copy()->startOfDay())) {
            $nextMonth = $reference->copy()->addMonthNoOverflow();

            return $this->createPaymentDate(
                year: $nextMonth->year,
                month: $nextMonth->month,
                payDate: $payDate,
            );
        }

        return $candidate;
    }

    private function nextYearlyPaymentAt(Carbon $reference, int $payDate): Carbon
    {
        $payMonth = $this->pay_month;

        if ($payMonth === null) {
            throw new LogicException('Yearly subscriptions require a pay month.');
        }

        $candidate = $this->createPaymentDate(
            year: $reference->year,
            month: $payMonth,
            payDate: $payDate,
        );

        if ($candidate->lt($reference->copy()->startOfDay())) {
            $nextYear = $reference->copy()->addYear();

            return $this->createPaymentDate(
                year: $nextYear->year,
                month: $payMonth,
                payDate: $payDate,
            );
        }

        return $candidate;
    }

    private function createPaymentDate(int $year, int $month, int $payDate): Carbon
    {
        $day = min($payDate, Carbon::create($year, $month, 1)->daysInMonth);

        return Carbon::create($year, $month, $day)->startOfDay();
    }
}
