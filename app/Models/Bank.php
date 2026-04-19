<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\BankFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property-read int $user_id
 * @property-read string $name
 * @property-read string|null $logo
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 * @property-read User $user
 * @property-read Card[] $cards
 * @property-read Account[] $accounts
 */
#[Fillable('user_id', 'name', 'logo')]
#[HasFactory(BankFactory::class)]
final class Bank extends Model
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

    public function cards(): HasMany
    {
        return $this->hasMany(
            related: Card::class,
            foreignKey: 'bank_id',
            localKey: 'id',
        );
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(
            related: Account::class,
            foreignKey: 'bank_id',
            localKey: 'id',
        );
    }
}
