<?php
namespace App\Models;

use App\Utils\Formatters\Money;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use ExtraTrait;

    protected $table = 'issues';

    const STATUS_FIELD = 'status';

    const STARTED_AT_FIELD = 'started_at';

    const LOTTERY_ID_FIELD = 'lottery_id';

    const ISSUE_FIELD = 'issue';

    const STOP_BET_AT_FILED = 'stop_bet_at';

    const ENDED_AT_FIELD = 'ended_at';

    const REWARD_CODES_FIELD = 'reward_codes';

    const NO_OPEN_STATUS = 0;

    const OPENED_STATUS = 1;

    const OPENING_STATUS = 2;

    const DELAY_OPEN_STATUS = 3;

    const OPEN_FAIL_STATUS = 4;

    const OPEN_CANCEL_STATUS = 5;

    public function lottery()
    {
        return $this->belongsTo(Lottery::class);
    }

    public function getRewardCodesAttribute($value)
    {
        $values = json_decode($value, true);
        if ($values)
            foreach ($values as &$value)
                $value = (string) $value;

        return $values;
    }

    public function setRewardCodesAttribute($value)
    {
        $this->attributes['reward_codes'] = is_array($value)? json_encode($value): $value;
    }

    public function getTotalBetMoneyAttribute($value)
    {
        return Money::normalize($value);
    }

    public function setTotalBetMoneyAttribute($value)
    {
        $this->attributes['total_bet_money'] = Money::normalize($value);
    }

    public function getTotalRewardMoneyAttribute($value)
    {
        return Money::normalize($value);
    }

    public function setTotalRewardMoneyAttribute($value)
    {
        $this->attributes['total_reward_money'] = Money::normalize($value);
    }
}
