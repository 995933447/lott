<?php
namespace App\Jobs;

use App\Models\Lottery;
use App\Services\Issue\Tasks\DrawIssues;
use App\Services\ServiceDispatcher;
use App\Utils\Log\Logger;

class IssueDrawer extends Job
{
    public $tries = 2;
    public $timeout = 60;

    protected $lottery;
    protected $drawDatetime;

    public function __construct(Lottery $lottery, ?string $drawDatetime = null)
    {
        $this->lottery = $lottery;
        $this->drawDatetime = $drawDatetime;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $setIssueResult = ServiceDispatcher::dispatch(ServiceDispatcher::TASK_SERVICE, new DrawIssues($this->lottery, $this->drawDatetime));

        if ($setIssueResult->hasErrors()) {
            Logger::emergency($setIssueResult->getError());
            echo "error:" . $setIssueResult->getError();
        }
    }
}
