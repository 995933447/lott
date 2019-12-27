<?php
namespace App\Repositories;

use App\Utils\Cache\Keys\KeysEnum;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class CaptchaValueRepository
{
    public static function find(string $id): ?string
    {
        return Cache::get(static::formatCaptchaId($id));
    }

    public static function save(string $id, string $value, Carbon $expire): bool
    {
        return Cache::put(static::formatCaptchaId($id), $value, $expire);
    }

    private static function formatCaptchaId(string $id): string
    {
        return sprintf(KeysEnum::CAPTCHA, $id);
    }
}
