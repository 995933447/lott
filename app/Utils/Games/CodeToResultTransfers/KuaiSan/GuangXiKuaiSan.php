<?php
namespace App\Utils\Games\CodeToTransfers\KuaiSan;

use App\Utils\Games\CodeToTransfers\DoubleFaceGameTransferContract;
use App\Utils\Math\Compare;

class GuangXiKuaiSan extends DoubleFaceGameTransferContract
{
    public static function toHeZhiDaXiao(string $code): string
    {
        return $code >= 11? '大': '小';
    }

    public static function toDaxiao(string $code): string
    {
        return $code < 4? '小': '大';
    }

    public static function toBaoZi(array $codes): ?string
    {
        return Compare::isAllSame($codes)? '豹子': null;
    }

    public static function getInvalidCode()
    {
        // TODO: Implement getInvalidCode() method.
    }

    public static function getInvalidSummationCode()
    {
        // TODO: Implement getInvalidSummationCode() method.
    }
}
