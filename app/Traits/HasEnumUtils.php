<?php

declare(strict_types=1);

namespace App\Traits;

trait HasEnumUtils
{
    /**
     * Returns the names of the enum cases.
     */
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    /**
     * Returns the values of the enum cases.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Returns the enum cases as an array.
     */
    public static function array(): array
    {
        return array_combine(self::values(), self::names());
    }

    /**
     * Returns a random enum case.
     */
    public static function random(): static
    {
        return self::cases()[array_rand(self::cases())];
    }
}
