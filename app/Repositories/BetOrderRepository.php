<?php
namespace App\Repositories;

use App\Models\BetOrder;
use App\Models\Lottery;

class BetOrderRepository
{
    public static function getByLotteryCodes(array $lotteryCodes, int $status = null, int $limit = 0, int $offset = 0): array
    {
        if (empty($lotteryCodes)) return [];

        $betOrders = (new BetOrder)->with(['user', 'lottery'])->whereIn(BetOrder::LOTTERY_CODE_FIELD, $lotteryCodes);

        if (!is_null($status)) {
            $betOrders = $betOrders->where(BetOrder::STATUS_FIELD, $status);
        }

        if ($limit > 0) {
            $betOrders = $betOrders->limit($limit);
        }

        if ($offset > 0) {
            $betOrders = $betOrders->offset($offset);
        }

        return $betOrders->get()->toArray();
    }

    public static function paginateByLotteryCodes(int $status = null, int $pageSize): array
    {
        if (empty($lotteryCodes)) return [];

        $betOrders = (new BetOrder)->with(['user', 'lottery'])->whereIn(BetOrder::LOTTERY_CODE_FIELD, $lotteryCodes);
        if (!is_null($status)) {
            $betOrders = $betOrders->where(BetOrder::STATUS_FIELD, $status);
        }
        return $betOrders->paginate($pageSize)->toArray();
    }

    public static function get(int $status = null, int $limit = 0, int $offset = 0): array
    {
        $betOrders = (new BetOrder)->with(['user', 'lottery']);

        if (!is_null($status)) {
            $betOrders = $betOrders->where(BetOrder::STATUS_FIELD, $status);
        }

        if ($limit > 0) {
            $betOrders = $betOrders->limit($limit);
        }

        if ($offset > 0) {
            $betOrders = $betOrders->offset($offset);
        }

        return $betOrders->get()->toArray();
    }

    public static function paginate(int $status = null, int $pageSize): array
    {
        $betOrders = (new BetOrder)->with(['user', 'lottery']);
        if (!is_null($status)) {
            $betOrders = $betOrders->where(BetOrder::STATUS_FIELD, $status);
        }
        return $betOrders->paginate($pageSize)->toArray();
    }

    public static function getByUser(int $userId, array $lotteryCodes = [], array $datetime = [], int $status = null, int $limit = 0, int $offset = 0): array
    {
        $betOrders = (new BetOrder)->with(['user', 'lottery'])->where(BetOrder::USER_ID_FIELD, $userId);

        if (!empty($lotteryCodes)) {
            $betOrders = $betOrders->whereIn(BetOrder::LOTTERY_CODE_FIELD, $lotteryCodes);
        }

        if (!empty($datetime)) {
            $betOrders = $betOrders->where(Lottery::CREATED_AT, '>=', $datetime[0])->where(Lottery::CREATED_AT, '<=', $datetime[1]);
        }

        if (!is_null($status)) {
            $betOrders = $betOrders->where(BetOrder::STATUS_FIELD, $status);
        }

        if ($limit > 0) {
            $betOrders = $betOrders->limit($limit);
        }

        if ($offset > 0) {
            $betOrders = $betOrders->offset($offset);
        }

        return $betOrders->orderBy(BetOrder::CREATED_AT, 'DESC')->get()->toArray();
    }

    public static function paginateByUser(int $userId, array $lotteryCodes = [], array $datetime = [], int $status = null, $pageSize): array
    {
        $betOrders = (new BetOrder)->with(['user', 'lottery'])->where(BetOrder::USER_ID_FIELD, $userId);

        if (!empty($lotteryCodes)) {
            $betOrders = $betOrders->whereIn(BetOrder::LOTTERY_CODE_FIELD, $lotteryCodes);
        }

        if (!empty($datetime)) {
            $betOrders = $betOrders->where(Lottery::CREATED_AT, '>=', $datetime[0])->where(Lottery::CREATED_AT, '<=', $datetime[1]);
        }

        if (!is_null($status)) {
            $betOrders = $betOrders->where(BetOrder::STATUS_FIELD, $status);
        }

        return $betOrders->orderBy(BetOrder::CREATED_AT, 'DESC')->paginate($pageSize)->toArray();
    }

    public static function paginateByIssue(int $userId, string $issue, int $pageSize)
    {
        return (new BetOrder)->with(['user', 'lottery'])
            ->where(BetOrder::USER_ID_FIELD, $userId)
            ->where(BetOrder::ISSUE_FIELD, $issue)
            ->orderBy(BetOrder::CREATED_AT, 'DESC')
            ->paginate($pageSize)
            ->toArray();
    }
}
