<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AccountTypeEnum;
use App\Enums\CurrencyEnum;
use Database\Factories\AccountFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property int $bank_id
 * @property string $name
 * @property AccountTypeEnum $type
 * @property CurrencyEnum $currency
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 * @property-read Bank $bank
 */
#[Fillable('user_id', 'bank_id', 'name', 'type', 'currency')]
#[UseFactory(AccountFactory::class)]
final class Account extends Model
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
            'type' => AccountTypeEnum::class,
            'currency' => CurrencyEnum::class,
        ];
    }
}
