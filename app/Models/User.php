<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use App\Models\Connections\BySiteConnectionModel;

class User extends BySiteConnectionModel implements AuthenticatableContract, AuthorizableContract
{
    use ExtraTrait, Authenticatable, Authorizable;

    const ID_FIELD = 'id';

    const STATUS_FIELD = 'status';

    const USERNAME_FIELD = 'username';

    const PASSWORD_FIELD = 'password';

    const AGENT_ID_FIELD = 'agent_id';

    const PAY_PASSWORD_FIELD = 'pay_password';

    const GAME_PASSWORD_FIELD = 'game_password';

    const VALID_STATUS = 1;

    protected $hidden = [
        self::PASSWORD_FIELD,
        self::PAY_PASSWORD_FIELD,
        self::GAME_PASSWORD_FIELD
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function balance()
    {
        return $this->hasOne(UserBalance::class)->where(UserBalance::STATUS_FIELD, UserBalance::VALID_STATUS);
    }

    public function getIsBindPasswordAttribute()
    {
        return !empty($this->pay_password);
    }
}
