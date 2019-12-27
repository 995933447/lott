<?php

namespace App\Listeners;

use App\Events\SettleOrder;
use App\Models\BetOrder;
use App\Models\BetOrderForRebate;
use App\Models\BetType;
use App\Models\Issue;
use App\Models\Lottery;
use App\Models\LotteryBetType;
use App\Models\User;

class BetOrderCollector
{
    public function handle(SettleOrder $event)
    {
        $betOrderForRebate = BetOrderForRebate::where(BetOrderForRebate::GAME_NO_FIELD, $event->order->order_no)->first()?: new BetOrderForRebate();

        $betOrderForRebate->username = User::find($event->order->user_id)->username;
        $betOrderForRebate->game_no = $event->order->order_no;
        $betOrderForRebate->game_name = ($lottery = new Lottery())->where($lottery->getPrimaryKey(), $event->order->lottery_id)->value(Lottery::NAME_FIELD);
        $betOrderForRebate->bet_amount = $event->order->bet_money;
        $betOrderForRebate->valid_amount = $event->order->valid_bet_money;
        $betOrderForRebate->play_type = $event->order->play_face == LotteryBetType::X_PLAY_FACE? 'x': 'y';
        $betOrderForRebate->bet_content = $event->order->codes;
        $betOrderForRebate->game_result = Issue::where(Issue::ISSUE_FIELD,  $event->order->issue)
            ->where(Issue::LOTTERY_ID_FIELD,  $event->order->lottery_id)
            ->value(Issue::REWARD_CODES_FIELD);
        $betOrderForRebate->bet_time = $event->order->created_at;
        $betOrderForRebate->win = $event->order->win;
        $betOrderForRebate->order_status = $event->order->status == BetOrder::SETTLEMENT_STATUS?
            BetOrderForRebate::SETTLEMENT_STATUS: BetOrderForRebate::NO_SETTLEMENT_STATUS;
        $betOrderForRebate->game_type = ($betType = new BetType)->where($betType->getPrimaryKey(), $event->order->bet_type_id)->value(BetType::NAME_FIELD);
        $betOrderForRebate->bet_status = $event->order->status == BetOrder::SETTLEMENT_STATUS?
            BetOrderForRebate::SETTLEMENT_STATUS: BetOrderForRebate::NO_SETTLEMENT_STATUS;

        $betOrderForRebate->save();
    }
}
