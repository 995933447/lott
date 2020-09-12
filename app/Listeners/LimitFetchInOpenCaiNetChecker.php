<?php

namespace App\Listeners;

use App\Events\ReadyFetchIssueInOpenCaiNet;
use App\Utils\Cache\Keys\KeysEnum;
use Illuminate\Support\Facades\Cache;

class LimitFetchInOpenCaiNetChecker
{
    public function handle(ReadyFetchIssueInOpenCaiNet $event)
    {
        while ($left = Cache::get(KeysEnum::LAST_FETCH_IN_OPENCAINET_AT, 0)) {
            echo  30 - (time() - $left) . '秒后请求抓彩接口' . PHP_EOL;
            sleep(1);
        }
    }
}
