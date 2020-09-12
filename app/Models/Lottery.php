<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lottery extends Model
{
    use ExtraTrait;

    const STATUS_FIELD = 'status';

    const SORT_FIELD = 'sort';

    const CODE_FIELD = 'code';

    const NAME_FIELD = 'name';

    const VALID_STATUS = 1;

    const IS_OFFICIAL_STATUS = 1;

    const GUANGXIKUAILE10_TYPE_CODE = 'gxkl10';
    const GUANGXIKUAI3_TYPE_CODE = 'gxk3';

    public function betType()
    {
       return $this->belongsToMany(BetType::class, (new LotteryBetType())->getTable())
                ->withPivot(LotteryBetType::STATUS_FIELD, LotteryBetType::PLAY_FACE_FIELD);
    }
}
