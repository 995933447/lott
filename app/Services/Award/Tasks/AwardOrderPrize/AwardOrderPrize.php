<?php
namespace App\Services\Award\Tasks\AwardOrderPrize;

use App\Events\SettleOrder;
use App\Models\BetOrder;
use App\Models\BetType;
use App\Models\Issue;
use App\Models\Lottery;
use App\Models\UserBalance;
use App\Providers\EventServiceProvider;
use App\Services\Award\Tasks\AwardOrderPrize\RewardCounters\CountRewardResult;
use App\Services\Award\Tasks\AwardOrderPrize\RewardCounters\KuaiLeShiFen\GuangXiKuaiLeShiFen\Buzhong;
use App\Services\Award\Tasks\AwardOrderPrize\RewardCounters\KuaiLeShiFen\GuangXiKuaiLeShiFen\Fushi;
use App\Services\Award\Tasks\AwardOrderPrize\RewardCounters\KuaiLeShiFen\GuangXiKuaiLeShiFen\Hezhi;
use App\Services\Award\Tasks\AwardOrderPrize\RewardCounters\KuaiLeShiFen\GuangXiKuaiLeShiFen\LianYing;
use App\Services\Award\Tasks\AwardOrderPrize\RewardCounters\KuaiLeShiFen\GuangXiKuaiLeShiFen\Normal;
use App\Services\Award\Tasks\AwardOrderPrize\RewardCounters\KuaiLeShiFen\GuangXiKuaiLeShiFen\Xingyun;
use App\Services\ServeResult;
use App\Services\TaskServiceContract;
use Illuminate\Support\Facades\Event;

class AwardOrderPrize implements TaskServiceContract
{
    private $issue;
    private $betOrder;

    private $rewardCounters = [
        Lottery::GUANGXIKUAILE10_TYPE_CODE . '@' . BetType::HEZHI_TYPE_CODE => Hezhi::class,
        Lottery::GUANGXIKUAILE10_TYPE_CODE . '@' . BetType::NORMAL_TYPE_CODE => Normal::class,
        Lottery::GUANGXIKUAILE10_TYPE_CODE . '@' . BetType::XINGYUN_TYPE_CODE => Xingyun::class,
        Lottery::GUANGXIKUAILE10_TYPE_CODE . '@' . BetType::BUZHONG_TYPE_CODE => Buzhong::class,
        Lottery::GUANGXIKUAILE10_TYPE_CODE . '@' . BetType::FUSHI_TYPE_CODE => Fushi::class,
        Lottery::GUANGXIKUAILE10_TYPE_CODE . '@' . BetType::LIANYING_TYPE_CODE => LianYing::class,

        Lottery::GUANGXIKUAI3_TYPE_CODE . '@' . BetType::HEZHI_TYPE_CODE => \App\Services\Award\Tasks\AwardOrderPrize\RewardCounters\KuaiSan\GuangXiKuaiSan\Hezhi::class,
        Lottery::GUANGXIKUAI3_TYPE_CODE . '@' . BetType::NORMAL_TYPE_CODE => \App\Services\Award\Tasks\AwardOrderPrize\RewardCounters\KuaiSan\GuangXiKuaiSan\Normal::class
    ];

    public function __construct(Issue $issue, BetOrder $betOrder)
    {
        $this->issue = $issue;
        $this->betOrder = $betOrder;
    }

    public function run(): ServeResult
    {
        switch ($this->issue->status) {
            case Issue::OPENING_STATUS:
                return ServeResult::make(['奖期正在开彩中']);
            case Issue::NO_OPEN_STATUS:
                return ServeResult::make(['奖期未开彩']);
        }

        if ($this->issue->lottery_id != $this->betOrder->lottery_id || $this->issue->issue != $this->betOrder->issue) {
            throw new \InvalidArgumentException(
                "Issue's lottery id must same with BetOrder's lottery id, Issue's issue must same with BetOrder's issue" .
                "Issue's lottery id is {$this->issue->lottery_id} and issue is {$this->issue->issue}" .
                "BetOrder's lottery id is {$this->betOrder->lottery_id} and issue is {$this->betOrder->issue}"
            );
        }

        Event::dispatch(EventServiceProvider::BEGIN_AWARD_ORDER_TRANSACTION_EVENT);
        try {
            $betOrderStatus = $this->betOrder->lockForUpdate()->where($this->betOrder->getPrimaryKey(), $this->betOrder->id)->value(BetOrder::STATUS_FIELD);
            if ($betOrderStatus != BetOrder::SUCCESS_STATUS) {
                throw new AwardOrderPrizeException("订单状态不为下单成功且未结算状态");
            }
            $this->betOrder->status = BetOrder::BILLING_STATUE;
            $this->betOrder->save();

            $countResult = $this->countReward();

            $this->betOrder->win = $countResult->winMoney;
            $this->betOrder->reward_codes = $countResult->rewardCodes;
            $this->betOrder->reward_money = $countResult->rewardMoney;
            switch ($countResult->rewardStatus) {
                case BetOrder::REWARD_STATUS:
                    $this->betOrder->reward_status = BetOrder::REWARD_STATUS;
                    break;
                case BetOrder::LOST_STATUS:
                    $this->betOrder->reward_status = BetOrder::LOST_STATUS;
                    break;
                default:
                    $this->betOrder->reward_status = BetOrder::NO_REWARD_NO_LOST_STATUS;
            }
            $this->betOrder->valid_bet_money = $this->betOrder->bet_money;
            $this->betOrder->status = BetOrder::SETTLEMENT_STATUS;
            $this->betOrder->save();

            $this->issue->total_reward_money = bcadd($this->issue->total_reward_money, $countResult->rewardMoney);
            if ($countResult->rewardStatus === CountRewardResult::REWARD_STATUS) {
                $this->issue->total_reward_num++;
            }

            if ($countResult->winMoney > 0) {
                $userBalance = UserBalance::lockForUpdate()->where(UserBalance::USER_ID_FIELD, $this->betOrder->user_id)->first();
                $userBalance->balance = bcadd($userBalance->balance, $countResult->winMoney);
                $userBalance->save();
            }

            Event::dispatch(new SettleOrder($this->betOrder));

            Event::dispatch(EventServiceProvider::COMMIT_AWARD_ORDER_TRANSACTION_EVENT);
        } catch (\Exception $e) {
            Event::dispatch(EventServiceProvider::ROLLBACK_AWARD_ORDER_TRANSACTION_EVENT);

            if ($e instanceof AwardOrderPrizeException) {
                return ServeResult::make([$e->getMessage()]);
            }
            throw $e;
        }

        return ServeResult::make();
    }

    private function countReward(): CountRewardResult
    {
        return call_user_func_array(
           [$this->rewardCounters[$this->betOrder->lottery_code . '@' . $this->betOrder->bet_type_code], 'handle'],
           [$this->issue, $this->betOrder]
        );
    }
}
