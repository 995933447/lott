<?php
namespace App\Services\Issue\Tasks;

use App\Events\CancelIssue;
use App\Events\DrawIssue;
use App\Models\Issue;
use App\Models\Lottery;
use App\Services\Issue\Tasks\IssueManagers\GuangXiKuaiLeShiFen;
use App\Services\Issue\Tasks\IssueManagers\GuangXiKuaiSan;
use App\Services\Issue\Tasks\IssueManagers\IssueManagerContract;
use App\Services\LotterySettings\Tasks\GetIsAutoBackCancelIssueOrders;
use App\Services\ServeResult;
use App\Services\ServiceDispatcher;
use App\Services\TaskServiceContract;
use App\Utils\Log\Logger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;

class DrawIssues implements TaskServiceContract
{
    const OPEN_DRAW_WORK_TIMEOUT = 120;

    private $lottery;
    private $issueManager;
    private $drawTime;

    private $issueManagers = [
        Lottery::GUANGXIKUAILE10_TYPE_CODE => GuangXiKuaiLeShiFen::class,
        Lottery::GUANGXIKUAI3_TYPE_CODE => GuangXiKuaiSan::class
    ];

    public function __construct(Lottery $lottery, ?string $drawDateTime = null)
    {
        $this->lottery = $lottery;
        $this->drawTime = $drawDateTime? strtotime($drawDateTime): time();
        $this->issueManager = $this->getIssueManager();
    }

    public function run(): ServeResult
    {
        try {
            $this->drawExistsIssue();
        } catch (\Exception $e) {
            echo $e;
            return ServeResult::make([$e]);
        }

        return ServeResult::make();
    }

    protected function drawExistsIssue()
    {
        DB::beginTransaction();
        try {
            $willExistsIssue = Issue::lockForUpdate()->where(function ($query) {
                $query->whereIn(Issue::STATUS_FIELD, [Issue::NO_OPEN_STATUS, Issue::DELAY_OPEN_STATUS, Issue::OPEN_FAIL_STATUS])
                    ->where(Issue::LOTTERY_ID_FIELD, $this->lottery->id)
                    ->where(Issue::ENDED_AT_FIELD, '<=', $this->drawTime);
            })->orWhere(function ($query) {
                $query->where(Issue::STATUS_FIELD, Issue::OPENING_STATUS)
                    ->where(Issue::LOTTERY_ID_FIELD, $this->lottery->id)
                    ->where(Issue::ENDED_AT_FIELD, '<', $this->drawTime - static::OPEN_DRAW_WORK_TIMEOUT);
            })->get();

            foreach ($willExistsIssue as $issueModel) {
                $issueModel->status = Issue::OPENING_STATUS;
                $issueModel->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }


        foreach ($willExistsIssue as $issueModel) {
            try {
                $issue = $this->issueManager->getIssueResult($issueModel, $this->lottery->code);
                if ($issue->hasError()) {
                    Logger::emergency($issue->getError());
                    echo $issue->getError();
                    $issueModel->status = Issue::OPEN_FAIL_STATUS;
                    $issueModel->save();
                    continue;
                }

                if ($issue->isCancel) {
                    $issueModel->status = Issue::OPEN_CANCEL_STATUS;
                    $issueModel->save();
                    $backOrdersResult = ServiceDispatcher::dispatch(ServiceDispatcher::TASK_SERVICE, GetIsAutoBackCancelIssueOrders::class);
                    if ($backOrdersResult->hasErrors()) {
                        echo $error = $backOrdersResult->getError();
                        Logger::emergency($error);
                    }
                    if ($backOrdersResult->getData()) {
                        Event::dispatch(new CancelIssue($issueModel));
                    }
                    continue;
                }

                if (!$issue->isOpened) {
                    $issueModel->status = Issue::DELAY_OPEN_STATUS;
                    $issueModel->save();
                    continue;
                }

                $issueModel->status = Issue::OPENED_STATUS;
                $issueModel->reward_codes = $issue->rewardCodes;
                $issueModel->save();

            } catch (\Exception $e) {
                echo $e;
                $issueModel->status = Issue::OPEN_FAIL_STATUS;
                $issueModel->save();
                continue;
            }

            Event::dispatch(new DrawIssue($issueModel));
        }
    }

    private function getIssueManager(): IssueManagerContract
    {
        return new $this->issueManagers[$this->lottery->code];
    }
}
