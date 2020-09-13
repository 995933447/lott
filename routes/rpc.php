<?php

use App\Repositories\LotteryRepository;
use App\Utils\Formatters\End;
use Illuminate\Http\Request;

$router->group(['domain' => 'lottery.rpc.net'], function ($router) {
    $router->get('/test', function () {
        return 'This is lottery.rpc.net';
    });

    $router->post('/cancel-issue/{issueId:[0-9]+}', 'IssueController@cancelIssue');

    $router->post('/recount-orders/issue/{issue:[0-9]+}', 'BetOrderController@recountOrdersOfIssue');

    $router->post('/issue', 'IssueController@resetIssue');

    $router->get('/lotteries', function (Request $request) {
        return End::toSuccessJson(LotteryRepository::get($request->input('ids')?? []));
    });

    $router->get('/lottery-issues/{lotteryId:[0-9]+}/page-size/{pageSize:[0-9]+}', function (Request $request, $lotteryId, $pageSize) {
        return End::toSuccessJson(
            LotteryRepository::issuesPaginate(
                $lotteryId,
                $request->input('datetime')?: [],
                $request->input('status'),
                (int) $pageSize
            )
        );
    });
});
