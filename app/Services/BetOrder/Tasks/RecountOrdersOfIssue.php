<?php
namespace App\Services\BetOrder\Tasks;

use App\Models\BetOrder;
use App\Models\Issue;
use App\Services\BetOrder\Tasks\AwardOrderPrize\AwardOrderPrize;
use App\Services\ServeResult;
use App\Services\ServiceDispatcher;
use App\Services\TaskServiceContract;

class RecountOrdersOfIssue implements TaskServiceContract
{
    private $issue;

    public function __construct(Issue $issue)
    {
        $this->issue = $issue;
    }

    public function run(): ServeResult
    {
        $orders = BetOrder::where(BetOrder::ISSUE_FIELD, $this->issue->issue)
            ->where(BetOrder::LOTTERY_ID_FIELD, $this->issue->lottery_id)
            ->where(BetOrder::STATUS_FIELD, '<>', BetOrder::CANCLE_STATUS)
            ->get();

        foreach ($orders as $order) {
            $result = ServiceDispatcher::dispatch(ServiceDispatcher::TASK_SERVICE, new AwardOrderPrize($this->issue, $order));
            if ($result->hasErrors()) {
                return $result;
            }
         }

        return ServeResult::make();
    }
}
