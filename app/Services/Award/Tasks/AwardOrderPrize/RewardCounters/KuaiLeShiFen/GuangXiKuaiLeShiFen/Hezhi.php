<?php
namespace App\Services\Award\Tasks\AwardOrderPrize\RewardCounters\KuaiLeShiFen\GuangXiKuaiLeShiFen;

use App\Models\BetOrder;
use App\Models\Issue;
use App\Models\LotteryBetType;
use App\Services\Award\Tasks\AwardOrderPrize\RewardCounters\CountRewardResult;
use App\Services\Award\Tasks\AwardOrderPrize\RewardCounters\RewardCounterContract;
use App\Utils\Games\CodeToTransfers\KuaiLeShiFen\GuangXiKuaiLeShiFen;

class Hezhi implements RewardCounterContract
{
    public static function handle(Issue $issue, BetOrder $betOrder): CountRewardResult
    {
        $betCode = explode(':', $betOrder->codes[0], 3)[2];
        $rewardCodesSum = array_sum($issue->reward_codes);

        $rewardResult = null;
        switch ($betCode) {
            case $rewardCodesSum === GuangXiKuaiLeShiFen::getInvalidSummationCode():
                break;
            case '大':
            case '小':
                $rewardResult = GuangXiKuaiLeShiFen::toDaXiao($rewardCodesSum);
                break;
            case '单':
            case '双':
                $rewardResult = GuangXiKuaiLeShiFen::toDanShuang($rewardCodesSum);
                break;
            case '尾大':
            case '尾小':
                $rewardResult = GuangXiKuaiLeShiFen::toWeiDaxiao($rewardCodesSum);
                break;
            default:
                // 龙虎
                $rewardResult = GuangXiKuaiLeShiFen::toLongHu($issue->reward_codes);
        }

        $countRewardResult = new CountRewardResult();

        if (!$rewardResult) {
            $countRewardResult->setRewardStatus(
                $betOrder->play_face == LotteryBetType::X_PLAY_FACE?
                    CountRewardResult::LOST_STATUS:
                    CountRewardResult::NO_REWARD_NO_LOST_STATUS
            );
            $countRewardResult->setWinMoney($betOrder->play_face == LotteryBetType::X_PLAY_FACE? bcmul(-1, $betOrder->bet_money): 0);
        } else {
            $countRewardResult->setRewardStatus($betCode == $rewardResult? CountRewardResult::REWARD_STATUS: CountRewardResult::LOST_STATUS);
            $countRewardResult->setWinMoney(
                $countRewardResult->rewardStatus == CountRewardResult::REWARD_STATUS?
                    bcmul($betOrder->odds, $betOrder->bet_money):
                    bcmul(-1, $betOrder->bet_money)
            );
        }

        $countRewardResult->setRewardCodes($countRewardResult->rewardStatus == CountRewardResult::REWARD_STATUS? $betOrder->codes: []);
        $countRewardResult->setRewardMoney(
            $countRewardResult->rewardStatus == CountRewardResult::REWARD_STATUS?
                bcadd(bcmul($betOrder->odds, $betOrder->bet_money), $betOrder->bet_money): 0
        );

        return $countRewardResult;
    }
}
