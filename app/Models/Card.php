<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CardBrandEnum;
use App\Enums\CardTypeEnum;
use App\Enums\CurrencyEnum;
use Database\Factories\CardFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property-read int $user_id
 * @property-read int $bank_id
 * @property-read string $name
 * @property-read CardBrandEnum $brand
 * @property-read CardTypeEnum $type
 * @property-read CurrencyEnum $currency
 * @property-read string $last_four_digits
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 * @property-read User $user
 * @property-read Bank $bank
 */
#[Fillable([
    'user_id',
    'bank_id',
    'currency',
    'name',
    'brand',
    'type',
    'last_four_digits',
])]
#[HasFactory(CardFactory::class)]
final class Card extends Model
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

    public function bank(): BelongsTo
    {
        return $this->belongsTo(
            related: Bank::class,
            foreignKey: 'bank_id',
            ownerKey: 'id',
        );
    }

    protected function casts(): array
    {
        return [
            'brand' => CardBrandEnum::class,
            'type' => CardTypeEnum::class,
            'currency' => CurrencyEnum::class,
        ];
    }
}
