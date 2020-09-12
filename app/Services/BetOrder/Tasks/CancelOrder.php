<?php
namespace App\Services\BetOrder\Tasks;

use App\Models\BetOrder;
use App\Models\Issue;
use App\Models\UserBalance;
use App\Providers\EventServiceProvider;
use App\Services\ServeResult;
use App\Services\TaskServiceContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;

class CancelOrder implements TaskServiceContract
{
    private $betOrder;
    private $isForce;

    public function __construct(?BetOrder $betOrder, bool $isForce = false)
    {
        $this->betOrder = $betOrder;
        $this->isForce = $isForce;
    }

    public function run(): ServeResult
    {
        if (is_null($this->betOrder))
            return ServeResult::make();

        Event::dispatch(EventServiceProvider::BEGIN_CANCEL_ORDER_TRANSACTION_EVENT);
        try {
            if (!$this->isForce) {
                $issue = Issue::where(Issue::ISSUE_FIELD, $this->betOrder->issue)
                    ->where(Issue::LOTTERY_ID_FIELD, $this->betOrder->lottery_id)
                    ->first();
                if ($issue->stop_bet_at <= time()) {
                    Event::dispatch(EventServiceProvider::ROLLBACK_CANCEL_ORDER_TRANSACTION_EVENT);
                    return ServeResult::make(['游戏已封盘, 无法撤销订单']);
                }
            }

            $this->betOrder = $this->betOrder->lockForUpdate()->where($this->betOrder->getPrimaryKey(), $this->betOrder->id)->first();
            $originalBetOrderRewardedMoney = $this->betOrder->reward_money;

            if ($this->betOrder->status == BetOrder::CANCLE_STATUS) {
                return ServeResult::make();
            } else if ($this->betOrder->status == BetOrder::SETTLEMENT_STATUS) {
                $userBalance = UserBalance::lockForUpdate()->where(UserBalance::USER_ID_FIELD, $this->betOrder->user_id)->first();
                $userBalance->balance = bcadd(bcsub($userBalance->balance, $this->betOrder->reward_money), $this->betOrder->bet_money);
                $userBalance->save();

                $this->betOrder->reward_money = 0;
                $this->betOrder->win = 0;
                $this->betOrder->reward_codes = [];
                $this->betOrder->reward_status = null;
                $this->betOrder->status = BetOrder::CANCLE_STATUS;
                $this->betOrder->save();
            } else {
                $this->betOrder->status = BetOrder::CANCLE_STATUS;
                $this->betOrder->save();

                $userBalance = UserBalance::lockForUpdate()->where(UserBalance::USER_ID_FIELD, $this->betOrder->user_id)->first();
                $userBalance->balance = bcadd($userBalance->balance, $this->betOrder->bet_money);
                $userBalance->save();
            }

            Event::dispatch(new \App\Events\CancelOrder($this->betOrder, $originalBetOrderRewardedMoney));

            Event::dispatch(EventServiceProvider::COMMIT_CANCEL_ORDER_TRANSACTION_EVENT);
        }catch (\Exception $e) {
            Event::dispatch(EventServiceProvider::ROLLBACK_CANCEL_ORDER_TRANSACTION_EVENT);

            throw $e;
        }
        return ServeResult::make();
    }
}
