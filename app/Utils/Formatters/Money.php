<?php
namespace App\Utils\Formatters;

final class Money
{
    public static function normalize($money): string
    {
        bcscale(2);
        return bcadd(0, $money);
    }
}
