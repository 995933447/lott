<?php
namespace App\Events;

use App\Models\BetOrder;

class ResettleOrder extends TransactionOrderEventContract
{
    public $transactionMoney;

    public $isAddSide;

    public function __construct(BetOrder $betOrder, $transactionMoney, bool $isAddSide)
    {
        parent::__construct($betOrder);
        $this->transactionMoney = $transactionMoney;
        $this->isAddSide = $isAddSide;
    }
}
