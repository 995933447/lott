<?php
namespace App\Utils\Cache\Keys;

final class KeysEnum
{
    const CAPTCHA = 'captcha_id:%s';

    const USER_LOGIN_TOKEN = 'token_user_id:%d';

    const LAST_FETCH_IN_OPENCAINET_AT = 'opencai.net_last_fetch_time';
}
