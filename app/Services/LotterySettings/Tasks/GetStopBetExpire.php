<?php
namespace App\Services\LotterySettings\Tasks;

use App\Services\ServeResult;
use App\Services\TaskServiceContract;

class GetStopBetExpire implements TaskServiceContract
{
    public function __construct(string $lotteryCode)
    {
    }

    public function run(): ServeResult
    {
        return ServeResult::make([], config('game.stop_before_end_at'));
    }
}
