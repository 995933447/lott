<?php
namespace App\Listeners;

use App\Events\CancelOrder;
use App\Events\ResettleOrder;
use App\Events\TransactionOrderEventContract;
use App\Models\BetOrder;
use App\Models\OrderTransactionLog;
use App\Models\UserBalance;

class OrderTransactionLogger
{
    const BET_ORDER_REMARK = '彩票下注';

    const REWARD_PRIZE_REMARK = '中奖派彩';

    const REWARD_NO_WIN_NO_LOST_REMARK = '开彩为和返回本金';

    const CANCEL_ORDER_WITHOUT_REWARD_REMARK = '取消订单返还投注本金';

    const CANCEL_ORDER_ABOUT_REWARD_REMARK = '取消订单返回本金以及扣除派彩';

    const RECOUNT_ORDER_TO_SUB_BALANCE_REMARK = '重算派彩结果导致合理扣除派彩金额';

    const RECOUNT_ORDER_TO_ADD_BALANCE_REMARK = '重算派彩结果导致合理添加派彩金额';

    public function handle(TransactionOrderEventContract $event)
    {
        if ($event instanceof ResettleOrder) {
            $this->logResettleOrder($event);
        } else {
            $this->logNormalOrder($event);
        }
    }

    protected function logNormalOrder(TransactionOrderEventContract $event)
    {
        if ($event->order->status == BetOrder::SETTLEMENT_STATUS && $event->order->reward_money <= 0) {
            return;
        }

        $userBalance = UserBalance::lockForUpdate()->where(UserBalance::USER_ID_FIELD, $event->order->user_id)->value(UserBalance::BALANCE_FIELD);

        $orderTransactionLogger = new OrderTransactionLog();

        $orderTransactionLogger->user_id = $event->order->user_id;
        $orderTransactionLogger->order_id = $event->order->id;
        $orderTransactionLogger->after_transaction_balance = $userBalance;

        if ($event->order->status == BetOrder::SETTLEMENT_STATUS && $event->order->reward_money > 0) {

            $orderTransactionLogger->type = OrderTransactionLog::REWARD_PRIZE_TYPE;
            $orderTransactionLogger->before_transaction_balance = bcsub($userBalance, $event->order->reward_money);
            $orderTransactionLogger->transaction_money = bcsub($orderTransactionLogger->after_transaction_balance, $orderTransactionLogger->before_transaction_balance);
            if ($event->order->reward_status == BetOrder::NO_REWARD_NO_LOST_STATUS) {
                $orderTransactionLogger->remark = static::REWARD_NO_WIN_NO_LOST_REMARK;
            } else {
                $orderTransactionLogger->remark = static::REWARD_PRIZE_REMARK;
            }

        } else if ($event->order->status == BetOrder::SUCCESS_STATUS) {

            $orderTransactionLogger->type = OrderTransactionLog::BET_ORDER_TYPE;
            $orderTransactionLogger->transaction_money = $event->order->bet_money;
            $orderTransactionLogger->before_transaction_balance = bcadd($userBalance, $event->order->bet_money);
            $orderTransactionLogger->remark = static::BET_ORDER_REMARK;

        } else if ($event->order->status = BetOrder::CANCLE_STATUS) {
            if (!$event instanceof CancelOrder) {
                throw new \InvalidArgumentException('Argument must instance of ' . CancelOrder::class);
            }

            if (bccomp($event->originalOrderRewardedMoney, 0) > 0) {
                $orderTransactionLogger->remark = static::CANCEL_ORDER_ABOUT_REWARD_REMARK;
                $orderTransactionLogger->type = OrderTransactionLog::CANCEL_ORDER_ABOUT_REWARD_TYPE;
                $orderTransactionLogger->transaction_money = bcsub($event->originalOrderRewardedMoney, $event->order->bet_money);
                $orderTransactionLogger->before_transaction_balance = bcadd($orderTransactionLogger->after_transaction_balance, $orderTransactionLogger->transaction_money);
            } else {
                $orderTransactionLogger->remark = static::CANCEL_ORDER_WITHOUT_REWARD_REMARK;
                $orderTransactionLogger->type = OrderTransactionLog::CANCEL_ORDER_WITHOUT_REWARD_TYPE;
                $orderTransactionLogger->transaction_money = $event->order->bet_money;
                $orderTransactionLogger->before_transaction_balance = bcsub($orderTransactionLogger->after_transaction_balance, $orderTransactionLogger->transaction_money);
            }
        }

        $orderTransactionLogger->save();
    }

    protected function logResettleOrder(ResettleOrder $event)
    {
        if (bccomp($event->transactionMoney, 0) === 0) {
            return;
        }

        $userBalance = UserBalance::lockForUpdate()->where(UserBalance::USER_ID_FIELD, $event->order->user_id)->value(UserBalance::BALANCE_FIELD);
        $orderTransactionLogger = new OrderTransactionLog();

        $orderTransactionLogger->user_id = $event->order->user_id;
        $orderTransactionLogger->order_id = $event->order->id;
        $orderTransactionLogger->after_transaction_balance = $userBalance;
        if ($event->isAddSide) {
            $orderTransactionLogger->remark = static::RECOUNT_ORDER_TO_ADD_BALANCE_REMARK;
            $orderTransactionLogger->type = OrderTransactionLog::RECOUNT_ORDER_TO_ADD_BALANCE_TYPE;
            $orderTransactionLogger->before_transaction_balance = bcsub($orderTransactionLogger->after_transaction_balance, $event->transactionMoney);
        } else {
            $orderTransactionLogger->remark = static::RECOUNT_ORDER_TO_SUB_BALANCE_REMARK;
            $orderTransactionLogger->type = OrderTransactionLog::RECOUNT_ORDER_TO_SUB_BALANCE_TYPE;
            $orderTransactionLogger->before_transaction_balance = bcadd($orderTransactionLogger->after_transaction_balance, $event->transactionMoney);
        }
        $orderTransactionLogger->transaction_money = $event->transactionMoney;
        $orderTransactionLogger->save();
    }
}
