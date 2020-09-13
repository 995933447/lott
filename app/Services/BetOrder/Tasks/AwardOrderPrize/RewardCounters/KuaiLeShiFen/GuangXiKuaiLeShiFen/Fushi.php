<?php
namespace App\Services\BetOrder\Tasks\AwardOrderPrize\RewardCounters\KuaiLeShiFen\GuangXiKuaiLeShiFen;

use App\Models\BetOrder;
use App\Models\Issue;
use App\Services\BetOrder\Tasks\AwardOrderPrize\RewardCounters\CountRewardResult;
use App\Services\BetOrder\Tasks\AwardOrderPrize\RewardCounters\RewardCounterContract;
use App\Utils\Formatters\ZeroPad;

class Fushi implements RewardCounterContract
{
    public static function handle(Issue $issue, BetOrder $betOrder): CountRewardResult
    {
        list($betItem, $betCode, $subBetCodes) = explode(':', $betOrder->codes[0], 3);
        $subBetCodes = ZeroPad::normalize(explode(',', $subBetCodes));

        $countRewardResult = new CountRewardResult();
        $countRewardResult->setRewardStatus(
            empty(array_diff($subBetCodes, ZeroPad::normalize($issue->reward_codes)))?
                CountRewardResult::REWARD_STATUS:
                CountRewardResult::LOST_STATUS
        );
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
}
