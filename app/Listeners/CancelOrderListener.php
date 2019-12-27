<?php
namespace App\Listeners;

use App\Events\CancelIssue;
use App\Models\BetOrder;
use App\Services\BetOrder\Tasks\CancelOrder;
use App\Services\ServiceDispatcher;
use App\Utils\Log\Logger;

class CancelOrderListener
{
    public function handle(CancelIssue $event)
    {
        $orders = BetOrder::where(BetOrder::ISSUE_FIELD, $event->issue->issue)->get();
        foreach ($orders as $order) {
            $cancelOrderResult = ServiceDispatcher::dispatch(ServiceDispatcher::TASK_SERVICE, new CancelOrder($order, true));
            if ($cancelOrderResult->hasErrors()) {
                Logger::emergency($cancelOrderResult->getError());
                echo $cancelOrderResult->getError();
            }
        }
    }
}
