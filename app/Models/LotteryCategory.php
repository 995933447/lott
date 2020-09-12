<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LotteryCategory extends Model
{
    use ExtraTrait;

    const STATUS_FIELD = 'status';

    const SORT_FIELD = 'sort';

    const VALID_STATUS = 1;

    public function lottery()
    {
        $related = $this->hasMany(Lottery::class)->where(Lottery::STATUS_FIELD, Lottery::VALID_STATUS);
        if ($offset = $this->getCurrentOffset() > 0) {
            $related->offset($offset);
        }
        if ($limit = $this->getPerPageLimit() > 0) {
            $related->limit($limit);
        }
        $related->orderBy(static::SORT_FIELD, 'DESC');

        return $related;
    }
}
