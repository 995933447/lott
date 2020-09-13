<?php
namespace App\Services\BetOrder\Tasks\AwardOrderPrize\RewardCounters\KuaiSan\GuangXiKuaiSan;

use App\Models\BetOrder;
use App\Models\Issue;
use App\Services\BetOrder\Tasks\AwardOrderPrize\RewardCounters\CountRewardResult;
use App\Services\BetOrder\Tasks\AwardOrderPrize\RewardCounters\RewardCounterContract;
use App\Utils\Games\CodeToTransfers\KuaiSan\GuangXiKuaiSan;

class Hezhi implements RewardCounterContract
{
    public static function handle(Issue $issue, BetOrder $betOrder): CountRewardResult
    {
        $betCode = explode(':', $betOrder->codes[0], 3)[2];
        $rewardCodesSum = array_sum($issue->reward_codes);

        $rewardResult = null;
        switch ($betCode) {
            case '大':
            case '小':
                $rewardResult = GuangXiKuaiSan::toHeZhiDaXiao($rewardCodesSum);
                break;
            case '单':
            case '双':
                $rewardResult = GuangXiKuaiSan::toDanShuang($rewardCodesSum);
                break;
            default:
                // 豹子
                $rewardResult = GuangXiKuaiSan::toBaoZi($issue->reward_codes);
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
