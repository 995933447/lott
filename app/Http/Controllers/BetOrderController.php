<?php
namespace App\Http\Controllers;

use App\Models\BetOrder;
use App\Services\BetOrder\Tasks\CancelOrder;
use App\Services\BetOrder\Tasks\RecountOrdersOfIssue;
use App\Services\BetOrder\Validators\CommitOrder\CommitOrder;
use App\Services\ServiceDispatcher;
use App\Utils\Formatters\End;
use App\Utils\Formatters\Money;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Issue;

class BetOrderController
{
    public function commit(Request $request)
    {
        if (($validateResult = ServiceDispatcher::dispatch(ServiceDispatcher::VALIDATOR_SERVICE, CommitOrder::class, $request))->hasErrors()) {
            return End::toFailJson($validateResult->getErrors()->toArray(), $validateResult->getError());
        }

        $commitOrderResult = ServiceDispatcher::dispatch(
            ServiceDispatcher::TASK_SERVICE,
            new \App\Services\BetOrder\Tasks\CommitOrder(
                Auth::id(),
                $request->input('lottery_code'),
                $request->input('face'),
                $request->input('bet_type_code'),
                $request->input('issue'),
                Money::normalize($request->input('money')),
                $request->input('codes'),
                (float) $request->input('odds')
            )
        );
        if ($commitOrderResult->hasErrors()) {
            return End::toFailJson([], $commitOrderResult->getError(), End::INTERNAL_ERROR);
        }

        return End::toSuccessJson($commitOrderResult->getData(), "投注成功");
    }

    public function cancel($orderId)
    {
        $result = ServiceDispatcher::dispatch(
            ServiceDispatcher::TASK_SERVICE,
            CancelOrder::class,
            ($betOrder = new BetOrder)->where(BetOrder::USER_ID_FIELD, Auth::id())
                ->where($betOrder->getPrimaryKey(), $orderId)
                ->where(BetOrder::STATUS_FIELD, BetOrder::SUCCESS_STATUS)
                ->first()
         );
        if ($result->hasErrors()) {
            return End::toFailJson($result->getErrors()->toArray(), $result->getError(), End::INTERNAL_ERROR);
        }
        return End::toSuccessJson();
    }

    public function recountOrdersOfIssue($issueId)
    {
        $result = ServiceDispatcher::dispatch(ServiceDispatcher::TASK_SERVICE, new RecountOrdersOfIssue(Issue::find($issueId)));
        if ($result->hasErrors()) {
            return End::toFailJson($result->getErrors()->toArray(), $result->getError(), End::INTERNAL_ERROR);
        }
        return End::toSuccessJson();
    }
}
