<?php
namespace App\Services\Issue\Tasks;

use App\Models\Lottery;
use App\Services\Issue\Tasks\IssueManagers\GuangXiKuaiLeShiFen;
use App\Services\Issue\Tasks\IssueManagers\GuangXiKuaiSan;
use App\Services\Issue\Tasks\IssueManagers\IssueManagerContract;
use App\Services\Issue\Tasks\IssueManagers\Issuer;
use App\Services\LotterySettings\Tasks\GetStopBetExpire;
use App\Services\ServeResult;
use App\Services\ServiceDispatcher;
use App\Services\TaskServiceContract;
use App\Models\Issue;

class GenerateIssues implements TaskServiceContract
{
    private $lottery;

    private $beginDate;

    private $endDate;

    private $issueManager;

    private $issuesGenerators = [
        Lottery::GUANGXIKUAILE10_TYPE_CODE => GuangXiKuaiLeShiFen::class,
        Lottery::GUANGXIKUAI3_TYPE_CODE => GuangXiKuaiSan::class
    ];

    public function __construct(Lottery $lottery, string $beginDate = null, string $endDate = null)
    {
        $this->lottery = $lottery;
        if (!$beginDate) {
            $this->beginDate = date('Y-m-d');
        }
        if (!$endDate) {
            $this->endDate = date('Y-m-d');
        }
        $this->issueManager = $this->getIssuesGenerator();
    }

    public function run(): ServeResult
    {
        $lastDate = $this->beginDate;
        $endDateToTimeStamp = strtotime($this->endDate);
        while (strtotime($lastDate) < $endDateToTimeStamp) {
            $issues = $this->issueManager->createIssues($this->lottery->code, $lastDate);

            $lastDate = date('Y-m-d', strtotime('+1 day', strtotime($lastDate)));

            $fetchedIssues = [];
            foreach ($issues as $issue) {
                if ($issue->hasError()) {
                    return ServeResult::make([$issue->getError()]);
                }
                $fetchedIssues[] = $issue->issue;
            }

            $existsIssues = Issue::whereIn(Issue::ISSUE_FIELD, $fetchedIssues)->pluck(Issue::ISSUE_FIELD)->toArray();
            foreach ($fetchedIssues as $index => $fetchedIssue) {
                if (in_array($fetchedIssue, $existsIssues)) {
                    unset($issues[$index]);
                }
            }

            foreach ($issues as $issue) {
                $this->saveIssue($issue);
            }
        }

        $issues = $this->issueManager->createIssues($this->lottery->code, $this->endDate);
        foreach ($issues as $issue) {
            if ($issue->hasError()) {
                return ServeResult::make([$issue->getError()]);
            }
            $this->saveIssue($issue);
        }
        return ServeResult::make();
    }

    private function saveIssue(Issuer $issue)
    {
        $getStopBetAtResult = ServiceDispatcher::dispatch(
            ServiceDispatcher::TASK_SERVICE,
            new GetStopBetExpire($this->lottery->code)
        );
        if ($getStopBetAtResult->hasErrors()) {
            throw new \Exception($getStopBetAtResult->getError());
        }

        if (Issue::where(Issue::ISSUE_FIELD, $issue->issue)->where(Issue::LOTTERY_ID_FIELD, $this->lottery->id)->count()) {
            return;
        }

        $issueModel = new Issue();
        $issueModel->lottery_id = $this->lottery->id;
        $issueModel->issue = $issue->issue;
        if ($issue->isOpened) {
            $issueModel->reward_codes = $issue->rewardCodes;
            $issueModel->status = Issue::OPENED_STATUS;
        } else {
            $issueModel->status = Issue::NO_OPEN_STATUS;
        }
        $issueModel->started_at = $issue->openedAt - $this->lottery->limit_time * 60;
        $issueModel->ended_at = $issue->openedAt;
        $issueModel->stop_bet_at = $issueModel->ended_at - $getStopBetAtResult->getData();
        $issueModel->save();
    }

    private function getIssuesGenerator(): IssueManagerContract
    {
        return new $this->issuesGenerators[$this->lottery->code];
    }
}
