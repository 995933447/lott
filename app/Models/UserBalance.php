<?php
namespace App\Models;

use App\Models\Connections\ByBalanceConnectionModel;
use App\Utils\Formatters\Money;

class UserBalance extends ByBalanceConnectionModel
{
    use ExtraTrait;

    const BALANCE_FIELD = 'balance';

    const STATUS_FIELD = 'balance_status';

    const USER_ID_FIELD = 'user_id';

    const VALID_STATUS = 1;

    protected $table = 'balance_user';

    public function getBalanceAttribute($value)
    {
        return Money::normalize($value);
    }

    public function setBalanceAttribute($value)
    {
        $this->attributes['balance'] = Money::normalize($value);
    }
}
