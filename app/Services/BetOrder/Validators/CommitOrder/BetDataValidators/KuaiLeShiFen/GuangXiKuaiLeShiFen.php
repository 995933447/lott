<?php
namespace App\Services\BetOrder\Validators\CommitOrder\BetDataValidators\KuaiLeShiFen;

use App\Models\Lottery;
use App\Models\LotteryBetType;
use App\Services\BetOrder\Validators\CommitOrder\BetDataValidators\BetDataValidatorContract;
use App\Services\LotterySettings\Tasks\UsableBetItemsGetter\GetLotteryUsableBetItems;
use App\Services\ServeResult;
use App\Services\ServiceDispatcher;

class GuangXiKuaiLeShiFen extends BetDataValidatorContract
{
    protected function getLotteryCode(): string
    {
        return Lottery::GUANGXIKUAILE10_TYPE_CODE;
    }

    protected function runRelatedValidation(string $betType, array $data, array $usableBetItems): ServeResult
    {
        $concrete = 'validateFor' . ucfirst($betType) . 'BetType';
        return $this->$concrete($data, $usableBetItems);
    }

    protected function validateForHezhiBetType(array $data, array $usableBetItems): ServeResult
    {
        return $this->useDefaultOneItemWithSubItemsTypeValidation($data, $usableBetItems);
    }

    protected function validateForPutongBetType(array $data, array $usableBetItems): ServeResult
    {
        return $this->useDefaultOneItemTypeValidation($data, $usableBetItems);
    }

    protected function validateForXingYunBetType(array $data, array $usableBetItems): ServeResult
    {
        return $this->useDefaultOneItemWithSubItemsTypeValidation($data, $usableBetItems);
    }

    protected function validateForBuzhongBetType(array $data, array $usableBetItems): ServeResult
    {
        return $this->useDefaultOneItemWithSubItemsTypeValidation($data, $usableBetItems);
    }

    protected function validateForFushiBetType(array $data, array $usableBetItems): ServeResult
    {
        return $this->useDefaultOneItemWithSubItemsTypeValidation($data, $usableBetItems);
    }

    protected function validateForLianyingBetType(array $data, array $usableBetItems): ServeResult
    {
        $betItemNames = [];
        $parsedCodes = [];
        foreach ($data['codes'] as $code)
        {
            $code = explode(':', $code);
            $parsedCodes[] = $code;
            $betItemNames[] = $code[0];
        }
        if (count($betItemNames) < 2) {
            return ServeResult::make(['注单号码格式不正确']);
        }

        if (count(array_unique($betItemNames)) !== 1) {
            return ServeResult::make(['投注格式非法:一注只能投注一种连赢玩法']);
        }

        if (!isset($usableBetItems[$betItemNames[0]])) {
            return ServeResult::make(["投注玩法{$betItemNames[0]}不存在"]);
        }

        $legalItemNames = explode(',', ($legalItemNamesToken = array_key_first($usableBetItems[$betItemNames[0]])));
        $betItemConfig = $usableBetItems[$betItemNames[0]][$legalItemNamesToken];
        foreach ($parsedCodes as $parsedCode) {
            if (!in_array($parsedCode[1], $legalItemNames)) {
                return ServeResult::make(["{$parsedCode[0]}:{$parsedCode[1]}玩法不存在"]);
            }
            if (!in_array($parsedCode[2], $betItemConfig['sub_bet_items'])) {
                return ServeResult::make(["{$parsedCode[0]}:{$parsedCode[1]}:{$parsedCode[2]}玩法不存在"]);
            }
        }

        if (($betItemNum = count($betItemNames)) <= $betItemConfig['number_limit'][0] ||  $betItemNum >= $betItemConfig['number_limit'][1]) {
            if (($betItemConfig['number_limit'][1] - $betItemConfig['number_limit'][0]) === 2) {
                return ServeResult::make(['投注号码个数必须是' . ($betItemConfig['number_limit'][0] + 1) . '个']);
            }
            return ServeResult::make(["投注号码个数必须大于{$betItemConfig['number_limit'][0]}且小于{$betItemConfig['number_limit'][1]}"]);
        }

        $odds = is_array($betItemConfig['odds'])? $betItemConfig['odds'][$data['face']]: $betItemConfig['odds'];
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
        if (!in_array((int)$data['face'], $betItemConfig['valid_face'])) {
            $face = $data['face'] == LotteryBetType::X_PLAY_FACE ? 'x': 'y';
            return ServeResult::make(["投注盘面{$face}正在维护"]);
        }
        if ($data['codes'] !== array_unique($data['codes'])) {
            return ServeResult::make(['单注不能出现重复的投注号码']);
        }

        return ServeResult::make();
    }
}
