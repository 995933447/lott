<?php
namespace App\Utils\Timer;

class Date
{
    public static function getDatesBetweenDays(string $beginDate, string $endDate): int
    {
        if ($beginDate > $endDate) {
            $originalEndDate = $endDate;
            $endDate = $beginDate;
            $beginDate = $originalEndDate;
        }

        return (strtotime($endDate) - strtotime($beginDate)) / (24 * 3600);
    }

    public static function getYear(string $date)
    {
        return date('Y', strtotime($date));
    }

    public static function getMonth(string $date)
    {
        return date('m', strtotime($date));
    }

    public static function getDay(string $date)
    {
        return date('d', strtotime($date));
    }

    public static function isBetweenDate($date, $beginDate, $endDate): bool
    {
        return strtotime($date) >= strtotime($beginDate) && strtotime($date) <= strtotime($endDate);
    }
}
