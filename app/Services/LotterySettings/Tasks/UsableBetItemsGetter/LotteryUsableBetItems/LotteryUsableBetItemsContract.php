<?php
namespace App\Services\LotterySettings\Tasks\UsableBetItemsGetter\LotteryUsableBetItems;

abstract class LotteryUsableBetItemsContract
{
    /** 维度是1的key代表投注类型,维度是2的key代表投注玩法名称,维度为3的key代表玩法投注项以及其值为相关配置
     * @var array
     */
    protected static $items = [];

    protected static $isParsed = false;

    public static function getItems(string $betTypeCode): array
    {
        if (!static::$isParsed) {
            static::$items = static::parseItems();
        }
        return static::$items[$betTypeCode];
    }

    private static function parseItems(): array
    {
        $parsedItems = [];
        foreach (static::$items as $betType => $betTypeItems) {
            // 根据可投注玩法字符串按|解析可投注玩法列表
            foreach ($betTypeItems as $playType => $playTypeItems) {
                $parsedPlayTypes = explode('|', $playType);

                $parsedAbleCodeItems = [];
                // 根据玩法可投注项字符串按|解析玩法可投注项
                foreach ($playTypeItems as $ableCodes => $ableCodeConfig) {
                    $parsedAbleCodes = explode('|', $ableCodes);
                    foreach ($parsedAbleCodes as $parsedAbleCode) {
                        $parsedAbleCodeItems[$parsedAbleCode] = $ableCodeConfig;
                    }
                }
                foreach ($parsedPlayTypes as $parsedPlayType) {
                    $parsedItems[$betType][$parsedPlayType] = $parsedAbleCodeItems;
                }
            }
        }
        return $parsedItems;
    }
}
