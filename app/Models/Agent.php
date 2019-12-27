<?php
namespace App\Models;

use App\Models\Connections\BySiteConnectionModel;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Laravel\Lumen\Auth\Authorizable;

class Agent extends BySiteConnectionModel implements AuthenticatableContract, AuthorizableContract
{
    use ExtraTrait, Authenticatable, Authorizable;

    const STATUS_FIELD = 'status';

    const USERNAME_FIELD = 'username';

    const PASSWORD_FIELD = 'password';

    const VALID_STATUS = 1;

    protected $table = 'agent';
}
