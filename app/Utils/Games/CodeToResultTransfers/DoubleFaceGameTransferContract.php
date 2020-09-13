<?php
namespace App\Utils\Games\CodeToTransfers;

use App\Utils\Math\Compare;
use http\Exception\InvalidArgumentException;

abstract class DoubleFaceGameTransferContract
{
    public static function toDanShuang(string $code): string
    {
        return Compare::isOdd($code)? '单': '双';
    }

    public static function toHeDanShuang(string $code): string
    {
        return Compare::sumTwoPlacesIsOdd($code)? '合单': '合双';
    }

    public static function toLongHu(array $codes, $index = 1):string
    {
        if ($index < 1)
            throw new InvalidArgumentException('Argument second can not be less than 1.');
        return Compare::symmetricIsHeaderBigger($codes, --$index)? '龙': '虎';
    }

    abstract public static function toHeZhiDaXiao(string $code): string;

    abstract public static function toDaxiao(string $code): string;

    abstract public static function getInvalidSummationCode();

    abstract public static function getInvalidCode();
}
