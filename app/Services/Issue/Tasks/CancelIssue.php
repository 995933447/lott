<?php
namespace App\Services\Issue\Tasks;

use App\Models\BetOrder;
use App\Models\Issue;
use App\Services\BetOrder\Tasks\CancelOrder;
use App\Services\ServeResult;
use App\Services\ServiceDispatcher;
use App\Services\TaskServiceContract;
use Illuminate\Support\Facades\DB;

class CancelIssue implements TaskServiceContract
{
    private $issue;

    public function __construct(Issue $issue)
    {
        $this->issue = $issue;
    }

    public function run(): ServeResult
    {
        DB::beginTransaction();
        try {
            $this->issue = $this->issue->lockForUpdate()->where($this->issue->getPrimaryKey(), $this->issue->id)->first();

            if ($this->issue->status == Issue::OPENING_STATUS) {
                DB::commit();
                return ServeResult::make(['此期正在开奖中，为避免数据混乱请开奖完成后重试该操作']);
            }

            $this->issue->status = Issue::OPEN_CANCEL_STATUS;
            $this->issue->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        $orders = BetOrder::where(BetOrder::ISSUE_FIELD, $this->issue->issue)
            ->where(BetOrder::LOTTERY_ID_FIELD, $this->issue->lottery_id)
            ->where(BetOrder::STATUS_FIELD, '<>', BetOrder::CANCLE_STATUS)
            ->get();
        foreach ($orders as $order) {
            $result = ServiceDispatcher::dispatch(ServiceDispatcher::TASK_SERVICE, new CancelOrder($order, true));
            if ($result->hasErrors()) {
                return $result;
            }
        }

        return ServeResult::make();
    }
}
