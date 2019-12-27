<?php
namespace App\Utils\Math;

final class Compare
{
    public static function isOdd(int $number): bool
    {
        return $number % 2;
    }

    public static function symmetricIsHeaderBigger(array $numbers, int $index = 0): bool
    {
       $numbers = $index > 0? substr($numbers, $index, -1 * $index): $numbers;
       return array_pop($numbers) < array_shift($numbers);
    }

    public static function sumTwoPlacesIsOdd(int $nember): bool
    {
        return ($nember / 10 + $nember % 10) % 2;
    }

    public static function isAllSame(array $items): bool
    {
        return count(array_unique($items)) === 1;
    }

    public static function between($number, $min, $max, $includeMin = true, $includeMax = true): bool
    {
        if (!$min && !$max) {
            throw new \InvalidArgumentException('Second argument and third argument can not be both boolean false.');
        }
        if ($min === false)
            return $includeMax? $number <= $max: $number < $max;
        if ($max === false)
            return $includeMin? $number >= $min: $number > $min;
        if ($includeMin && $includeMax) return $number >= $min && $number <= $max;
        if ($includeMin && !$includeMax) return $number >= $min && $number < $max;
        if (!$includeMin && $includeMax) return $number > $min && $number <= $max;
    }

    public static function onePlacesBetween($number, $min, $max, $includeMin = true, $includeMax = true): bool
    {
        return static::between($number % 10, $min, $max, $includeMin, $includeMax);
    }
}
