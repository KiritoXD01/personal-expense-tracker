<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TransactionTypeEnum;
use Database\Factories\TransactionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property-read int $user_id
 * @property-read string $transactable_type
 * @property-read int $transactable_id
 * @property-read string $currency
 * @property-read TransactionTypeEnum $type
 * @property-read string $amount
 * @property-read string|null $description
 * @property-read Carbon $transacted_at
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 * @property-read User $user
 * @property-read Account|Card|null $transactable
 */
#[Fillable([
    'user_id',
    'transactable_type',
    'transactable_id',
    'currency',
    'type',
    'amount',
    'description',
    'transacted_at',
])]
#[HasFactory(TransactionFactory::class)]
final class Transaction extends Model
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

    public function transactable(): MorphTo
    {
        return $this->morphTo();
    }

    protected static function booted(): void
    {
        self::saving(static function (self $transaction): void {
            $source = $transaction->transactable;

            if ($source === null) {
                return;
            }

            $transaction->user_id = $source->user_id;
            $transaction->currency = $source->currency;
        });
    }

    protected function casts(): array
    {
        return [
            'type' => TransactionTypeEnum::class,
            'amount' => 'decimal:2',
            'transacted_at' => 'datetime',
        ];
    }
}
