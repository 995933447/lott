<?php
namespace App\Services\Issue\Tasks;

use App\Models\Issue;
use App\Services\ServeResult;
use App\Services\TaskServiceContract;

class ResetIssue implements TaskServiceContract
{
    private $issue;

    private $issueNumber;

    private $rewardCodes;

    private $status;

    public function __construct(Issue $issue, array $rewardCodes = null, string $issueNumber = null, int $status = null)
    {
        $this->issue = $issue;
        $this->issueNumber = $issueNumber?: null;
        $this->rewardCodes = $rewardCodes?: null;
        $this->status = $status;
    }

    public function run(): ServeResult
    {
        if (is_null($this->issueNumber) && is_null($this->rewardCodes) && is_null($this->status)) {
            return ServeResult::make(['请输入更新项']);
        }

        if (is_null($this->issue)) {
            return ServeResult::make(['奖期不存在']);
        }

        if (!is_null($this->issueNumber)) {
            $this->issue->issue = $this->issueNumber;
        }
        if (!is_null($this->rewardCodes)) {
            $this->issue->reward_codes = $this->rewardCodes;
        }
        if (!is_null($this->status)) {
            $this->issue->status = $this->status;
        }
        $this->issue->save();

        return ServeResult::make();
    }
}
