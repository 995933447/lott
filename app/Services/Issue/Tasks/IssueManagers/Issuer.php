<?php
namespace App\Services\Issue\Tasks\IssueManagers;

use InvalidArgumentException;

class Issuer
{
    private $issue;

    private $isOpened;

    private $rewardCodes = [];

    private $openedAt;

    private $error;

    private $isCancel = false;

    public function __construct(string $error = null)
    {
        $this->error = $error;
    }

    public function getError()
    {
        return $this->error;
    }

    public function hasError()
    {
        return !empty($this->error);
    }

    public function setIsCancel(bool $isCancel)
    {
        $this->isCancel = $isCancel;
    }

    public function setIssue(string $issue)
    {
        $this->issue = $issue;
    }

    public function setIsOpened(bool $isOpened)
    {
        $this->isOpened = $isOpened;
    }

    public function setRewardCodes(array $rewardCodes)
    {
        $this->rewardCodes = $rewardCodes;
    }

    public function setOpenedAt(int $time)
    {
        $this->openedAt = $time;
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            if (is_null($this->$name))
                throw new InvalidArgumentException("Property {$name} does not set.");
            return $this->$name;
        }
        throw new InvalidArgumentException("Property {$name} is not exist.");
    }
}
