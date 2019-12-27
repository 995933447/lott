<?php

namespace App\Events;

use App\Models\Issue;

class DrawIssue extends Event
{
    public $issue;

    public function __construct(Issue $issue)
    {
        $this->issue = $issue;
    }
}
