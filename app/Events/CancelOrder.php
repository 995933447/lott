<?php
namespace App\Events;

use App\Models\BetOrder;

class CancelOrder extends TransactionOrderEventContract
{
    public $originalOrderRewardedMoney;

    public function __construct(BetOrder $betOrder, $originalOrderRewardedMoney = 0)
    {
        parent::__construct($betOrder);
        $this->originalOrderRewardedMoney = $originalOrderRewardedMoney;
    }
}
