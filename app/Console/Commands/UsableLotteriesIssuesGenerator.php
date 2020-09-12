<?php
namespace App\Console\Commands;

use App\Jobs\IssueGenerator;
use App\Jobs\JobQueuesEnum;
use App\Models\Lottery;
use Illuminate\Support\Facades\Queue;

class UsableLotteriesIssuesGenerator extends ExtendCommand
{
    const SIGNATURE = 'issues:generate';

    const PARAMS = '';

    const OPTIONS = '';

    protected $description = '获取可用彩票并更新相关奖期信息,需启用队列命令配合工作';

    protected $lottery;

    public function __construct(Lottery $lottery)
    {
        parent::__construct();

        $this->lottery = $lottery;
    }

    public function handle()
    {
        $lotteries = $this->lottery->where(Lottery::STATUS_FIELD, Lottery::VALID_STATUS)->get();
        foreach ($lotteries as $lottery) {
            Queue::push(new IssueGenerator($lottery), '', JobQueuesEnum::ISSUE_SETTER);
        }
    }
}
