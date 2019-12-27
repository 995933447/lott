<?php
namespace App\Services\Issue\Tasks\IssueManagers;

use App\Utils\Timer\Date;

class GuangXiKuaiLeShiFen extends OpenCaiNetFetcher
{
    public function getDailyIssuesBetween(): int
    {
        return 100;
    }

    protected function getDateFistIssue(string $date): string
    {
        $dateToTimestamp = strtotime($date);
        $year = Date::getYear($date);
        $days = Date::getDatesBetweenDays($year . '-01-01', date('Y-m-d', $dateToTimestamp)) + 1;

        if (strtotime($date) < strtotime($this->getSpringFestival($year))) {
            $subIssue = $days * 100 + 1;
        } else if (strtotime($date) < strtotime($year . '-10-01')) {
            $subIssue = ($days - 7) * 100 + 1;
        } else {
            $subIssue = ($days - 14) * 100 + 1;
        }

        return sprintf("%d%s", $year, str_pad($subIssue, 5, '0', STR_PAD_LEFT));
    }
}
