<?php
namespace App\Repositories;

use App\Utils\Cache\Keys\KeysEnum;
use Illuminate\Support\Facades\Cache;

class TokenRepository
{
    public static function saveUserToken(int $userKey, string $token, \Carbon\Carbon $expire): bool
    {
        return Cache::put(static::formatUserTokenKey($userKey), $token, $expire);
    }

    public static function findUserToken(int $userKey)
    {
        return Cache::get(static::formatUserTokenKey($userKey));
    }

    public static function deleteUserToken(int $userKey): bool
    {
        return Cache::forget(static::formatUserTokenKey($userKey));
    }

    private static function formatUserTokenKey(int $userKey)
    {
        return sprintf(KeysEnum::USER_LOGIN_TOKEN, $userKey);
    }
}
