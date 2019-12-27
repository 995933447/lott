<?php
namespace App\Services\LotterySettings\Tasks\UsableBetItemsGetter;

use App\Models\Lottery;
use App\Services\LotterySettings\Tasks\UsableBetItemsGetter\LotteryUsableBetItems\KuaiLeShiFen\GuangXiKuaiLeShiFen;
use App\Services\LotterySettings\Tasks\UsableBetItemsGetter\LotteryUsableBetItems\KuaiSan\GuangXiKuaiSan;
use App\Services\LotterySettings\Tasks\UsableBetItemsGetter\LotteryUsableBetItems\LotteryUsableBetItemsContract;
use App\Services\ServeResult;
use App\Services\TaskServiceContract;

class GetLotteryUsableBetItems implements TaskServiceContract
{
    private $lotteryUsableBetItemsHandlers = [
        Lottery::GUANGXIKUAILE10_TYPE_CODE => GuangXiKuaiLeShiFen::class,
        Lottery::GUANGXIKUAI3_TYPE_CODE => GuangXiKuaiSan::class
    ];

    private $lotteryUsableBetItemsHandler;

    private $betTypeCode;

    public function __construct(string $lotteryCode, string $betTypeCode)
    {
        $this->lotteryUsableBetItemsHandler = $this->getLotteryUsableBetItemsHandler($lotteryCode);
        $this->betTypeCode = $betTypeCode;
    }

    public function run(): ServeResult
    {
        if (is_null($this->lotteryUsableBetItemsHandler)) {
            return ServeResult::make(['彩票代码不正确']);
        }

        $usableBetItems = $this->lotteryUsableBetItemsHandler::getItems($this->betTypeCode);
        return ServeResult::make([], $usableBetItems);
    }

    private function getLotteryUsableBetItemsHandler(string $lotteryCode): string
    {
        return $this->lotteryUsableBetItemsHandlers[$lotteryCode];
    }
}
