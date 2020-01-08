<?php
namespace App\Listeners;

use App\Events\IllegallyRiskHappen;
use App\Services\Rpc\Tasks\Clients\HttpRpc;
use App\Services\ServiceDispatcher;
use App\Utils\Log\Logger;
use Illuminate\Http\Request;

class ReportRiskMessage
{
    public function handle(IllegallyRiskHappen $event)
    {
        $request = new Request();
        $request->setMethod('post');
        $request->merge([
                'risk_type' => 8,
                'message' => $event->message,
            ]);

        $rpcResult = ServiceDispatcher::dispatch(
            ServiceDispatcher::TASK_SERVICE,
            new HttpRpc($request, 'http-rpc/backend/push-risk-message', [])
        );

        if ($rpcResult->hasErrors()) {
            echo $rpcResult->getError();die;
            Logger::emergency($rpcResult->getError());
        }
    }
}
