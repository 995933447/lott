<?php
namespace App\Console\Commands;

use App\Jobs\IssueDrawer;
use App\Jobs\JobQueuesEnum;
use App\Models\Lottery;
use Illuminate\Support\Facades\Queue;

class UsableLotteriesIssuesDrawer extends ExtendCommand
{
    const SIGNATURE = 'issues:draw';

    const PARAMS = '';

    const OPTIONS = '{--datetime}';

    protected $description = '开奖,需启用队列命令配合工作';

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
            Queue::push(new IssueDrawer($lottery, $this->option('datetime')), '', JobQueuesEnum::PRIZE_DISTRIBUTOR);
        }
    }
}
