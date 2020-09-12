<?php
namespace App\Services\Issue\Tasks;

use App\Models\Issue;
use App\Services\ServeResult;
use App\Services\TaskServiceContract;
use App\Utils\Formatters\ZeroPad;

class ResetIssue implements TaskServiceContract
{
    private $issue;

    private $issueNumber;

    private $rewardCodes;

    private $status;

    public function __construct(Issue $issue, ?array $rewardCodes = null, ?string $issueNumber = null, ?int $status = null)
    {
        $this->issue = $issue;
        $this->issueNumber = $issueNumber;
        $this->rewardCodes = $rewardCodes;
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

        if (!is_null($this->status)) {
            $this->issue->status = $this->status;
        }

        if (!is_null($this->rewardCodes)) {
            $this->issue->reward_codes = $this->rewardCodes;

            if (!empty($this->rewardCodes)) {
                $this->issue->status = Issue::OPENED_STATUS;
            }
        }

        $this->issue->save();

        return ServeResult::make();
    }
}
