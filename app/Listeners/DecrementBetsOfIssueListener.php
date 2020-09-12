<?php

namespace App\Listeners;

use App\Events\CancelOrder;
use App\Models\Issue;

class DecrementBetsOfIssueListener
{
    public function handle(CancelOrder $event)
    {
        $issue = Issue::where(Issue::ISSUE_FIELD, $event->order->issue)->where(Issue::LOTTERY_ID_FIELD, $event->order->lottery_id)->first();
        $issue->total_bet_money = bcsub($issue->total_bet_money, $event->order->bet_money);
        $issue->total_bet_num--;
        $issue->save();
    }
}
