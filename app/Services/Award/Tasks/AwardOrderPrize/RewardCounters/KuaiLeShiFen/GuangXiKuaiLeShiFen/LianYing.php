<?php
namespace App\Services\Award\Tasks\AwardOrderPrize\RewardCounters\KuaiLeShiFen\GuangXiKuaiLeShiFen;

use App\Models\BetOrder;
use App\Models\Issue;
use App\Services\Award\Tasks\AwardOrderPrize\RewardCounters\CountRewardResult;
use App\Services\Award\Tasks\AwardOrderPrize\RewardCounters\RewardCounterContract;
use App\Utils\Games\CodeToTransfers\KuaiLeShiFen\GuangXiKuaiLeShiFen;

class LianYing implements RewardCounterContract
{
    public static function handle(Issue $issue, BetOrder $betOrder): CountRewardResult
    {
        $betItemCodes = $betOrder->codes;
        $continuingWin = true;
        foreach ($betItemCodes as $betItemCode) {
            if (!$continuingWin = static::getRewardStatus($betOrder, $issue->reward_codes))
                break;
        }

        $countRewardResult = new CountRewardResult();
        $countRewardResult->setRewardStatus($continuingWin? CountRewardResult::REWARD_STATUS: CountRewardResult::LOST_STATUS);
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

    private static function getRewardStatus(string $betItemCode, array $rewardCodes): bool
    {
        list($betItem, $betCode, $betSubCode) = explode(':', $betItemCode, 3);
        if ($betItem === '合值') {
            $resultCode = array_sum($rewardCodes);
        } else {
            $resultCode = $rewardCodes[array_search($betItem, ['平码一', '平码二', '平码三', '平码四', '特码'])];
        }
        $rewardResults = [];
        switch ($betItem) {
            case '合值':
                if ($resultCode === GuangXiKuaiLeShiFen::getInvalidSummationCode()) {
                    break;
                }
                $rewardResults[] = GuangXiKuaiLeShiFen::toHeZhiDaXiao($resultCode);
                $rewardResults[] = GuangXiKuaiLeShiFen::toDanShuang($resultCode);
                break;
            default:
                if ($resultCode === GuangXiKuaiLeShiFen::getInvalidCode()) {
                    break;
                }
                $rewardResults[] = GuangXiKuaiLeShiFen::toDaXiao($resultCode);
                $rewardResults[] = GuangXiKuaiLeShiFen::toDanShuang($resultCode);
        }
        return in_array($betSubCode, $rewardResults);
    }
}
