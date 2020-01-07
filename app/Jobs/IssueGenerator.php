<?php
namespace App\Jobs;

use App\Models\Lottery;
use App\Services\Issue\Tasks\GenerateIssues;
use App\Services\ServiceDispatcher;
use App\Utils\Log\Logger;
use Illuminate\Support\Carbon;

class IssueGenerator extends Job
{
    protected $lottery;

    public function __construct(Lottery $lottery)
    {
        $this->lottery = $lottery;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $setIssueResult = ServiceDispatcher::dispatch(
            ServiceDispatcher::TASK_SERVICE,
            new GenerateIssues($this->lottery)
        );
        if ($setIssueResult->hasErrors()) {
            Logger::emergency($setIssueResult->getError());
            echo "error:" . $setIssueResult->getError();
        }
    }

    public function retryUntil()
    {
        return Carbon::now()->addHour();
    }
}
