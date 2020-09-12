<?php
namespace App\Services\Rpc\Tasks\Clients;

class HttpRpcServerConfig
{
    const GET_REQUEST_METHOD = 'GET';
    const POST_REQUEST_METHOD = 'POST';
    const DELETE_REQUEST_METHOD = 'DELETE';
    const PUT_REQUEST_METHOD ='PUT';
    const OPTIONS_REQUEST_METHOD = 'OPTIONS';

    const URI_PARAM_REPLACEMENT = '$param';

    public static $routes = [
        'http-rpc/bind-basic-info' => [
            self::POST_REQUEST_METHOD => 'http://boya.rpc.com/index.php/bind-basic-info',
        ],
        'http-rpc/bind-pay-password' => [
            self::POST_REQUEST_METHOD => 'http://boya.rpc.com/index.php/bind-pay-password',
        ],
        'http-rpc/change-pay-password' => [
            self::POST_REQUEST_METHOD => 'http://boya.rpc.com/index.php/change-pay-password'
        ],
        'http-rpc/bank-select-list' => [
            self::GET_REQUEST_METHOD => 'http://boya.rpc.com/index.php/bank-select-list',
        ],
        'http-rpc/bank-list' => [
            self::GET_REQUEST_METHOD => 'http://boya.rpc.com/index.php/bank-list',
        ],
        'http-rpc/add-bank' => [
            self::POST_REQUEST_METHOD => 'http://boya.rpc.com/index.php/add-bank',
        ],
        'http-rpc/wap-add-bank' => [
            self::POST_REQUEST_METHOD => 'http://boya.rpc.com/index.php/wap-add-bank',
        ],
        'http-rpc/check-money-password' => [
            self::POST_REQUEST_METHOD => 'http://boya.rpc.com/index.php/check-money-password',
        ],
        'http-rpc/delete-bank' => [
            self::POST_REQUEST_METHOD => 'http://boya.rpc.com/index.php/delete-bank'
        ],
        'http-rpc/set-default-bank' => [
            self::POST_REQUEST_METHOD => 'http://boya.rpc.com/index.php/set-default-bank'
        ],
        'http-rpc/withdraw' => [
            self::POST_REQUEST_METHOD => 'http://boya.rpc.com/index.php/withdraw'
        ],
        'http-rpc/payment-list' => [
            self::GET_REQUEST_METHOD => 'http://boya.rpc.com/index.php/payment-list'
        ],
        'http-rpc/transfer-list' => [
            self::GET_REQUEST_METHOD => 'http://boya.rpc.com/index.php/transfer-list'
        ],
        'http-rpc/transfer-order' => [
            self::POST_REQUEST_METHOD => 'http://boya.rpc.com/index.php/transfer-order'
        ],
        'http-rpc/login' => [
            self::POST_REQUEST_METHOD => 'http://boya.rpc.com/index.php/api/frontend/login'
        ],
        'http-rpc/balance-log' => [
            self::GET_REQUEST_METHOD => 'http://boya.rpc.com/index.php/balance-log'
        ],
        'http-rpc/backend/push-risk-message' => [
            self::POST_REQUEST_METHOD => 'http://boya.rpc.com/index.php/backend/push-risk-message'
        ]
    ];
}
