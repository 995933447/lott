<?php
namespace App\Models;

use App\Utils\Formatters\Money;
use Illuminate\Database\Eloquent\Model;

class BetOrder extends Model
{
    use ExtraTrait;

    const STATUS_FIELD = 'status';
    const USER_ID_FIELD = 'user_id';
    const LOTTERY_CODE_FIELD = 'lottery_code';
    const ISSUE_FIELD = 'issue';
    const LOTTERY_ID_FIELD = 'lottery_id';
    const ORDER_NO_FIELD = 'order_no';
    const REWARD_CODES_FIELD = 'reward_codes';
    const CODES_FIELD = 'codes';

    const SUCCESS_STATUS = 0;
    const BILLING_STATUE = 1;
    const SETTLEMENT_STATUS = 2;
    const CANCLE_STATUS = 3;

    const REWARD_STATUS = 1;
    const LOST_STATUS = 0;
    const NO_REWARD_NO_LOST_STATUS = 2;

    public function getRewardCodesAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setRewardCodesAttribute($value)
    {
        $this->attributes['reward_codes'] = is_array($value)? json_encode($value): $value;
    }

    public function getCodesAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setCodesAttribute($value)
    {
        $this->attributes['codes'] = is_array($value)? json_encode($value): $value;
    }

    public function getWinAttribute($value)
    {
        return Money::normalize($value);
    }

    public function setWinAttribute($value)
    {
        $this->attributes['win'] = Money::normalize($value);
    }

    public function getBetMoneyAttribute($value)
    {
        return Money::normalize($value);
    }

    public function setBetMoneyAttribute($value)
    {
        $this->attributes['bet_money'] = Money::normalize($value);
    }

    public function getRewardMoneyAttribute($value)
    {
        return Money::normalize($value);
    }

    public function setRewardMoneyAttribute($value)
    {
        $this->attributes['reward_money'] = Money::normalize($value);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lottery()
    {
        return $this->belongsTo(Lottery::class);
    }
}
