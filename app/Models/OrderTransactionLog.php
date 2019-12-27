<?php
namespace App\Models;

use App\Utils\Formatters\Money;
use Illuminate\Database\Eloquent\Model;

class OrderTransactionLog extends Model
{
    use ExtraTrait;

    const TYPE_FIELD = 'type';
    const USER_ID_FIELD = 'user_id';
    const ORDER_ID_FIELD = 'order_id';

    const BET_ORDER_TYPE = 0;
    const REWARD_PRIZE_TYPE = 1;
    const CANCEL_ORDER_TYPE = 2;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(BetOrder::class);
    }

    public function setBeforeTransactionBalanceAttribute($value)
    {
        $this->attributes['before_transaction_balance'] = Money::normalize($value);
    }

    public function getBeforeTransactionBalanceAttribute($value)
    {
        return Money::normalize($value);
    }

    public function setAfterTransactionBalanceAttribute($value)
    {
        $this->attributes['after_transaction_balance'] = Money::normalize($value);
    }

    public function getAfterTransactionBalanceAttribute($value)
    {
        return Money::normalize($value);
    }

    public function setTransactionMoneyAttribute($value)
    {
        $this->attributes['transaction_money'] = Money::normalize($value);
    }

    public function getTransactionMoneyAttribute($value)
    {
        return Money::normalize($value);
    }
}
