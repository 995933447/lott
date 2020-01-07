<?php
namespace App\Utils\Encryptor;

use Illuminate\Support\Facades\Hash;

class Encryptor
{
    public static function hashPassword(string $password): string
    {
        return Hash::make($password);
    }

    public static function checkPassword(string $password, string $passwordHash): bool
    {
        return Hash::check($password, $passwordHash);
    }

    public static function createJwtToken(?string $key, int $iat, int $expire, int $nbf, array $data)
    {
        return Jwt::createToken($key, $iat, $expire, $nbf, $data);
    }

    public static function decodeJwtToken(?string $key, string $token)
    {
        return Jwt::decodeToken($key, $token);
    }
}
