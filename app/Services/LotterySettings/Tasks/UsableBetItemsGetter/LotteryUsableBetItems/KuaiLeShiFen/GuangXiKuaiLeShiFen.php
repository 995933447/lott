<?php
namespace App\Services\LotterySettings\Tasks\UsableBetItemsGetter\LotteryUsableBetItems\KuaiLeShiFen;

use App\Models\BetType;
use App\Models\LotteryBetType;
use App\Services\LotterySettings\Tasks\UsableBetItemsGetter\LotteryUsableBetItems\LotteryUsableBetItemsContract;

class GuangXiKuaiLeShiFen extends LotteryUsableBetItemsContract
{
    const MUST_MORE_THAN_MONEY_DEFAULT = 10;
    const MUST_LESS_MONEY_DEFAULT = 20000;

    /** 游戏可用投注项,维度是1的key代表投注类型,维度是2的key代表投注玩法名称,维度为3的key代表玩法投项以及其值为相关配置
     * @var array
     */
    protected static $items = [
        // 和值投注类型下的玩法投注项
        BetType::HEZHI_TYPE_CODE => [
            '合值' => [
                '号码' => [
                    // 子投注项
                    'sub_bet_items' => ['单', '双', '大', '小', '尾大', '尾小', '龙', '虎'],
                    // 赔率
                    'odds' => 0.95,
                    // 投注号码数量必须大于0个且小于2个
                    'number_limit' => [0, 2],
                    // 投注金额限制必须大于10元且小于20000元
                    'money_limit' => [self::MUST_MORE_THAN_MONEY_DEFAULT, self::MUST_LESS_MONEY_DEFAULT],
                    // 投注有效面盘(X或Y盘)
                    'valid_face' => [LotteryBetType::X_PLAY_FACE]
                ],
            ]
        ],

        // 普通投注类型下的玩法投注项
        BetType::NORMAL_TYPE_CODE => [
            '平码一|平码二|平码三|平码四|特码' => [
                '单|双|大|小|尾大|尾小|合单|合双' => [
                    'odds' => 0.95,
                    'number_limit' => [0, 2],
                    'money_limit' => [self::MUST_MORE_THAN_MONEY_DEFAULT, self::MUST_LESS_MONEY_DEFAULT],
                    'valid_face' => [LotteryBetType::X_PLAY_FACE]
                ],
                '红|绿|蓝' => [
                    'odds' => 1.65,
                    'number_limit' => [0, 2],
                    'money_limit' => [self::MUST_MORE_THAN_MONEY_DEFAULT, self::MUST_LESS_MONEY_DEFAULT],
                    'valid_face' => [LotteryBetType::X_PLAY_FACE]
                ],
                '福|禄|寿|喜' => [
                    'odds' => 2.5,
                    'number_limit' => [0, 2],
                    'money_limit' => [self::MUST_MORE_THAN_MONEY_DEFAULT, self::MUST_LESS_MONEY_DEFAULT],
                    'valid_face' => [LotteryBetType::X_PLAY_FACE]
                ],
            ]
        ],

        // 幸运投注类型下的玩法投注项
        BetType::XINGYUN_TYPE_CODE => [
            '平码一|平码二|平码三|平码四|特码' => [
                '号码' => [
                    // 子投注项
                    'sub_bet_items' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21],
                    'odds' => 15,
                    'number_limit' => [0, 2],
                    'money_limit' => [self::MUST_MORE_THAN_MONEY_DEFAULT, self::MUST_LESS_MONEY_DEFAULT],
                    'valid_face' => [LotteryBetType::X_PLAY_FACE]
                ]
            ],
            '一中一' => [
                '号码' => [
                    'sub_bet_items' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21],
                    'odds' => 3,
                    'number_limit' => [0, 2],
                    'money_limit' => [self::MUST_MORE_THAN_MONEY_DEFAULT, self::MUST_LESS_MONEY_DEFAULT],
                    'valid_face' => [LotteryBetType::X_PLAY_FACE]
                ]
            ],
            '二中二' => [
                '号码' => [
                    'sub_bet_items' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21],
                    'odds' => 10,
                    'number_limit' => [1, 3],
                    'money_limit' => [self::MUST_MORE_THAN_MONEY_DEFAULT, self::MUST_LESS_MONEY_DEFAULT],
                    'valid_face' => [LotteryBetType::X_PLAY_FACE]
                ]
            ],
            '三中二' => [
                '号码' => [
                    'sub_bet_items' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21],
                    'odds' => 4,
                    'number_limit' => [2, 4],
                    'money_limit' => [self::MUST_MORE_THAN_MONEY_DEFAULT, self::MUST_LESS_MONEY_DEFAULT],
                    'valid_face' => [LotteryBetType::X_PLAY_FACE]
                ]
            ],
            '三中三' => [
                '号码' => [
                    'sub_bet_items' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21],
                    'odds' => 60,
                    'number_limit' => [2, 4],
                    'money_limit' => [self::MUST_MORE_THAN_MONEY_DEFAULT, self::MUST_LESS_MONEY_DEFAULT],
                    'valid_face' => [LotteryBetType::X_PLAY_FACE]
                ]
            ]
        ],

        // 不中投注类型下的玩法投注项
        BetType::BUZHONG_TYPE_CODE => [
            '三不中' => [
                '号码' => [
                    'sub_bet_items' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21],
                    'odds' => 0.9,
                    'number_limit' => [2, 4],
                    'money_limit' => [self::MUST_MORE_THAN_MONEY_DEFAULT, self::MUST_LESS_MONEY_DEFAULT],
                    'valid_face' => [LotteryBetType::X_PLAY_FACE]
                ]
            ],
            '四不中' => [
                '号码' => [
                    'sub_bet_items' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21],
                    'odds' => 1,
                    'number_limit' => [3, 5],
                    'money_limit' => [self::MUST_MORE_THAN_MONEY_DEFAULT, self::MUST_LESS_MONEY_DEFAULT],
                    'valid_face' => [LotteryBetType::X_PLAY_FACE]
                ]
            ],
            '五不中' => [
                '号码' => [
                    'sub_bet_items' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21],
                    'odds' => 2.5,
                    'number_limit' => [4, 6],
                    'money_limit' => [self::MUST_MORE_THAN_MONEY_DEFAULT, self::MUST_LESS_MONEY_DEFAULT],
                    'valid_face' => [LotteryBetType::X_PLAY_FACE]
                ]
            ],
            '六不中' => [
                '号码' => [
                    'sub_bet_items' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21],
                    'odds' => 4,
                    'number_limit' => [5, 7],
                    'money_limit' => [self::MUST_MORE_THAN_MONEY_DEFAULT, self::MUST_LESS_MONEY_DEFAULT],
                    'valid_face' => [LotteryBetType::X_PLAY_FACE]
                ]
            ],
            '七不中' => [
                '号码' => [
                    'sub_bet_items' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21],
                    'odds' => 7,
                    'number_limit' => [6, 8],
                    'money_limit' => [self::MUST_MORE_THAN_MONEY_DEFAULT, self::MUST_LESS_MONEY_DEFAULT],
                    'valid_face' => [LotteryBetType::X_PLAY_FACE]
                ]
            ],
            '八不中' => [
                '号码' => [
                    'sub_bet_items' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21],
                    'odds' => 9,
                    'number_limit' => [7, 9],
                    'money_limit' => [self::MUST_MORE_THAN_MONEY_DEFAULT, self::MUST_LESS_MONEY_DEFAULT],
                    'valid_face' => [LotteryBetType::X_PLAY_FACE]
                ]
            ],
            '九不中' => [
                '号码' => [
                    'sub_bet_items' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21],
                    'odds' => 14,
                    'number_limit' => [8, 10],
                    'money_limit' => [self::MUST_MORE_THAN_MONEY_DEFAULT, self::MUST_LESS_MONEY_DEFAULT],
                    'valid_face' => [LotteryBetType::X_PLAY_FACE]
                ]
            ],
        ],

        // 连赢投注类型下的玩法投注项
        BetType::LIANYING_TYPE_CODE => [
             '三连赢' => [
                '合值,平码一,平码二,平码三,平码四,特码' => [
                    'sub_bet_items' => ['大', '小', '单', '双'],
                    'odds' => 3.49,
                    'number_limit' => [2, 4],
                    'money_limit' => [self::MUST_MORE_THAN_MONEY_DEFAULT, self::MUST_LESS_MONEY_DEFAULT],
                    'valid_face' => [LotteryBetType::X_PLAY_FACE]
                ]
             ],
             '四连赢' => [
                 '合值,平码一,平码二,平码三,平码四,特码' => [
                     'sub_bet_items' => ['大', '小', '单', '双'],
                     'odds' => 6.41,
                     'number_limit' => [3, 5],
                     'money_limit' => [self::MUST_MORE_THAN_MONEY_DEFAULT, self::MUST_LESS_MONEY_DEFAULT],
                     'valid_face' => [LotteryBetType::X_PLAY_FACE]
                 ]
             ],
             '五连赢' => [
                 '合值,平码一,平码二,平码三,平码四,特码' => [
                     'sub_bet_items' => ['大', '小', '单', '双'],
                     'odds' => 11.23,
                     'number_limit' => [4, 6],
                     'money_limit' => [self::MUST_MORE_THAN_MONEY_DEFAULT, self::MUST_LESS_MONEY_DEFAULT],
                     'valid_face' => [LotteryBetType::X_PLAY_FACE]
                 ]
             ],
             '六连赢' => [
                 '合值,平码一,平码二,平码三,平码四,特码' => [
                     'sub_bet_items' => ['大', '小', '单', '双'],
                     'odds' => 19.18,
                     'number_limit' => [5, 7],
                     'money_limit' => [self::MUST_MORE_THAN_MONEY_DEFAULT, self::MUST_LESS_MONEY_DEFAULT],
                     'valid_face' => [LotteryBetType::X_PLAY_FACE]
                 ]
             ]
        ],

        // 复式投注类型下的玩法投注项
        BetType::FUSHI_TYPE_CODE => [
            '复式一中一' => [
                '号码' => [
                    'sub_bet_items' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21],
                    'odds' => 2,
                    'number_limit' => [0, 2],
                    'money_limit' => [self::MUST_MORE_THAN_MONEY_DEFAULT, self::MUST_LESS_MONEY_DEFAULT],
                    'valid_face' => [LotteryBetType::X_PLAY_FACE]
                ]
            ],
            '复式二中二' => [
                '号码' => [
                    'sub_bet_items' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21],
                    'odds' => 10,
                    'number_limit' => [1, 3],
                    'money_limit' => [self::MUST_MORE_THAN_MONEY_DEFAULT, self::MUST_LESS_MONEY_DEFAULT],
                    'valid_face' => [LotteryBetType::X_PLAY_FACE]
                ]
            ],
            '复式三中三' => [
                '号码' => [
                    'sub_bet_items' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21],
                    'odds' => 60,
                    'number_limit' => [2, 4],
                    'money_limit' => [self::MUST_MORE_THAN_MONEY_DEFAULT, self::MUST_LESS_MONEY_DEFAULT],
                    'valid_face' => [LotteryBetType::X_PLAY_FACE]
                ]
            ],
        ]
    ];
}
