<?php
namespace App\Utils\Games\CodeToTransfers\KuaiLeShiFen;

use App\Utils\Math\Compare;
use App\Utils\Games\CodeToTransfers\DoubleFaceGameTransferContract;

class GuangXiKuaiLeShiFen extends DoubleFaceGameTransferContract
{
   public static function toHeZhiDaXiao(string $code): string
   {
       return Compare::between($code, 56, 95)? '大': '小';
   }

   public static function toDaXiao(string $code): string
   {
       return Compare::between($code, 0, 10)? '小': '大';
   }

   public static function toWeiDaxiao(string $code): string
   {
       return Compare::onePlacesBetween($code, 5, false)? '尾大': '尾小';
   }

   public static function toFuLvShouXi(string $code): string
   {
       if ($code < 6) {
           return '福';
       } else if ($code < 11) {
            return '禄';
       } else if ($code < 16) {
           return '寿';
       } else {
           return '喜';
       }
   }

   public static function toHongLvLan(string $code): string
   {
       if (in_array($code, explode('、', '1、4、7、10、13、16、19'))) {
           return '红';
       } else if (in_array($code, explode('、', '2、5、8、11、14、17、20'))) {
           return '绿';
       } else {
           return '蓝';
       }
   }

   public static function getInvalidSummationCode()
   {
       return '55';
   }

   public static function getInvalidCode()
   {
       return '21';
   }
}
