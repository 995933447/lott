<?php
namespace App\Services\Auth\Validators;

use App\Models\Agent;
use App\Repositories\CaptchaValueRepository;
use App\Services\ServeResult;
use App\Services\ValidatorServiceContract;
use App\Utils\Encryptor\Encryptor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class Login implements ValidatorServiceContract
{
    public function validate(Request $request): ServeResult
    {
        $baseValidator = Validator::make($request->all(), [
            'username' => 'bail|required',
            'password' => 'bail|required',
            'captcha' => [
                'bail',
                'required',
                function ($attribute, $value, $fail) {
                    if (!isset($value['id']) || !isset($value['value'])) {
                        return $fail('验证码错误!');
                    }
                    if (CaptchaValueRepository::find($value['id']) !== $value['value']) {
                        return $fail('验证码错误!');
                    }
                }
            ],
            'is_agent' => 'bail|required|integer'
        ], [
            'username.required' => '用户名必填',
            'password.required' => '用户名必填',
            'is_agent.required' => '请选择用户类型',
            'is_agent.integer' => 'is_agent参数非法'
        ]);
        if ($baseValidator->fails()) {
            return ServeResult::make($baseValidator->errors()->toArray());
        }

        $isAgent = $request->input('is_agent');
        $model = $isAgent? new Agent(): new User();
        $statusField = $isAgent? Agent::STATUS_FIELD : User::STATUS_FIELD;
        $validStatus = $isAgent? Agent::VALID_STATUS : User::VALID_STATUS;
        $usernameField = $isAgent? Agent::USERNAME_FIELD: User::USERNAME_FIELD;
        $passwordField = $isAgent? Agent::PASSWORD_FIELD: User::PASSWORD_FIELD;

        $user = $model->where($usernameField, $request->input('username'))->select($passwordField, $statusField, $model->getPrimaryKey())->first();
        if (is_null($user)) {
            return ServeResult::make(['用户不存在']);
        }

        if (!Encryptor::checkPassword($request->input('password'), $user->$passwordField)) {
            return ServeResult::make(['密码不正确']);
        }

        if ((int) $user->$statusField !== $validStatus) {
            return ServeResult::make(['用户已被冻结']);
        }

        return ServeResult::make([], $user);
    }
}
