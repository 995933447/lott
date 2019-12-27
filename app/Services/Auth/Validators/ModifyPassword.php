<?php
namespace App\Services\Auth\Validators;

use App\Services\ServeResult;
use App\Services\ValidatorServiceContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ModifyPassword implements ValidatorServiceContract
{
    public function validate(Request $request): ServeResult
    {
        $baseValidator = Validator::make($request->all(), [
            'password' => 'required|confirmed'
        ], [
            'password.required' => '请输入新密码',
            'password.confirmed' => '请确认新密码'
        ]);
        if ($baseValidator->fails()) {
            return ServeResult::make($baseValidator->errors()->toArray());
        }

        return ServeResult::make();
    }
}
