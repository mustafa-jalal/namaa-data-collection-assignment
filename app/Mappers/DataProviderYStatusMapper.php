<?php

namespace App\Mappers;

class DataProviderYStatusMapper
{
    public static function toCode(string $status): ?int
    {
        return match ($status) {
            'authorised' => 100,
            'decline' => 200,
            'refunded' => 300,
            default => null,
        };
    }


    public static function toStatus(int $code): ?string
    {
        return match ($code) {
            100 => 'authorised',
            200 => 'decline',
            300 => 'refunded',
            default => null,
        };
    }
}
