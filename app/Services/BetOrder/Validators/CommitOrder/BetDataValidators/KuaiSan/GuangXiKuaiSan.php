<?php
namespace App\Services\BetOrder\Validators\CommitOrder\BetDataValidators\KuaiSan;

use App\Services\BetOrder\Validators\CommitOrder\BetDataValidators\BetDataValidatorContract;
use App\Models\Lottery;
use App\Services\ServeResult;

class GuangXiKuaiSan extends BetDataValidatorContract
{
    protected function getLotteryCode(): string
    {
        return Lottery::GUANGXIKUAI3_TYPE_CODE;
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
}
