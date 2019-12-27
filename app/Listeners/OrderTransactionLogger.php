<?php
namespace App\Listeners;

use App\Events\TransactionOrderEventContract;
use App\Models\BetOrder;
use App\Models\OrderTransactionLog;
use App\Models\UserBalance;

class OrderTransactionLogger
{
    const BET_ORDER_REMARK = '彩票下注';

    const REWARD_PRIZE_REMARK = '中奖派彩';

    const CANCEL_ORDER_REMARK = '取消订单';

    public function handle(TransactionOrderEventContract $event)
    {
        if ($event->order->status = BetOrder::SETTLEMENT_STATUS && $event->order->win < 0) {
            return;
        }

        $userBalance = UserBalance::where(UserBalance::USER_ID_FIELD, $event->order->user_id)->value(UserBalance::BALANCE_FIELD);
        $orderTransactionLogger = new OrderTransactionLog();
        $orderTransactionLogger->user_id = $event->order->user_id;
        $orderTransactionLogger->order_id = $event->order->id;
        $orderTransactionLogger->after_transaction_balance = $userBalance;

        if ($event->order->status == BetOrder::SETTLEMENT_STATUS && $event->order->win > 0) {

            $orderTransactionLogger->type = OrderTransactionLog::REWARD_PRIZE_TYPE;
            $orderTransactionLogger->transaction_money = $event->order->win;
            $orderTransactionLogger->before_transaction_balance = bcsub($userBalance, $event->order->win);
            $orderTransactionLogger->remark = static::REWARD_PRIZE_REMARK;

        } else if ($event->order->status == BetOrder::SUCCESS_STATUS) {

            $orderTransactionLogger->type = OrderTransactionLog::BET_ORDER_TYPE;
            $orderTransactionLogger->transaction_money = $event->order->bet_money;
            $orderTransactionLogger->before_transaction_balance = bcadd($userBalance, $event->order->bet_money);
            $orderTransactionLogger->remark = static::BET_ORDER_REMARK;

        } else if ($event->order->status = BetOrder::CANCLE_STATUS) {

            $orderTransactionLogger->type = OrderTransactionLog::CANCEL_ORDER_TYPE;
            $orderTransactionLogger->transaction_money = $event->order->bet_money;
            $orderTransactionLogger->before_transaction_balance = bcsub($userBalance, $event->order->bet_money);
            $orderTransactionLogger->remark = static::CANCEL_ORDER_REMARK;

        }

        $orderTransactionLogger->save();
    }
}
