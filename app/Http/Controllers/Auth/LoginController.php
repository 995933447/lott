<?php
namespace App\Http\Controllers\Auth;

use App\Repositories\TokenRepository;
use App\Services\Auth\Tasks\CreateLoginToken;
use App\Services\Auth\Validators\Login;
use App\Services\ServiceDispatcher;
use App\Utils\Formatters\End;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController
{
    public function login(Request $request)
    {
        // 验证请求参数
        $validateResult = ServiceDispatcher::dispatch(ServiceDispatcher::VALIDATOR_SERVICE, Login::class, $request);

        if ($validateResult->hasErrors()) {
            return End::toFailJson($validateResult->getErrors()->toArray(), $validateResult->getError());
        }

        // 生成登录token
        $createTokenResult = ServiceDispatcher::dispatch(
            ServiceDispatcher::TASK_SERVICE,
            new CreateLoginToken(
                (bool) $request->input('is_agent'),
                (int) $validateResult->getData()->id,
                false
            )
        );

        if ($createTokenResult->hasErrors()) {
            return End::toFailJson($createTokenResult->getErrors()->toArray(), $createTokenResult->getError(), End::INTERNAL_ERROR);
        }

        return End::toSuccessJson(['token' => $createTokenResult->getData()]);
    }

    public function logout()
    {
        TokenRepository::deleteUserToken(Auth::id());
        return End::toSuccessJson();
    }
}
