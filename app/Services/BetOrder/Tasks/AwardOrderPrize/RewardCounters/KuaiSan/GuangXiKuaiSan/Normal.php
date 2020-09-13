<?php
namespace App\Services\BetOrder\Tasks\AwardOrderPrize\RewardCounters\KuaiSan\GuangXiKuaiSan;

use App\Models\BetOrder;
use App\Models\Issue;
use App\Services\BetOrder\Tasks\AwardOrderPrize\RewardCounters\CountRewardResult;
use App\Services\BetOrder\Tasks\AwardOrderPrize\RewardCounters\RewardCounterContract;
use App\Utils\Games\CodeToTransfers\KuaiSan\GuangXiKuaiSan;

class Normal implements RewardCounterContract
{
    public static function handle(Issue $issue, BetOrder $betOrder): CountRewardResult
    {
        list($betItem, $betCode) = explode(':', $betOrder->codes[0], 2);
        $resultCode = $issue->reward_codes[array_search($betItem, ['第一球', '第二球', '第三球'])];
        $rewardResult = null;
        switch ($betCode) {
            case '大':
            case '小':
                $rewardResult = GuangXiKuaiSan::toDaxiao($resultCode);
                break;
            case '单':
            case '双':
                $rewardResult = GuangXiKuaiSan::toDanShuang($resultCode);
                break;
            default:
                $rewardResult = $resultCode;
        }

        $countRewardResult = new CountRewardResult();

        $countRewardResult->setRewardStatus($betCode === $rewardResult? CountRewardResult::REWARD_STATUS: CountRewardResult::LOST_STATUS);
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
