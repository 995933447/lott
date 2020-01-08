<?php
namespace App\Services\BetOrder\Tasks\AwardOrderPrize\RewardCounters\KuaiLeShiFen\GuangXiKuaiLeShiFen;

use App\Models\BetOrder;
use App\Models\Issue;
use App\Models\LotteryBetType;
use App\Services\BetOrder\Tasks\AwardOrderPrize\RewardCounters\CountRewardResult;
use App\Services\BetOrder\Tasks\AwardOrderPrize\RewardCounters\RewardCounterContract;
use App\Utils\Games\CodeToTransfers\KuaiLeShiFen\GuangXiKuaiLeShiFen;

class Hezhi implements RewardCounterContract
{
    public static function handle(Issue $issue, BetOrder $betOrder): CountRewardResult
    {
        $betCode = explode(':', $betOrder->codes[0], 3)[2];
        $rewardCodesSum = array_sum($issue->reward_codes);

        $rewardResult = null;
        switch ($betCode) {
            case $rewardCodesSum === (int)GuangXiKuaiLeShiFen::getInvalidSummationCode():
                break;
            case '大':
            case '小':
                $rewardResult = GuangXiKuaiLeShiFen::toHeZhiDaXiao($rewardCodesSum);
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
                in_array((int)$betOrder->play_face, [LotteryBetType::X_PLAY_FACE, LotteryBetType::Y_PLAY_FACE])?
                    CountRewardResult::NO_REWARD_NO_LOST_STATUS:
                    CountRewardResult::LOST_STATUS
            );
            $countRewardResult->setWinMoney(
                in_array((int)$betOrder->play_face, [LotteryBetType::X_PLAY_FACE, LotteryBetType::Y_PLAY_FACE])?
                    0: bcmul(-1, $betOrder->bet_money)
            );
        } else {
            $countRewardResult->setRewardStatus($betCode == $rewardResult? CountRewardResult::REWARD_STATUS: CountRewardResult::LOST_STATUS);
            $countRewardResult->setWinMoney(
                $countRewardResult->rewardStatus == CountRewardResult::REWARD_STATUS?
                    bcmul($betOrder->odds, $betOrder->bet_money):
                    bcmul(-1, $betOrder->bet_money)
            );
        }

        switch ($countRewardResult->rewardStatus) {
            case CountRewardResult::NO_REWARD_NO_LOST_STATUS:
                $countRewardResult->setRewardCodes(['和']);
                $countRewardResult->setRewardMoney($betOrder->bet_money);
                break;
            case CountRewardResult::REWARD_STATUS:
                $countRewardResult->setRewardCodes($betOrder->codes);
                $countRewardResult->setRewardMoney(bcadd(bcmul($betOrder->odds, $betOrder->bet_money), $betOrder->bet_money));
                break;
            default:
                $countRewardResult->setRewardCodes([]);
                $countRewardResult->setRewardMoney(0);
        }

        return $countRewardResult;
    }
}
