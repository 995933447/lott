<?php
namespace App\Repositories;

use App\Models\OrderTransactionLog;

class OrderTransactionLogRepository
{
    public static function paginateByUser(int $userId, int $type = null, array $datetime = [], int $pageSize): array
    {
        $logger = OrderTransactionLog::with(['user', 'order']);
        if (!is_null($type)) {
            $logger = $logger->where(OrderTransactionLog::TYPE_FIELD, $type);
        }
        if (!empty($datetime)) {
            $logger = $logger->where(OrderTransactionLog::CREATED_AT, '>=', $datetime[0])->where(OrderTransactionLog::CREATED_AT, '<=', $datetime[1]);
        }
        return $logger->where(OrderTransactionLog::USER_ID_FIELD, $userId)
            ->orderBy((new OrderTransactionLog())->getPrimaryKey(), 'DESC')
            ->paginate($pageSize)
            ->toArray();
    }

    public static function paginate(int $type = null, array $datetime = [], int $pageSize)
    {
        $logger = OrderTransactionLog::with(['user', 'order']);
        if (!is_null($type)) {
            $logger = $logger->where(OrderTransactionLog::TYPE_FIELD, $type);
        }
        if (!empty($datetime)) {
            $logger = $logger->where(OrderTransactionLog::CREATED_AT, '>=', $datetime[0])->where(OrderTransactionLog::CREATED_AT, '<=', $datetime[1]);
        }
        return $logger->orderBy((new OrderTransactionLog())->getPrimaryKey(), 'DESC')->paginate($pageSize)->toArray();
    }

    public static function findByOrderId(int $orderId): array
    {
        return OrderTransactionLog::with(['user', 'order'])->where(OrderTransactionLog::ORDER_ID_FIELD, $orderId)->first()->toArray();
    }
}
