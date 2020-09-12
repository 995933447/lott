<?php
namespace App\Events;

use App\Models\User;

class IllegallyRiskHappen extends Event
{
    public $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }
}
