<?php
namespace App\Events;

use App\Models\Issue;

class CancelIssue extends Event
{
    public $order;

    public function __construct(Issue $issue)
    {
        $this->issue = $issue;
    }
}
