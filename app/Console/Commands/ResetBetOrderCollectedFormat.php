<?php
namespace App\Console\Commands;

use App\Models\BetOrder;
use App\Models\BetOrderForRebate;

class ResetBetOrderCollectedFormat extends ExtendCommand
{
    const SIGNATURE = 'collected-orders:reset-format';

    const PARAMS = '';

    const OPTIONS = '';

    public function handle()
    {
        $collectedOrders = BetOrderForRebate::all();
        foreach ($collectedOrders as $collectedOrder) {
            if (empty($collectedOrder->inning_no)) {
                $betOrder = BetOrder::where(BetOrder::ORDER_NO_FIELD, $collectedOrder->game_no)
                    ->select(BetOrder::ISSUE_FIELD, BetOrder::REWARD_CODES_FIELD, BetOrder::CODES_FIELD)
                    ->first();

                if ($betOrder) {
                    $collectedOrder->inning_no = $betOrder->issue;
                    $collectedOrder->bet_content = ['投注号码' => $betOrder->codes, '中奖号码' => $betOrder->reward_codes];
                    $collectedOrder->save();
                }
            }
        }
    }
}
