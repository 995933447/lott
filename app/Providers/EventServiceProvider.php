<?php

namespace App\Providers;

use App\Events\AfterFetchIssueInOpenCaiNet;
use App\Events\CancelIssue;
use App\Events\CancelOrder;
use App\Events\CommitOrder;
use App\Events\DrawIssue;
use App\Events\ReadyFetchIssueInOpenCaiNet;
use App\Events\ResettleOrder;
use App\Listeners\BetOrderCollector;
use App\Listeners\CancelOrderListener;
use App\Listeners\DecrementBetsOfIssueListener;
use App\Listeners\IncrementBetsOfIssueListener;
use App\Listeners\LimitFetchInOpenCaiNetChecker;
use App\Listeners\LastFetchInOpenCatNetMarker;
use App\Listeners\OrderTransactionLogger;
use App\Listeners\PrizeJudgment;
use App\Models\Connections\ByBalanceConnectionModel;
use App\Models\Connections\CasionByConnectionModel;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;
use App\Events\SettleOrder;
use Illuminate\Support\Facades\Event;
use App\Listeners\DecrementAwardsOfIssueListener;

class EventServiceProvider extends ServiceProvider
{
    const BEGIN_COMMIT_ORDER_TRANSACTION_EVENT = 'begin_commit_order_transaction';
    const COMMIT_COMMIT_ORDER_TRANSACTION_EVENT = 'commit_commit_order_transaction';
    const ROLLBACK_COMMIT_ORDER_TRANSACTION_EVENT = 'rollback_commit_order_transaction';
    const BEGIN_CANCEL_ORDER_TRANSACTION_EVENT = 'begin_cancel_order_transaction';
    const COMMIT_CANCEL_ORDER_TRANSACTION_EVENT = 'commit_cancel_order_transaction';
    const ROLLBACK_CANCEL_ORDER_TRANSACTION_EVENT = 'rollback_cancel_order_transaction';
    const BEGIN_AWARD_ORDER_TRANSACTION_EVENT = 'begin_award_order_transaction';
    const COMMIT_AWARD_ORDER_TRANSACTION_EVENT = 'commit_award_order_transaction';
    const ROLLBACK_AWARD_ORDER_TRANSACTION_EVENT = 'rollback_award_order_transaction';

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // 捕抓sql日志
        'Illuminate\Database\Events\QueryExecuted' => [
            'App\Listeners\QueryListener'
        ],
        DrawIssue::class => [
            PrizeJudgment::class
        ],
        ReadyFetchIssueInOpenCaiNet::class => [
            LimitFetchInOpenCaiNetChecker::class
        ],
        AfterFetchIssueInOpenCaiNet::class => [
            LastFetchInOpenCatNetMarker::class
        ],
        CommitOrder::class => [
            BetOrderCollector::class,
            IncrementBetsOfIssueListener::class,
            OrderTransactionLogger::class
        ],
        CancelOrder::class => [
            DecrementBetsOfIssueListener::class,
            DecrementAwardsOfIssueListener::class,
            OrderTransactionLogger::class
        ],
        SettleOrder::class => [
            BetOrderCollector::class,
            OrderTransactionLogger::class,
        ],
        ResettleOrder::class => [
            BetOrderCollector::class,
            OrderTransactionLogger::class,
        ],
        CancelIssue::class => [
            CancelOrderListener::class
        ]
    ];

    public function boot()
    {
        parent::boot();

        // 提交注单事务开始
        Event::listen(static::BEGIN_COMMIT_ORDER_TRANSACTION_EVENT, function () {
            DB::beginTransaction();
            DB::connection(ByBalanceConnectionModel::CONNECTION)->beginTransaction();
        });
        // 提交注单事务完成
        Event::listen(static::COMMIT_COMMIT_ORDER_TRANSACTION_EVENT, function () {
            DB::connection(ByBalanceConnectionModel::CONNECTION)->commit();
            DB::commit();
        });
        // 提交订单事务回滚
        Event::listen(static::ROLLBACK_COMMIT_ORDER_TRANSACTION_EVENT, function () {
            DB::rollBack();
            DB::connection(ByBalanceConnectionModel::CONNECTION)->rollBack();
        });

        // 取消注单事务开始
        Event::listen(static::BEGIN_CANCEL_ORDER_TRANSACTION_EVENT, function () {
            DB::beginTransaction();
            DB::connection(ByBalanceConnectionModel::CONNECTION)->beginTransaction();
        });
        // 取消注单事务完成
        Event::listen(static::COMMIT_CANCEL_ORDER_TRANSACTION_EVENT, function () {
            DB::commit();
            DB::connection(ByBalanceConnectionModel::CONNECTION)->commit();
        });
        // 取消订单事务回滚
        Event::listen(static::ROLLBACK_CANCEL_ORDER_TRANSACTION_EVENT, function () {
            DB::connection(ByBalanceConnectionModel::CONNECTION)->rollBack();
            DB::rollBack();
        });

        // 注单派彩事务开始
        Event::listen(static::BEGIN_AWARD_ORDER_TRANSACTION_EVENT, function () {
            DB::beginTransaction();
            DB::connection(ByBalanceConnectionModel::CONNECTION)->beginTransaction();
            DB::connection(CasionByConnectionModel::CONNECTION)->beginTransaction();
        });
        // 注单派彩事务完成
        Event::listen(static::COMMIT_AWARD_ORDER_TRANSACTION_EVENT, function () {
            DB::commit();
            DB::connection(CasionByConnectionModel::CONNECTION)->commit();
            DB::connection(ByBalanceConnectionModel::CONNECTION)->commit();
        });
        // 注单派彩事务回滚
        Event::listen(static::ROLLBACK_AWARD_ORDER_TRANSACTION_EVENT, function () {
            DB::connection(ByBalanceConnectionModel::CONNECTION)->rollBack();
            DB::connection(CasionByConnectionModel::CONNECTION)->rollBack();
            DB::rollBack();
        });
    }
}
