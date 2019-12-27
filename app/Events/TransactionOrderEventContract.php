<?php

namespace App\Events;

use App\Models\BetOrder;

abstract class TransactionOrderEventContract extends Event
{
    private $order;

    private $orderStatus;

    public function __construct(BetOrder $betOrder)
    {
        $this->order = $betOrder;
        $this->orderStatus = $this->order->status;
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            $this->initProperty();
            $value = $this->$name;
        }
        return $value;
    }

    private function initProperty()
    {
        $this->order->status = $this->orderStatus;
    }
}
