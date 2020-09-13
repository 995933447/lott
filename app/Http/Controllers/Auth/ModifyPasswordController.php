<?php
namespace App\Http\Controllers\Auth;

use App\Services\Auth\Validators\ModifyPassword;
use App\Services\ServeResult;
use App\Services\ServiceDispatcher;
use App\Utils\Formatters\End;
use Illuminate\Http\Request;

class ModifyPasswordController
{
    public function modifyPassword(Request $request)
    {
        $validateResult = ServiceDispatcher::dispatch(ServiceDispatcher::VALIDATOR_SERVICE, ModifyPassword::class, $request);

        if ($validateResult->hasErrors()) {
            return End::toFailJson($validateResult->getErrors()->toArray(), $validateResult->getError());
        }

        $modifyPasswordResult = ServiceDispatcher::dispatch(ServiceDispatcher::TASK_SERVICE, \App\Services\Auth\Tasks\ModifyPassword::class, $request->input('password'));

        if ($modifyPasswordResult->hasErrors()) {
            return End::toFailJson($modifyPasswordResult->getErrors()->toArray(), $modifyPasswordResult->getError(), End::INTERNAL_ERROR);
        }

        return End::toSuccessJson();
    }
}
