<?php
namespace App\Services\LotterySettings\Tasks;

use App\Services\ServeResult;
use App\Services\TaskServiceContract;
use Illuminate\Support\Facades\Config;

class GetIsAutoBackCancelIssueOrders implements TaskServiceContract
{
    public function run(): ServeResult
    {
        return ServeResult::make([], Config::get('game.auto_back_cancel_issue_orders', false));
    }
}
