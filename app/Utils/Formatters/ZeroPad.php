<?php
namespace App\Utils\Formatters;

final class ZeroPad
{
    public static function normalize($numbers)
    {
        if (is_array($numbers)) {
            foreach ($numbers as &$number) {
                $number = str_pad($number, 2, '0', STR_PAD_LEFT);
            }
        } else {
            $numbers = str_pad($numbers, 2, '0', STR_PAD_LEFT);
        }
        return $numbers;
    }
}
