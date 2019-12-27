<?php
namespace App\Services\LotterySettings\Tasks\UsableBetItemsGetter\LotteryUsableBetItems\KuaiSan;

use App\Models\BetType;
use App\Models\LotteryBetType;
use App\Services\LotterySettings\Tasks\UsableBetItemsGetter\LotteryUsableBetItems\LotteryUsableBetItemsContract;

class GuangXiKuaiSan extends LotteryUsableBetItemsContract
{
    const MUST_MORE_THAN_MONEY_DEFAULT = 10;
    const MUST_LESS_MONEY_DEFAULT = 20000;

    protected static $items = [
        // 和值投注类型下的玩法投注项
        BetType::HEZHI_TYPE_CODE => [
            '合值' => [
                '号码' => [
                    // 子投注项
                    'sub_bet_items' => ['单', '双', '大', '小', '豹子'],
                    // 赔率
                    'odds' => ['单' => 0.95, '双' => 0.95, '大' => 0.95, '小' => 0.95, '豹子' => 23],
                    // 投注号码数量必须大于0个且小于2个
                    'number_limit' => [0, 2],
                    // 投注金额限制必须大于10元且小于20000元
                    'money_limit' => [self::MUST_MORE_THAN_MONEY_DEFAULT, self::MUST_LESS_MONEY_DEFAULT],
                    // 有效投注盘面 X或Y盘
                    'valid_face' => [LotteryBetType::X_PLAY_FACE]
                ],
            ]
        ],
        BetType::NORMAL_TYPE_CODE => [
            '第一球|第二球|第三球' => [
                '1|2|3|4|5|6' => [
                    'odds' => 4,
                    'number_limit' => [0, 2],
                    'money_limit' => [self::MUST_MORE_THAN_MONEY_DEFAULT, self::MUST_LESS_MONEY_DEFAULT],
                    'valid_face' => [LotteryBetType::X_PLAY_FACE]
                ],
                '大|小|单|双' => [
                    'odds' => 0.95,
                    'number_limit' => [0, 2],
                    'money_limit' => [self::MUST_MORE_THAN_MONEY_DEFAULT, self::MUST_LESS_MONEY_DEFAULT],
                    'valid_face' => [LotteryBetType::X_PLAY_FACE]
                ]
            ]
        ]
    ];
}
