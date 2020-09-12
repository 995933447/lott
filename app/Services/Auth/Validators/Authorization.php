<?php
namespace App\Services\Auth\Validators;

use App\Repositories\TokenRepository;
use App\Services\ServeResult;
use App\Services\ValidatorServiceContract;
use App\Utils\Encryptor\Encryptor;
use Illuminate\Http\Request;

class Authorization implements ValidatorServiceContract
{
    public function validate(Request $request): ServeResult
    {
        $tokenType = 'Bearer';
        $token = $request->header('Authorization');
        if (strpos($token, $tokenType) !== 0) {
            return  ServeResult::make(['无权访问']);
        }
        $token = trim(substr($token, strlen($tokenType)));

        if (!$payload = Encryptor::decodeJwtToken(null, $token)) {
            return ServeResult::make(['token已过期']);
        }

        if (!($correctToken = TokenRepository::findUserToken((int) $payload['uid'])) || $correctToken !== $token) {
            return ServeResult::make(['token已过期']);
        }

        if ((int) $request->input('is_agent') !== $payload['is_agent']) {
            return ServeResult::make(['无权访问']);
        }

        return ServeResult::make([], ['uid' => (int) $payload['uid'], 'is_agent' => (bool) $payload['is_agent']]);
    }
}
