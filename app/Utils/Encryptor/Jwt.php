<?php
namespace App\Utils\Encryptor;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;

class Jwt
{
    const JWT_KEY = 'suspn@)!*%^&*a';

    public static function getJwtDefaultSigner()
    {
        return new Sha256();
    }

    public static function getJwtDefaultKey(): string
    {
        return static::JWT_KEY;
    }

    public static function createToken(?string $key, int $iat, int $expire, int $nbf, array $data)
    {
        $builder = new Builder();
        $builder->issuedAt($iat) // token创建时间
        ->canOnlyBeUsedAfter($nbf) // 在此时间之前token无法使用
        ->expiresAt($expire); //token过期时间
        foreach ($data as $field => $value) {
            $builder->withClaim($field, $value);
        }
        return (string)$builder->getToken(Jwt::getJwtDefaultSigner(), new Key(is_null($key) ? static::getJwtDefaultKey() : $key));
    }

    public static function decodeToken(?string $key, $token)
    {
        $parse = (new Parser())->parse($token);
        //验证token合法性
        if (!$parse->verify(static::getJwtDefaultSigner(),  is_null($key) ? static::getJwtDefaultKey() : $key)) {
            return false;
        }
        //验证是否已经过期
        if ($parse->isExpired()) {
            return false;
        }
        foreach ($parse->getClaims() as $field => $value) {
            $payload[$field] = $parse->getClaim($field);
        }
        return $payload ?? [];
    }
}
