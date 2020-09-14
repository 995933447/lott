<?php
namespace App\Services\Issue\Tasks\IssueManagers;

use App\Models\Issue;
use App\Models\Lottery;
use App\Utils\Timer\Date;

class GuangXiKuaiSan extends OpenCaiNetFetcher
{
    public function getDailyIssuesBetween(): int
    {
        return 1000;
    }

    public function getDateFistIssue(string $date): string
    {
        $dateToTimestamp = strtotime($date);
        $year = date('Y', $dateToTimestamp);
        $subDate = date('md', $dateToTimestamp);

        return sprintf("%d%s001", $year, $subDate);
    }
}
