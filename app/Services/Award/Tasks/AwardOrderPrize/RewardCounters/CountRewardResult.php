<?php
namespace App\Services\Award\Tasks\AwardOrderPrize\RewardCounters;

class CountRewardResult
{
    const REWARD_STATUS = 1;
    const LOST_STATUS = 0;
    const NO_REWARD_NO_LOST_STATUS = 2;

    private $winMoney;
    private $rewardStatus;
    private $rewardMoney;
    private $rewardCodes;

    public function setWinMoney(string $win)
    {
        $this->winMoney = $win;
    }

    public function setRewardStatus(int $status)
    {
        switch ($status) {
            case static::REWARD_STATUS:
            case static::LOST_STATUS:
            case static::NO_REWARD_NO_LOST_STATUS:
                break;
            default:
                $legalStatus = implode(',', [static::REWARD_STATUS, static::LOST_STATUS, static::LOST_STATUS]);
                throw new CountRewardException("Argument must be in [{$legalStatus}]");
        }
        $this->rewardStatus = $status;
    }

    public function setRewardCodes(array $codes)
    {
        $this->rewardCodes = $codes;
    }

    public function setRewardMoney(string $money)
    {
        $this->rewardMoney = $money;
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            if (!is_null($this->$name)) {
                return $this->$name;
            }
            throw new CountRewardException("Property {$name} is not set.");
        }

        throw new \InvalidArgumentException("Property {$name} not exist.");
    }
}
