<?php
namespace App\Services\Issue\Tasks\IssueManagers;

use App\Models\Lottery;
use App\Utils\Log\Logger;
use App\Utils\Timer\Date;
use App\Utils\Timer\Lunar;

abstract class OfficeLotteryIssueManagerContract implements IssueManagerContract
{
    const SPRING_FESTIVAL = '春节';
    const NATIONAL_DAY = '国庆';

    public function createIssues(string $lotteryCode, string $date): Issuers
    {
        if (!is_null($notWorkingDay = $this->isNotWorkingDay($date))) {
            $exceptionMsg = $notWorkingDay . '期间内官方彩停止销售';
            Logger::emergency($exceptionMsg);
            throw new IssueMangerException($exceptionMsg);
        }

        $lottery = Lottery::where(Lottery::CODE_FIELD, $lotteryCode)->first();

        $issues = new Issuers();

        $theDateFirstIssue = new Issuer();
        $theDateFirstIssue->setIssue($this->getDateFistIssue($date));
        $theDateFirstIssue->setOpenedAt(strtotime($date . ' ' . $lottery->started_at) + $lottery->limit_time * 60);
        $theDateFirstIssue->setIsOpened(false);

        $issues->add($theDateFirstIssue);

        $issueNumDay = $lottery->issue_num_day;
        $lastIssue = $theDateFirstIssue;

        while (--$issueNumDay) {
            $issue = new Issuer();
            $issue->setIssue($lastIssue->issue + 1);
            $issue->setIsOpened(false);
            $issue->setOpenedAt($lastIssue->openedAt + $lottery->limit_time * 60);

            $issues->add($issue);
            $lastIssue = $issue;
        }

        return $issues;
    }

    protected function isNotWorkingDay(string $date):? string
    {
        $year = Date::getYear($date);
        if (Date::isBetweenDate($date, sprintf('%d-10-01', $year), sprintf('%d-10-07', $year))) {
            return static::NATIONAL_DAY;
        }

        if (
            Date::isBetweenDate(
                $date,
                $springFestival = $this->getSpringFestival($year),
                date('Y-m-d', strtotime('+6 day', strtotime($springFestival))))
        ) {
            return static::SPRING_FESTIVAL;
        }

        return null;
    }

    protected function getSpringFestival($year): string
    {
        $lunar = new Lunar();
        for ($month = 1; $month < 3; $month++) {
            for ($day = 1; $day <= 31; $day++) {
                $getFestivalResult = $lunar->getFestival($year, $month, $day);
                if ($getFestivalResult['is']) {
                    if ($getFestivalResult['info'] === '除夕') {
                        return sprintf(
                            "%d-%s-%d",
                            $year,
                            str_pad($month, 2, '0', STR_PAD_LEFT),
                            str_pad($day, 2, '0', STR_PAD_LEFT)
                        );
                    }
                }
            }
        }
    }

    abstract protected function getDateFistIssue(string $date): string;
}
