<?php
namespace App\Services\Auth\Tasks;


namespace App\Services\Auth\Tasks;

use App\Repositories\TokenRepository;
use App\Services\ServeResult;
use App\Utils\Encryptor\Encryptor;
use Illuminate\Support\Carbon;
use App\Services\TaskServiceContract;

class CreateLoginToken implements TaskServiceContract
{
    private $userId;
    private $isRememberMe;
    private $isAgent;

    public function __construct(bool $isAgent, int $userId, int $isRememberMe)
    {
        $this->isAgent = $isAgent;
        $this->userId = $userId;
        $this->isRememberMe = $isRememberMe;
    }

    public function run(): ServeResult
    {
        $this->isRememberMe ? $expire = 3600 * 24 * 365 : $expire = 3600 * 24 * 7;
        $payload = ['uid' => $this->userId, 'is_agent' => (int) $this->isAgent];

        if (!$token = Encryptor::createJwtToken(null, $now = time(), $now + $expire, $now, $payload))
            return ServeResult::make(['生成token失败']);

        if (!TokenRepository::saveUserToken($this->userId, $token, Carbon::now()->addMinutes($expire / 60)))
            return ServeResult::make(['保存token失败']);

        return ServeResult::make([], $token);
    }
}
