<?php declare(strict_types = 1);

namespace App\Utils;

abstract class Helper
{
    public static function priceToString(int | float $amount): string
    {
        return number_format((float)($amount), 2, '.', '') . '€';
    }

    public static function dateTimeToString(string $date, string $time = ''): string
    {
        $dateTime = new \DateTimeImmutable("$date $time");
        return $dateTime->format('l jS \o\f F Y') . ' at ' . $dateTime->format('h:i A');
    }
}