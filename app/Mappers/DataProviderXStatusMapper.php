<?php

namespace App\Mappers;

class DataProviderXStatusMapper
{
    public static function toCode(string $status): ?int
    {
        return match ($status) {
            'authorised' => 1,
            'decline' => 2,
            'refunded' => 3,
            default => null,
        };
    }

    public static function toStatus(int $code): ?string
    {
        return match ($code) {
            1 => 'authorised',
            2 => 'decline',
            3 => 'refunded',
            default => null,
        };
    }
}
