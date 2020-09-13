<?php
namespace App\Utils\Formatters;

final class End
{
    // 成功状态码
    const SUCCESS_CODE = 200;
    // 验证失败状态码
    const VALIDATE_ERROR = 403;
    // 内部程序错误状态码
    const INTERNAL_ERROR = 500;
    // 认证失败状态码
    const UNAUTHORIZED_ERROR = 401;

    public static function toSuccessJson(array $data = [], string $msg = '',  int $statusCode = self::SUCCESS_CODE)
    {
        return json_encode([
            'code' => $statusCode,
            'data' => $data,
            'message' => $msg
        ]);
    }

    public static function toFailJson(array $data = [], string $msg = '',  int $statusCode = self::VALIDATE_ERROR)
    {
        return json_encode([
            'code' => $statusCode,
            'data' => $data,
            'message' => $msg
        ]);
    }
}
