<?php
namespace App\Utils\Marker;

final class MarkInstance
{
    private static $marked = [];

    public static function mark($name, $value)
    {
        return static::$marked[$name] = $value;
    }

    public static function marked($name)
    {
        return static::$marked[$name];
    }

    public static function hasMarked($name): bool
    {
        return isset(static::$marked[$name]);
    }
}
