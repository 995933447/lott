<?php
namespace App\Repositories;

use App\Models\Issue;
use App\Models\Lottery;
use App\Models\LotteryCategory;

class LotteryRepository
{
    public static function getCategories()
    {
        return LotteryCategory::where(LotteryCategory::STATUS_FIELD, LotteryCategory::VALID_STATUS)->get()->toArray();
    }

    public static function getByCategories(array $categoryIds, int $limit = 0, int $offset = 0)
    {
        $model = new LotteryCategory;

        $model->setPerPageLimit($limit);

        $model->setCurrentOffset($offset);

        if (!empty($categoryIds)) {
            $model = $model->whereIn($model->getPrimaryKey(), $categoryIds);
        }

        return $model->where(LotteryCategory::STATUS_FIELD, LotteryCategory::VALID_STATUS)
                ->orderBy(LotteryCategory::SORT_FIELD, 'DESC')
                ->get()
                ->each(function ($category) {
                    $category->load('lottery.betType');
                })
                ->toArray();
    }

    public static function get(array $ids): array
    {
        $model = empty($ids)? new Lottery(): ($model = new Lottery())->whereIn($model->getPrimaryKey(), $ids);

        return $model->with('betType')
            ->where(Lottery::STATUS_FIELD, Lottery::VALID_STATUS)
            ->orderBy(Lottery::SORT_FIELD, 'DESC')
            ->get()
            ->toArray();
    }

    public static function find(int $id): array
    {
        $model = ($model = new Lottery())
            ->with('betType')
            ->where([Lottery::STATUS_FIELD => Lottery::VALID_STATUS, $model->getPrimaryKey() => $id])
            ->first();

        return $model? $model->toArray(): [];
    }

    public static function findByCode(string $code)
    {
        $model = ($model = new Lottery())
            ->where([Lottery::STATUS_FIELD => Lottery::VALID_STATUS, Lottery::CODE_FIELD => $code])
            ->first();

        return $model? $model->toArray(): [];
    }

    public static function getIssues(int $id, array $datetime = [], $status = null, int $limit = 0, int $offset = 0, $orderType = 'DESC'): array
    {
        $model = (new Issue())->where(Issue::LOTTERY_ID_FIELD, $id);

        if (!empty($datetime)) {
            $model = $model->where(Issue::ENDED_AT_FIELD, '>=', strtotime($datetime[0]))->where(Issue::ENDED_AT_FIELD, '<=', strtotime($datetime[1]));
        }

        $model = is_null($status)? $model: $model->where(Issue::STATUS_FIELD, $status);

        if ($limit > 0) {
            $model->limit($limit);
        }

        if ($offset > 0) {
            $model->offset($offset);
        }

        return $model->orderBy(Issue::STARTED_AT_FIELD, $orderType)->get()->toArray();
    }

    public static function issuesPaginate(int $id, array $datetime = [], $status = null, int $pageSize, $orderType = 'DESC'): array
    {
        $model = is_null($status)?
            (new Issue())->where(Issue::LOTTERY_ID_FIELD, $id):
            (new Issue())->where(Issue::STATUS_FIELD, $status)->where(Issue::LOTTERY_ID_FIELD, $id);

        if (!empty($datetime)) {
            $model = $model->where(Issue::ENDED_AT_FIELD, '>=', strtotime($datetime[0]))->where(Issue::ENDED_AT_FIELD, '<=', strtotime($datetime[1]));
        }

        return $model->orderBy(Issue::STARTED_AT_FIELD, $orderType)->paginate($pageSize)->toArray();
    }
}
