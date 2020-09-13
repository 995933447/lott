<?php
namespace App\Services\BetOrder\Validators\CommitOrder\BetDataValidators;

use App\Models\Lottery;
use App\Models\LotteryBetType;
use App\Services\LotterySettings\Tasks\UsableBetItemsGetter\GetLotteryUsableBetItems;
use App\Services\ServeResult;
use App\Services\ServiceDispatcher;

abstract class BetDataValidatorContract
{
    public function handle(string $betType, array $data): ServeResult
    {
        $getUsableBetItemsResult = ServiceDispatcher::dispatch(
            ServiceDispatcher::TASK_SERVICE,
            GetLotteryUsableBetItems::class,
            static::getLotteryCode(),
            $betType
        );
        if ($getUsableBetItemsResult->hasErrors()) {
            return $getUsableBetItemsResult;
        }

       return $this->runRelatedValidation($betType, $data, $getUsableBetItemsResult->getData());
    }

    abstract protected function runRelatedValidation(string $betType, array $data, array $usableBetItems): ServeResult;

    abstract protected function getLotteryCode(): string;

    // 一注只能下一项类型玩法的默认验证器
    protected function useDefaultOneItemTypeValidation(array $data, array $usableBetItems): ServeResult
    {
        if (count($data['codes']) !== 1) {
            return ServeResult::make(['本投注玩法一次只能投注一个号码']);
        }
        $codes = explode(':', $data['codes'][0]);
        if (!isset($usableBetItems[$codes[0]]) || !isset($usableBetItems[$codes[0]][$codes[1]])) {
            return ServeResult::make(["{$data['codes'][0]}:{$usableBetItems[$codes[0]][$codes[1]]}玩法不存在"]);
        }
        $betItemConfig = $usableBetItems[$codes[0]][$codes[1]];
        if (is_array($betItemConfig['odds'])) {
            $odds = is_array($betItemConfig['odds'][$data['face']])? $betItemConfig['odds'][$data['face']][$codes[2]]: $betItemConfig['odds'][$data['face']];
        } else {
            $odds = $betItemConfig['odds'];
        }
        if ((float) $odds !== (float) $data['odds']) {
            return ServeResult::make(['网站赔率已更新,请刷新网站后重新投注']);
        }

        list($minMoneyLimit, $maxMoneyLimit) =  $betItemConfig['money_limit'][$data['face']];
        if (bccomp($data['money'], $minMoneyLimit) <= 0) {
            return ServeResult::make(["投注金额必须大于{$minMoneyLimit}"]);
        }
        if (bccomp($data['money'], $maxMoneyLimit) >= 0) {
            return ServeResult::make(["投注金额必须小于{$maxMoneyLimit}"]);
        }
        if (!(int)in_array($data['face'], $betItemConfig['valid_face'])) {
            $face = $data['face'] == LotteryBetType::X_PLAY_FACE ? 'x': 'y';
            return ServeResult::make(["投注盘面{$face}正在维护"]);
        }
        return ServeResult::make();
    }

    // 一注只能下一项但是有子项目类型的默认验证器
    protected function useDefaultOneItemWithSubItemsTypeValidation(array $data, array $usableBetItems): ServeResult
    {
        if (count($data['codes']) !== 1) {
            return ServeResult::make(['下注号码格式请求']);
        }

        $codes = explode(':', $data['codes'][0], 3);
        if (!isset($usableBetItems[$codes[0]]) || !isset($usableBetItems[$codes[0]][$codes[1]])) {
            return ServeResult::make(["{$data['codes'][0]}:{$usableBetItems[$codes[0]][$codes[1]]}玩法不存在"]);
        }

        $betItemConfig = $usableBetItems[$codes[0]][$codes[1]];

        if (is_array($betItemConfig['odds'])) {
            $odds = is_array($betItemConfig['odds'][$data['face']])? $betItemConfig['odds'][$data['face']][$codes[2]]: $betItemConfig['odds'][$data['face']];
        } else {
            $odds = $betItemConfig['odds'];
        }
        if ((float) $odds !== (float) $data['odds']) {
            return ServeResult::make(['网站赔率已更新,请刷新网站后重新投注']);
        }

        list($minMoneyLimit, $maxMoneyLimit) =  $betItemConfig['money_limit'][$data['face']];
        if (bccomp($data['money'], $minMoneyLimit) <= 0) {
            return ServeResult::make(["投注金额必须大于{$minMoneyLimit}"]);
        }

        if (bccomp($data['money'], $maxMoneyLimit) >= 0) {
            return ServeResult::make(["投注金额必须小于{$maxMoneyLimit}"]);
        }

        if (!(int)in_array($data['face'], $betItemConfig['valid_face'])) {
            $face = $data['face'] == LotteryBetType::X_PLAY_FACE ? 'x': 'y';
            return ServeResult::make(["投注盘面{$face}正在维护"]);
        }

        $subCodes = explode(',', $codes[2]);
        if (($subCodesNum = count($subCodes)) <= $betItemConfig['number_limit'][0] ||  $subCodesNum >= $betItemConfig['number_limit'][1]) {
            if (($betItemConfig['number_limit'][1] - $betItemConfig['number_limit'][0]) === 2) {
                return ServeResult::make(['投注号码个数必须是' . ($betItemConfig['number_limit'][0] + 1) . '个']);
            }
            return ServeResult::make(["投注号码个数必须大于{$betItemConfig['number_limit'][0]}且小于{$betItemConfig['number_limit'][1]}"]);
        }

        if ($subCodes !== array_unique($subCodes)) {
            return ServeResult::make(['投注号码中不能有重复的号码']);
        }

        if (!empty(array_diff($subCodes, $betItemConfig['sub_bet_items']))) {
            return ServeResult::make(["不存在投注号码项{$codes[0]}:{$codes[1]}:" . implode(",", $subCodes)]);
        }

        return ServeResult::make();
    }
}
