<?php
namespace App\Observers;

use App\Events\IllegallyRiskHappen;
use App\Models\BetOrder;
use App\Services\BetOrder\Tasks\OrderEncryptor\CheckOrderLegality;
use App\Services\BetOrder\Tasks\OrderEncryptor\EncryptOrder;
use App\Services\ServiceDispatcher;
use Illuminate\Support\Facades\Event;

class BetOrderObserver
{
    public function saving(BetOrder $order)
    {
        $encryptResult = ServiceDispatcher::dispatch(ServiceDispatcher::TASK_SERVICE, new EncryptOrder($order, EncryptOrder::VERSION_1));
        if ($encryptResult->hasErrors()) {
            throw new \Exception($encryptResult->getError());
        }
    }

    public function retrieved(BetOrder $order)
    {
        $legalityResult = ServiceDispatcher::dispatch(ServiceDispatcher::TASK_SERVICE, new CheckOrderLegality($order));
        if ($legalityResult->hasErrors()) {
            Event::dispatch(new IllegallyRiskHappen($legalityResult->getError()));
        }
    }
}
