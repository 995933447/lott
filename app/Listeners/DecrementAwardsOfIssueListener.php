<?php
namespace App\Listeners;

use App\Events\CancelOrder;
use App\Models\Issue;

class DecrementAwardsOfIssueListener
{
    public function handle(CancelOrder $event)
    {
        if (bccomp($event->originalOrderRewardedMoney, 0) > 0) {
            $issue = Issue::where(Issue::ISSUE_FIELD, $event->order->issue)->where(Issue::LOTTERY_ID_FIELD, $event->order->lottery_id)->first();
            if ($issue) {
                $issue->total_reward_money = bcsub($issue->total_reward_money, $event->originalOrderRewardedMoney);
                $issue->total_reward_num = 0;
                $issue->save();
            }
        }
    }
}
