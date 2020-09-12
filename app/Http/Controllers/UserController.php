<?php
namespace App\Http\Controllers;

use App\Services\ServiceDispatcher;
use App\Services\User\Tasks\GetUserInfo;
use App\Utils\Formatters\End;
use App\Utils\Formatters\Money;
use Illuminate\Support\Facades\Auth;

class UserController
{
    public function getUserInfo()
    {
        $getUserInfoResult = ServiceDispatcher::dispatch(ServiceDispatcher::TASK_SERVICE, GetUserInfo::class);

        if ($getUserInfoResult->hasErrors()) {
            return End::toFailJson($getUserInfoResult->getErrors()->toArray(), $getUserInfoResult->getError(), End::INTERNAL_ERROR);
        }

        return End::toSuccessJson($getUserInfoResult->getData());
    }
}
