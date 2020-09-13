<?php
namespace App\Services\BetOrder\Tasks\AwardOrderPrize\RewardCounters;

use App\Models\BetOrder;
use App\Models\Issue;

interface RewardCounterContract
{
    public static function handle(Issue $issue, BetOrder $betOrder): CountRewardResult;
}
