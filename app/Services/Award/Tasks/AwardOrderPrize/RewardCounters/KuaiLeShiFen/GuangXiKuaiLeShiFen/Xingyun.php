<?php
namespace App\Services\Award\Tasks\AwardOrderPrize\RewardCounters\KuaiLeShiFen\GuangXiKuaiLeShiFen;

use App\Models\BetOrder;
use App\Models\Issue;
use App\Services\Award\Tasks\AwardOrderPrize\RewardCounters\CountRewardResult;
use App\Services\Award\Tasks\AwardOrderPrize\RewardCounters\RewardCounterContract;
use App\Utils\Formatters\ZeroPad;

class Xingyun implements RewardCounterContract
{
    public static function handle(Issue $issue, BetOrder $betOrder): CountRewardResult
    {
        $rewardStatus = strpos($betOrder->codes[0], '特码') !== false || strpos($betOrder->codes[0], '平码') !== false?
            static::getBetPingmaItemRewardStatus($issue->reward_codes, $betOrder->codes[0]):
            static::getBetJIzhongJiItemewardStatus($issue->reward_codes, $betOrder->codes[0]);

        $countRewardResult = new CountRewardResult();
        $countRewardResult->setRewardStatus($rewardStatus? CountRewardResult::REWARD_STATUS: CountRewardResult::LOST_STATUS);
        $countRewardResult->setWinMoney(
            $countRewardResult->rewardStatus == CountRewardResult::REWARD_STATUS?
                bcmul($betOrder->odds, $betOrder->bet_money):
                bcmul(-1, $betOrder->bet_money)
        );
        $countRewardResult->setRewardCodes($countRewardResult->rewardStatus == CountRewardResult::REWARD_STATUS? $betOrder->codes: []);
        $countRewardResult->setRewardMoney(
            $countRewardResult->rewardStatus == CountRewardResult::REWARD_STATUS?
                bcadd(bcmul($betOrder->odds, $betOrder->bet_money), $betOrder->bet_money): 0
        );

        return $countRewardResult;
    }

    private static function getBetPingmaItemRewardStatus(array $rewardCodes, string $betItem): bool
    {
        list($betItem, $betCode, $subBetCode) = explode(':', $betItem, 3);
        return $subBetCode == $rewardCodes[array_search($betItem, ['平码一', '平码二', '平码三', '平码四', '特码'])];
    }

    private static function getBetJIzhongJiItemewardStatus(array $rewardCodes, string $betItem): bool
    {
        list($betItem, $betCode, $subBetCodes) = explode(':', $betItem, 3);
        $subBetCodes = ZeroPad::normalize(explode(',', $subBetCodes));
        $rewardCodes = ZeroPad::normalize($rewardCodes);

        switch ($betItem) {
            case '一中一':
            case '二中二':
            case '三中三':
                return empty(array_diff($subBetCodes, $rewardCodes));
            default:
                return count(array_diff($subBetCodes, $rewardCodes)) <= 1;
        }
    }
}
