<?php

namespace App\Listeners;

use App\Events\SettleOrder;
use App\Events\TransactionOrderEventContract;
use App\Models\BetOrder;
use App\Models\BetOrderForRebate;
use App\Models\BetType;
use App\Models\Issue;
use App\Models\Lottery;
use App\Models\LotteryBetType;
use App\Models\User;

class BetOrderCollector
{
    public function handle(TransactionOrderEventContract $event)
    {
        $betOrderForRebate = BetOrderForRebate::where(BetOrderForRebate::GAME_NO_FIELD, $event->order->order_no)->first()?: new BetOrderForRebate();

        $betOrderForRebate->username = User::find($event->order->user_id)->username;
        $betOrderForRebate->game_no = $event->order->order_no;
        $betOrderForRebate->inning_no = $event->order->issue;
        $betOrderForRebate->game_name = ($lottery = new Lottery())->where($lottery->getPrimaryKey(), $event->order->lottery_id)->value(Lottery::NAME_FIELD);
        $betOrderForRebate->bet_amount = $event->order->bet_money;
        $betOrderForRebate->play_type = $event->order->play_face == LotteryBetType::X_PLAY_FACE? 'x': 'y';
        $betOrderForRebate->bet_content = ['投注号码' => $event->order->codes, '中奖号码' => $event->order->reward_codes];
        $betOrderForRebate->game_result = Issue::where(Issue::ISSUE_FIELD,  $event->order->issue)
            ->where(Issue::LOTTERY_ID_FIELD,  $event->order->lottery_id)
            ->value(Issue::REWARD_CODES_FIELD);
        $betOrderForRebate->bet_time = $event->order->created_at;
        $betOrderForRebate->win = $event->order->win;

        switch ($event->order->status) {
            case BetOrder::SETTLEMENT_STATUS:
                $betOrderForRebate->valid_amount = $event->order->valid_bet_money;
                $betOrderForRebate->order_status = BetOrderForRebate::SETTLEMENT_STATUS;
                break;
            case BetOrder::SUCCESS_STATUS:
                $betOrderForRebate->valid_amount = 0;
                $betOrderForRebate->order_status = BetOrderForRebate::NO_SETTLEMENT_STATUS;
                break;
            case BetOrder::CANCLE_STATUS:
                $betOrderForRebate->valid_amount = 0;
                $betOrderForRebate->order_status = BetOrderForRebate::CANCEL_STATUS;
        }

        $betOrderForRebate->game_type = ($betType = new BetType)->where($betType->getPrimaryKey(), $event->order->bet_type_id)->value(BetType::NAME_FIELD);
        $betOrderForRebate->bet_status = $event->order->status == BetOrder::SETTLEMENT_STATUS?
            BetOrderForRebate::SETTLEMENT_STATUS: BetOrderForRebate::NO_SETTLEMENT_STATUS;

        $betOrderForRebate->save();
    }
}
