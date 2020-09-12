<?php
namespace App\Listeners;

use App\Events\DrawIssue;
use App\Events\ExampleEvent;
use App\Jobs\AwardOrderPrize;
use App\Jobs\JobQueuesEnum;
use Illuminate\Support\Facades\Queue;

class PrizeJudgment
{
    /**
     * Handle the event.
     *
     * @param  ExampleEvent  $event
     * @return void
     */
    public function handle(DrawIssue $event)
    {
        Queue::push(new AwardOrderPrize($event->issue), '', JobQueuesEnum::AWARD_ORDER_JUDGMENT);
    }
}
