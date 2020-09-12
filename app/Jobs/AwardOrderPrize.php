<?php

namespace App\Jobs;

use App\Models\BetOrder;
use App\Models\Issue;
use App\Services\ServiceDispatcher;
use App\Utils\Log\Logger;
use Illuminate\Support\Carbon;

class AwardOrderPrize extends Job
{
    public $timeout = 3600 * 5;

    protected $issue;

    public function __construct(Issue $issue)
    {
        $this->issue = $issue;
    }

    public function handle()
    {
        $orders = BetOrder::where(BetOrder::STATUS_FIELD, BetOrder::SUCCESS_STATUS)
            ->where(BetOrder::ISSUE_FIELD, $this->issue->issue)
            ->where(BetOrder::LOTTERY_ID_FIELD, $this->issue->lottery_id)
            ->get();

        foreach ($orders as $order) {
            $awardResult = ServiceDispatcher::dispatch(
                ServiceDispatcher::TASK_SERVICE,
                new \App\Services\BetOrder\Tasks\AwardOrderPrize\AwardOrderPrize($this->issue, $order)
            );

            if ($awardResult->hasErrors()) {
                echo "error:" . $awardResult->getError() .PHP_EOL;
                Logger::error($awardResult->getError());
                continue;
            }
        }
    }

    public function retryUntil()
    {
        return Carbon::now()->addHours(6);
    }
}
