<?php
namespace App\Services\Award\Tasks\AwardOrderPrize\RewardCounters;

use App\Models\BetOrder;
use App\Models\Issue;
use App\Services\ServeResult;

interface RewardCounterContract
{
    public static function handle(Issue $issue, BetOrder $betOrder): CountRewardResult;
}
