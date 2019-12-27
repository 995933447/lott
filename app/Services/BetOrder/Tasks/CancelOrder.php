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
            } else {
                $this->betOrder = BetOrder::lockForUpdate()->where($this->betOrder->getPrimaryKey(), $this->betOrder->id)->first();
                if ($this->betOrder->status == BetOrder::SETTLEMENT_STATUS) {
                    Event::dispatch(EventServiceProvider::ROLLBACK_CANCEL_ORDER_TRANSACTION_EVENT);
                    return ServeResult::make(['该笔订单已结算,无法操作取消订单']);
                }
            }

            $this->betOrder->status = BetOrder::CANCLE_STATUS;
            $this->betOrder->save();

            $userBalance = UserBalance::lockForUpdate()->where(UserBalance::USER_ID_FIELD, $this->betOrder->user_id)->first();
            $userBalance->balance = bcadd($userBalance->balance, $this->betOrder->bet_money);
            $userBalance->save();

            Event::dispatch(new \App\Events\CancelOrder($this->betOrder));

            Event::dispatch(EventServiceProvider::COMMIT_CANCEL_ORDER_TRANSACTION_EVENT);
        }catch (\Exception $e) {
            Event::dispatch(EventServiceProvider::ROLLBACK_CANCEL_ORDER_TRANSACTION_EVENT);

            throw $e;
        }
        return ServeResult::make();
    }
}
