<?php
namespace App\Services\Issue\Tasks\IssueManagers;

use App\Events\AfterFetchIssueInOpenCaiNet;
use App\Events\ReadyFetchIssueInOpenCaiNet;
use App\Models\Issue;
use App\Models\Lottery;
use App\Utils\Timer\Date;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Event;

abstract class OpenCaiNetFetcher extends OfficeLotteryIssueManagerContract
{
    const FETCHER_TOKEN = 'tc4944b3f0239dbdbk';

    private $lotteryCodeToApiCodes = [
        Lottery::GUANGXIKUAILE10_TYPE_CODE => 'gxklsf',
        Lottery::GUANGXIKUAI3_TYPE_CODE => 'gxk3'
    ];

    public static $openedIssues = [];

    public function getIssueResult(Issue $issue, string $lotteryCode): Issuer
    {
        if ($this->checkIssueResultInFetched($issue->issue)) {
           return $this->getIssueResultInFetched($issue->issue);
        }

        return $this->fetchIssueResult($lotteryCode, $issue);
    }

    private function fetchIssueResult(string $lotteryCode, Issue $issue): Issuer
    {
        if (($issueDate = date('Y-m-d', $issue->ended_at)) !== ($todayDate = date('Y-m-d'))) {
            $data = $this->fetchForDate($lotteryCode, $issueDate);
        } else {
            $data = $this->fetchMostNews($lotteryCode);
        }

        if (is_null($data)) {
            $api = $issueDate !== $todayDate? $this->getFetchForDateApi($lotteryCode, $issueDate): $this->getFetchMostNewsApi($lotteryCode);
            return new Issuer("{$api}请求失败");
        }

        $newIssue = new Issuer();
        $hasSetIssue = false;
        $issueHasExpire = false;
        foreach ($data['data'] as $value) {
            static::$openedIssues[$value['expect']] = $value;

            if ($issue->issue === $value['expect']) {
                $newIssue->setIssue($value['expect']);
                $newIssue->setOpenedAt($value['opentimestamp']);
                $newIssue->setRewardCodes(explode(',', $value['opencode']));
                $newIssue->setIsOpened(true);
                $hasSetIssue = true;
            }
            if ($issue->issue < $value['expect']) {
                $issueHasExpire = true;
            }
        }
        if (!$issueHasExpire && !$hasSetIssue) {
            $newIssue->setIssue($issue->issue);
            $newIssue->setOpenedAt((int) Issue::where(Issue::ISSUE_FIELD, $issue->issue)->value(Issue::ENDED_AT_FIELD));
            $newIssue->setIsOpened(false);
        } else if ($issueHasExpire && !$hasSetIssue) {
            $newIssue->setIssue($issue->issue);
            $newIssue->setIsCancel(true);
        }

        return $newIssue;
    }

    private function getIssueResultInFetched(string $issue): Issuer
    {
        $data = static::$openedIssues[$issue];
        $newIssue = new Issuer();
        $newIssue->setIsOpened(true);
        $newIssue->setOpenedAt($data['opentimestamp']);
        $newIssue->setRewardCodes(explode(',', $data['opencode']));
        $newIssue->setIssue($data['expect']);
        return $newIssue;
    }

    private function checkIssueResultInFetched(string $issue): bool
    {
        return isset(static::$openedIssues[$issue]);
    }

    private function fetchHasNotStartedDateIssues(string $lotteryCode, string $date): Issuers
    {
        $result = new Issuers();

        // 以某日为参考日奖期计算新奖期
        if (strtotime($date) > strtotime($todayDate = date('Y-m-d'))) {
            $referenceDate = date('Y-m-d', strtotime('-1 day', strtotime($todayDate)));
        } else {
            $referenceDate = date('Y-m-d', strtotime('-1 day', strtotime($date)));
        }

        $referenceIssues = $this->fetchForDate($lotteryCode, $referenceDate);

        if (is_null($referenceIssues)) {
            $api = $this->getFetchForDateApi($lotteryCode, $referenceDate);
            $result->add(new Issuer("{$api}请求失败"));
            return $result;
        }

        $referenceIssueDateBetween = Date::getDatesBetweenDays($referenceDate, $date);

        foreach ($referenceIssues['data'] as $referenceIssue) {
            $issue = new Issuer();
            $issue->setIssue($referenceIssue['expect'] + $this->getDailyIssuesBetween() * $referenceIssueDateBetween);
            $issue->setIsOpened(false);
            $issue->setOpenedAt(strtotime("+{$referenceIssueDateBetween} day", $referenceIssue['opentimestamp']));
            $result->add($issue);
        }

        return $result;
    }

    private function fetchHasStartedDateIssues(string $lotteryCode, string $date): Issuers
    {
        // 向开彩网抓取该日期已开奖期
        $data = $this->fetchForDate($lotteryCode, $date);
        $result = new Issuers();
        // 开彩网出现问题异常处理
        if (is_null($data)) {
            $api = $this->getFetchForDateApi($lotteryCode, $date);
            $result->add(new Issuer("{$api}请求失败"));
            return $result;
        }

        foreach ($data['data'] as $value) {
            $newIssue = new Issuer();
            $newIssue->setIssue($value['expect']);
            $newIssue->setOpenedAt($value['opentimestamp']);
            $newIssue->setRewardCodes(explode(',', $value['opencode']));
            $newIssue->setIsOpened(true);
            $result->add($newIssue);
        }

        $lottery = ($lottery = new Lottery)->where(Lottery::CODE_FIELD, $lotteryCode)->first();
        $result = new Issuers();
        if (($fetchedIssuesNum = count($result)) > 0) {
            $lastIssue = $result[0];
            $missIssuesNum = $lottery->issue_num_day - $fetchedIssuesNum;

            // 计算出并填充当日未开奖期
            while ($missIssuesNum) {
                $issue = new Issuer();
                $issue->setIssue((int) $lastIssue->issue + 1);
                $issue->setIsOpened(false);
                $issue->setOpenedAt($lastIssue->openedAt + $lottery->limit_time * 60);
                $result->add($issue);

                $lastIssue = $issue;
                $missIssuesNum--;
            }
        }
        return $result;
    }

    public function fetchMostNews(string $lotteryCode):? array
    {
        Event::dispatch(new ReadyFetchIssueInOpenCaiNet());
        echo '开始抓彩' . PHP_EOL;
        $result = (new Client())->get(
            $this->getFetchMostNewsApi($lotteryCode),
            ['timeout' => 2]
        );
        Event::dispatch(new AfterFetchIssueInOpenCaiNet());
        return json_decode($result->getBody()->getContents(), true);
    }

    public function fetchForDate(string $lotteryCode, string $date):? array
    {
        Event::dispatch(new ReadyFetchIssueInOpenCaiNet());
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch,
            CURLOPT_URL,
            $this->getFetchForDateApi($lotteryCode, $date)
        );
        $result = curl_exec($ch);
        curl_close($ch);
        Event::dispatch(new AfterFetchIssueInOpenCaiNet());
        return json_decode($result, true);
    }

    protected function getFetchForDateApi(string $lotteryCode, string $date)
    {
        return "http://wd.apiplus.net/daily.do?token=" . static::FETCHER_TOKEN .
            "&code={$this->lotteryCodeToApiCodes[$lotteryCode]}&format=json&date={$date}";
    }

    protected function getFetchMostNewsApi(string $lotteryCode)
    {
        return "http://wd.apiplus.net/newly.do?token=" . static::FETCHER_TOKEN . "&code={$this->lotteryCodeToApiCodes[$lotteryCode]}&rows=20&format=json";
    }

    abstract protected function getDailyIssuesBetween(): int;
}
