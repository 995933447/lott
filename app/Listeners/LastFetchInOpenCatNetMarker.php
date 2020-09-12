<?php

namespace App\Listeners;

use App\Events\AfterFetchIssueInOpenCaiNet;
use App\Utils\Cache\Keys\KeysEnum;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class LastFetchInOpenCatNetMarker
{
    public function handle(AfterFetchIssueInOpenCaiNet $event)
    {
        Cache::put(KeysEnum::LAST_FETCH_IN_OPENCAINET_AT, time(), Carbon::now()->addSeconds(30));
    }
}
