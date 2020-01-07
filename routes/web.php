<?php

/*
|--------------------------------------------------------------------------
| Application HttpRpc
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use App\Repositories\BetOrderRepository;
use App\Repositories\IssueRepository;
use App\Repositories\LotteryRepository;
use App\Repositories\OrderTransactionLogRepository;
use App\Services\Auth\Tasks\CreateCaptcha;
use App\Services\Rpc\Tasks\Clients\HttpRpc;
use App\Utils\Formatters\End;
use App\Services\ServiceDispatcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/test', function () {
//    echo Hash::make('abc123456');
});

/**
 * @api {get} /captcha 获取验证码
 * @apiGroup 用户验证
 * @apiName 验证码
 * /captcha
 * @apiSuccess (Success) {number} status 状态码
 * @apiSuccess (Success) {string} msg  消息
 * @apiSuccess (Success) {object} data 数据
 * @apiSuccess (Success) {number} data.id 数据id
 * @apiSuccess (Success) {string} data.captcha 验证码
 * @apiSuccess (Success)Example Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *        "status": 200,
 *        "data":{
 *           "captcha": "data:image/jpeg;base64,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",
 *           "id": "5de785bae7ab0"
 *         },
 *        "msg": "请求成功"
 *     }
 */
$router->get('/captcha', function () {
    $getCaptchaResult = ServiceDispatcher::dispatch(ServiceDispatcher::TASK_SERVICE, CreateCaptcha::class);
    if ($getCaptchaResult->hasErrors()) {
        return End::toFailJson($getCaptchaResult->getErrors()->toArray(), $getCaptchaResult->getError(), End::INTERNAL_ERROR);
    }
    return End::toSuccessJson($getCaptchaResult->getData());
});

/**
 * @api {post} /login 登录
 * @apiGroup Login
 * @apiName 登录
 * @apiParamExample 请求样例
 * /login
 *
 * @apiParam {string} username 用户名
 * @apiParam {string} password 密码
 * @apiParam {int} is_agent,是否代理,0否,1是
 * @apiParam {string} captcha[id],验证码唯一id,如：5deda44ed73f4
 * @apiParam {string} captcha[value],验证码
 *
 * @apiSuccess (Success) {number} status 状态码
 * @apiSuccess (Success) {string} msg  消息
 * @apiSuccess (Success) {object} data 数据
 * @apiSuccess (Success)Example Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *        "status": 200,
 *        "data": [
 *           {
 *           },
 *        ],
 *        "msg": "请求成功"
 *     }
 */
$router->post('/login', 'Auth\LoginController@login');

$router->group(['middleware' => 'auth'], function () use ($router) {
    /**
     * @api {get} /user-info 获取用户信息
     * @apiGroup User
     * @apiName 获取用户信息
     * /user-info
     * @apiSuccess (Success) {number} status 状态码
     * @apiSuccess (Success) {string} msg  消息
     * @apiSuccess (Success) {object} data 数据
     * @apiSuccess (Success)Example Success-Response:
     *     HTTP/1.1 200 OK
     *   {
     *   "code": 200,
     *       "data": {
     *       "id": 368,
     *       "site_id": "1",
     *       "grade_id": "1",
     *       "level_id": "1",
     *       "agent_id": "1",
     *       "username": "f505b7",
     *       "status": "1",
     *       "register_ip": "127.0.0.1",
     *       "register_time": "2019-11-18 02:41:58",
     *       "register_url": "",
     *       "register_device": "1",
     *       "last_login_ip": "127.0.0.1",
     *       "last_login_time": "2019-11-19 01:59:33",
     *       "last_login_address": "内网IP",
     *       "realname": "",
     *       "mobile": "",
     *       "email": "",
     *       "qq": null,
     *       "birthday": null,
     *       "sex": "0",
     *       "is_online": "0",
     *       "focus_level": "1",
     *       "balance_status": "1",
     *       "safe_question": "",
     *       "safe_answer": "",
     *       "show_beginner_guide": "1",
     *        "delete_at": "0",
     *        "remark": "",
     *        "created_at": "2019-11-18 02:41:58",
     *        "updated_at": "2019-11-19 01:59:33",
     *        "agent_name": "agent001",
     *        "balance": "10772.00"
     *  },
     *  "message": ""
     *}
     */
    $router->get('/user-info', 'UserController@getUserInfo');
    /**
     * @api {delete} /logout 用户等处
     * @apiGroup User
     * @apiName 用户登出
     * /logout
     * @apiPermission token
     * @apiSuccess (Success) {number} status 状态码
     * @apiSuccess (Success) {string} msg  消息
     * @apiSuccess (Success) {object} data 数据
     * @apiSuccess (Success)Example Success-Response:
     *     HTTP/1.1 200 OK
     *   {
     *   "code": 200,
     *   "data": [],
     *  "message": ""
     *}
     */
    $router->delete('/logout', 'Auth\LoginController@logout');

    /**
     * @api {put} /modify-password 修改密码
     * @apiGroup User
     * @apiName 修改密码
     * @apiPermission token
     * @apiParamExample 请求样例
     * /modify-password
     *
     * @apiParam {string} password 新密码
     * @apiParam {string} password_comfirmation 确认新密码
     *
     * @apiSuccess (Success) {number} status 状态码
     * @apiSuccess (Success) {string} msg  消息
     * @apiSuccess (Success) {object} data 数据
     * @apiSuccess (Success) {number} data.id 数据id
     * @apiSuccess (Success)Example Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *        "status": 200,
     *        "data: [
     *        ],
     *        "msg": "请求成功"
     *     }
     */
    $router->put('/modify-password', 'Auth\ModifyPasswordController@modifyPassword');

    /**
     * @api {post} /order 投注,需先登录并携带bearer token
     * @apiGroup BetOrder
     * @apiName 投注
     * @apiPermission token
     * @apiParamExample 请求样例
     * /order
     *
     * @apiParam {string} bet_type_code 投注类型code,可用code有hezhi,putong,xingyun,buzhong,lianying,fushi
     * @apiParam {string} lottery_code 彩票code,可用code有gxkl10,gxk3
     * @apiParam {float} odds 如：0.95
     * @apiParam {float} money 如：12.6
     * @apiParam {int} face 玩法面盘:0.x盘,y盘
     * @apiParam {string} issue 奖期,如:34567890
     * @apiParam {array} codes 奖期,如:和值投注方式['合值:号码:单'],三不中投注方式['三不中:号码:1,3,5'],特殊的连赢投注方式为['三连赢:合值:单','三连赢:合值:大','三连赢:平码:大']
     *
     * @apiSuccess (Success) {number} status 状态码
     * @apiSuccess (Success) {string} msg  消息
     * @apiSuccess (Success) {object} data 数据
     * @apiSuccess (Success) {number} data.id 数据id
     * @apiSuccess (Success)Example Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *        "status": 200,
     *        "data: [
     *        ],
     *        "msg": "请求成功"
     *     }
     */
    $router->post('/order', 'BetOrderController@commit');

    /**
     * @api {delete} /order/{orderId}
     * @apiGroup BetOrder
     * @apiName 取消投注
     * @apiPermission token
     * @apiParamExample 请求样例
     * /order/{orderId}
     *
     * @apiSuccess (Success) {number} status 状态码
     * @apiSuccess (Success) {string} msg  消息
     * @apiSuccess (Success) {object} data 数据
     * @apiSuccess (Success) {number} data.id 数据id
     * @apiSuccess (Success)Example Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *        "status": 200,
     *        "data: [
     *        ],
     *        "msg": "请求成功"
     *     }
     */
    $router->delete('/order/{orderId}', 'BetOrderController@cancel');


    /**
     * @api {get} /orders/page-size/1 获取历史注单分页
     * @apiGroup BetOrder
     * @apiName 获取历史注单分页
     * /orders/page-size/{pageSize}
     * @apiPermission token
     * @apiParam {array} lottery_codes 可选,彩票code,如：[gxk3],多个传多个,如:[gxk3, gxkl10],不传则返回所有彩票分类下的彩种
     * @apiParam {array} datetime 日期时间,如["2017-01-01 13:00:00", "2017-01-02 13:00:00"]代表2017-01-01 13:00:00到2017-01-02 13:00:00
     * @apiParam {number} status 状态,0.投注成功,1.结算中,2.已结算,3.取消下注
     * @apiSuccess (Success) {number} status 状态码
     * @apiSuccess (Success) {string} msg  消息
     * @apiSuccess (Success) {object} data 数据
     * @apiSuccess (Success)Example Success-Response:
     *     HTTP/1.1 200 OK
     *  {
     *      "code": 200,
     *      "data": {
     *          "current_page": 1,
     *          "data": [],
     *          "first_page_url": "http://192.168.56.128/orders/page-size/10?page=1",
     *          "from": null,
     *          "last_page": 1,
     *          "last_page_url": "http://192.168.56.128/orders/page-size/10?page=1",
     *          "next_page_url": null,
     *          "path": "http://192.168.56.128/orders/page-size/10",
     *          "per_page": 10,
     *          "prev_page_url": null,
     *          "to": null,
     *          "total": 0
     *      },
     *      "message": ""
     *  }
     */
    $router->get('/orders/page-size/{pageSize:[0-9]+}', function (Request $request, $pageSize) {
       return End::toSuccessJson(
            BetOrderRepository::paginateByUser(
                Auth::id(),
                $request->input('lottery_codes')?: [],
                $request->input('datetime')?: [],
                $request->input('status'),
                (int) $pageSize
            )
       );
    });

    // 根据奖期获取订单
    $router->get('/orders/issue/{issue}/page-size/{pageSize:[0-9]+}', function ($issue, $pageSize) {
        return End::toSuccessJson(BetOrderRepository::paginateByIssue(Auth::id(), $issue, $pageSize));
    });

    /**
     * @api {get} /order-logs/page-size/50 获取游戏账变
     * @apiGroup BetOrder
     * @apiName 获取游戏账变
     * /order-logs/page-size/{pageSize}
     * @apiPermission token
     * @apiParam {array} type 可选,类型,不传代表获取全部类型.交易类型,0.投注,1.派彩,2.取消订单
     * @apiParam {array} datetime 日期时间,如["2017-01-01 13:00:00", "2017-01-02 13:00:00"]代表2017-01-01 13:00:00到2017-01-02 13:00:00
     * @apiSuccess (Success) {number} code 状态码
     * @apiSuccess (Success) {string} msg  消息
     * @apiSuccess (Success) {object} data 数据
     * @apiSuccess (Success)Example Success-Response:
     *     HTTP/1.1 200 OK
     *  {
     *      "code": 200,
     *      "data": {
     *          "current_page": 1,
     *          "data": [],
     *          "first_page_url": "http://192.168.56.128/orders/page-size/10?page=1",
     *          "from": null,
     *          "last_page": 1,
     *          "last_page_url": "http://192.168.56.128/orders/page-size/10?page=1",
     *          "next_page_url": null,
     *          "path": "http://192.168.56.128/orders/page-size/10",
     *          "per_page": 10,
     *          "prev_page_url": null,
     *          "to": null,
     *          "total": 0
     *      },
     *      "message": ""
     *  }
     */
    $router->get('/order-logs/page-size/{pageSize:[0-9]+}', function (Request $request, $pageSize) {
        return End::toSuccessJson(
            OrderTransactionLogRepository::paginateByUser(
                Auth::id(), $request->input('type'),
                $request->input('datetime')?: [],
                $pageSize
            )
        );
    });

    // rpc远程调用
    $router->group(['prefix' => 'http-rpc'], function () use($router) {
        // rpc调用服务
        $httpRpcHandler = function (Request $request) {
            $argv = func_get_args();
            array_shift($argv);

            return ServiceDispatcher::dispatch(
                ServiceDispatcher::TASK_SERVICE,
                new HttpRpc($request, $request->path(), $argv)
            )->getData();
        };

        // 账号信息
        $router->post('bind-basic-info', $httpRpcHandler);  // 实名认证
        $router->post('bind-pay-password', $httpRpcHandler);  // 绑定资金密码
        $router->post('change-pay-password', $httpRpcHandler);  // 修改资金密码

        // 资金管理 - 提现
        $router->get('bank-list', $httpRpcHandler); // 会员银行账号列表
        $router->post('add-bank', $httpRpcHandler); // 添加会员银行账号
        $router->post('wap-add-bank', $httpRpcHandler); // 添加会员银行账号(手机端用)
        $router->post('check-money-password', $httpRpcHandler); // 验证资金密码
        $router->post('delete-bank', $httpRpcHandler); // 删除会员银行账号
        $router->post('set-default-bank', $httpRpcHandler); // 设置会员默认银行账号
        $router->post('withdraw', $httpRpcHandler);   // 申请提款

        // 资金管理 - 充值
        $router->get('payment-list', $httpRpcHandler);   // 获取可用的在线支付列表
        $router->get('transfer-list', $httpRpcHandler); // 获取可用的转账汇款列表
        $router->post('transfer-order', $httpRpcHandler); // 转账汇款提交下单

        $router->get('bank-select-list', $httpRpcHandler);

        $router->post('login', $httpRpcHandler);
        $router->get('balance-log', $httpRpcHandler);
    });
});

/**
 * @api {get} /lottery-categories 获取彩票分类
 * @apiGroup Lotteries
 * @apiName 获取彩票分类
 * /lottery-categories
 * @apiSuccess (Success) {number} status 状态码
 * @apiSuccess (Success) {string} msg  消息
 * @apiSuccess (Success) {object} data 数据
 * @apiSuccess (Success) {number} data.index.id 分类id
 * @apiSuccess (Success) {string} data.index.name 分类名称
 * @apiSuccess (Success) {string} data.index.icon 图标
 * @apiSuccess (Success) {string} data.index.description 分类描述
 * @apiSuccess (Success) {string} data.index.status 分类状态
 * @apiSuccess (Success) {string} data.index.remark 分类描述
 * @apiSuccess (Success) {number} data.index.lottery_num 彩票游戏数量
 * @apiSuccess (Success) {number} data.index.sort 排序
 * @apiSuccess (Success)Example Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *        "status": 200,
 *        "data": [
 *            {
 *               "id": 1,
 *               "name": "快乐十分",
 *               "icon": "",
 *               "description": null,
 *               "status": "1",
 *               "remark": "",
 *               "lottery_num": "1",
 *               "created_at": "2019-12-03 02:45:27",
 *               "updated_at": "2019-12-03 02:45:32",
 *               "sort": "0"
 *           },
 *           {
 *               "id": 2,
 *               "name": "快三",
 *               "icon": "",
 *               "description": null,
 *               "status": "1",
 *               "remark": "",
 *               "lottery_num": "0",
 *               "created_at": "2019-12-03 02:45:51",
 *               "updated_at": "2019-12-03 02:45:53",
 *               "sort": "0"
 *           }
 *           ],
 *        "msg": "请求成功"
 *     }
 */
$router->get('/lottery-categories', function () {
   return End::toSuccessJson(LotteryRepository::getCategories());
});

/**
 * @api {get} /lotteries/categories?category_ids[]=1&category_ids[]=2 根据分类获取彩票
 * @apiGroup Lotteries
 * @apiName 获取彩票分类
 * /lotteries/categories
 * @apiParam {array} category_ids 可选,彩票分类id,如：[1],多个传多个,如:[1, 2],不传则返回所有彩票分类下的彩种
 * @apiSuccess (Success) {number} status 状态码
 * @apiSuccess (Success) {string} msg  消息
 * @apiSuccess (Success) {object} data 数据
 * @apiSuccess (Success) {number} data.index.id 分类id
 * @apiSuccess (Success) {string} data.index.name 分类名称
 * @apiSuccess (Success) {string} data.index.icon 图标
 * @apiSuccess (Success) {string} data.index.description 分类描述
 * @apiSuccess (Success) {string} data.index.status 分类状态
 * @apiSuccess (Success) {string} data.index.remark 分类描述
 * @apiSuccess (Success) {number} data.index.lottery_num 彩票游戏数量
 * @apiSuccess (Success) {number} data.index.sort 排序
 * @apiSuccess (Success)Example Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *        "status": 200,
 *        "data": [
 *          {
 *              "id": 1,
 *              "name": "快乐十分",
 *              "icon": "",
 *              "description": null,
 *              "status": "1",
 *              "remark": "",
 *              "lottery_num": "1",
 *              "created_at": "2019-12-03 02:45:27",
 *              "updated_at": "2019-12-03 02:45:32",
 *              "sort": "0",
 *                  "lottery": [
 *                          {
 *                              "id": 1,
 *                              "name": "广西快乐十分",
 *                              "code": "gxkl10",
 *                               "icon": "",
 *                               "lottery_category_id": "1",
 *                               "status": "1",
 *                               "description": null,
 *                               "limit_time": "20",
 *                               "issue_num_day": "40",
 *                               "is_official": "1",
 *                               "created_at": "2019-12-03 02:46:57",
 *                               "updated_at": "2019-12-03 02:46:59",
 *                               "sort": "0",
 *                               "bet_type": [
 *                                   {
 *                                       "id": 1,
 *                                       "name": "合值",
 *                                       "code": "hezhi",
 *                                       "created_at": "2019-12-03 05:05:56",
 *                                       "updated_at": "2019-12-03 05:05:59",
 *                                       "pivot": {
 *                                           "lottery_id": "1",
 *                                           "bet_type_id": "1",
 *                                           "status": "1",
 *                                           "play_face": "0"
 *                                       }
 *                                   },
 *                                   {
 *                                       "id": 2,
 *                                       "name": "普通投注",
 *                                       "code": "putong",
 *                                       "created_at": "2019-12-03 05:07:31",
 *                                       "updated_at": "2019-12-03 05:07:36",
 *                                       "pivot": {
 *                                           "lottery_id": "1",
 *                                           "bet_type_id": "2",
 *                                           "status": "1",
 *                                           "play_face": "0"
 *                                       }
 *                                   },
 *                       ]
 *                   }
 *              ],
 *        "msg": "请求成功"
 *     }
 */
$router->get('/lotteries/categories', function (Request $request) {
   return End::toSuccessJson(LotteryRepository::getByCategories($request->input('category_ids')?? []));
});

/**
 * @api {get} /lotteries?ids[]=1&ids[]=2 获取彩种
 * @apiGroup Lotteries
 * @apiName 获取彩票分类
 * /lotteries
 * @apiParam {array} ids 可选,彩票id,如：[1],多个传多个,如:[1, 2],不传则返回所有彩票
 * @apiSuccess (Success) {number} status 状态码
 * @apiSuccess (Success) {string} msg  消息
 * @apiSuccess (Success) {object} data 数据
 * @apiSuccess (Success) {number} data.index.id 分类id
 * @apiSuccess (Success) {string} data.index.name 分类名称
 * @apiSuccess (Success) {string} data.index.icon 图标
 * @apiSuccess (Success) {string} data.index.description 分类描述
 * @apiSuccess (Success) {string} data.index.status 分类状态
 * @apiSuccess (Success) {string} data.index.remark 分类描述
 * @apiSuccess (Success) {number} data.index.lottery_num 彩票游戏数量
 * @apiSuccess (Success) {number} data.index.sort 排序
 * @apiSuccess (Success)Example Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *        "status": 200,
 *        "data":{
 *                  "id": 1,
 *                  "name": "广西快乐十分",
 *                  "code": "gxkl10",
 *                  "icon": "",
 *                  "lottery_category_id": "1",
 *                  "status": "1",
 *                  "description": null,
 *                  "limit_time": "20",
 *                  "issue_num_day": "40",
 *                  "is_official": "1",
 *                  "created_at": "2019-12-03 02:46:57",
 *                  "updated_at": "2019-12-03 02:46:59",
 *                  "sort": "0",
 *                  "bet_type": [
 *                       {
 *                           "id": 1,
 *                           "name": "合值",
 *                           "code": "hezhi",
 *                           "created_at": "2019-12-03 05:05:56",
 *                           "updated_at": "2019-12-03 05:05:59",
 *                               "pivot": {
 *                                   "lottery_id": "1",
 *                                   "bet_type_id": "1",
 *                                   "status": "1",
 *                                   "play_face": "0"
 *                                }
 *                      },
 *                      {
 *                         "id": 2,
 *                         "name": "普通投注",
 *                         "code": "putong",
 *                         "created_at": "2019-12-03 05:07:31",
 *                         "updated_at": "2019-12-03 05:07:36",
 *                              "pivot": {
 *                                   "lottery_id": "1",
 *                                   "bet_type_id": "2",
 *                                   "status": "1",
 *                                   "play_face": "0"
 *                              }
 *                      },
 *                ]
 *        },
 *        "msg": "请求成功"
 *     }
 */
$router->get('/lotteries', function (Request $request) {
    return End::toSuccessJson(LotteryRepository::get($request->input('ids')?? []));
});

/**
 * @api {get} /lottery-issues/1/page-size/50 分页获取彩种将期历史
 * @apiGroup Lotteries
 * @apiName 分页根据彩种获取将期
 * /lottery-issues/{lotteryId:[0-9]+}/page-size/{pageSize:[0-9]+}
 * @apiParam {array} lottery_id 必选,彩票id
 * @apiParam {int} status 必选,状态,0未开彩,1已开彩,2.开彩中,不传代表获取所有
 * @apiParam {int} page-size 必选,每页条数
 * @apiSuccess (Success) {number} status 状态码
 * @apiSuccess (Success) {string} msg  消息
 * @apiSuccess (Success) {object} data 数据
 * @apiSuccess (Success) {number} data.index.id 数据id
 * @apiSuccess (Success) {number} data.index.issue 奖期
 * @apiSuccess (Success) {string} data.index.lottery_id 彩票id
 * @apiSuccess (Success) {string} data.index.started_at 开始时间
 * @apiSuccess (Success) {string} data.index.ended_at 结束时间
 * @apiSuccess (Success) {string} data.index.status 0未开彩,1已开彩,2.开彩中
 * @apiSuccess (Success) {string} data.index.reward_codes 开奖号码
 * @apiSuccess (Success) {number} data.index.total_bet_money 所有用户投注总额
 * @apiSuccess (Success) {number} data.index.total_reward_money 总共派发彩金
 * @apiSuccess (Success) {number} data.index.total_bet_num 总投注人数
 * @apiSuccess (Success) {number} data.index.total_reward_num 中奖人数
 * @apiSuccess (Success)Example Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *       "code": 200,
 *       "data": {
 *           "current_page": 1,
 *           "data": [
 *               {
 *                   "id": 257,
 *                   "issue": "201932911",
 *                   "lottery_id": "1",
 *                   "started_at": "1575866103",
 *                   "ended_at": "1575866803",
 *                   "stop_bet_at": "1575866773",
 *                   "status": "0",
 *                   "reward_codes": null,
 *                   "total_bet_money": "0",
 *                   "total_reward_money": "0",
 *                   "total_bet_num": "0",
 *                   "total_reward_num": "0",
 *                   "created_at": "2019-12-09 04:35:03",
 *                   "updated_at": "2019-12-09 04:35:03"
 *               },
 *               {
 *                   "id": 255,
 *                   "issue": "201932910",
 *                   "lottery_id": "1",
 *                   "started_at": "1575864782",
 *                   "ended_at": "1575865380",
 *                   "stop_bet_at": "1575865350",
 *                   "status": "1",
 *                   "reward_codes": [
 *                       "05",
 *                       "03",
 *                       "09",
 *                       "01",
 *                       "04"
 *                       ],
 *               "total_bet_money": "0",
 *               "total_reward_money": "0",
 *               "total_bet_num": "0",
 *               "total_reward_num": "0",
 *               "created_at": "2019-12-09 04:13:02",
 *               "updated_at": "2019-12-09 04:35:11"
 *               }
 *           ],
 *           "first_page_url": "http://192.168.56.128/lottery-issues/1/page-size/2?page=1",
 *           "from": 1,
 *           "last_page": 17,
 *           "last_page_url": "http://192.168.56.128/lottery-issues/1/page-size/2?page=17",
 *           "next_page_url": "http://192.168.56.128/lottery-issues/1/page-size/2?page=2",
 *           "path": "http://192.168.56.128/lottery-issues/1/page-size/2",
 *           "per_page": 2,
 *           "prev_page_url": null,
 *           "to": 2,
 *           "total": 34
 *       },
 *       "message": ""
 *       }
 */
$router->get('/lottery-issues/{lotteryId:[0-9]+}/page-size/{pageSize:[0-9]+}', function (Request $request, $lotteryId, $pageSize) {
    return End::toSuccessJson(
        LotteryRepository::issuesPaginate(
            $lotteryId,
            $request->input('datetime')?: [],
            $request->input('status'),
            (int) $pageSize
        )
    );
});

/**
 * @api {get} /lottery-issues/1 根据彩种获取将期
 * @apiGroup Lotteries
 * @apiName 根据彩种获取将期
 * /lottery-issues/{lottery_id}
 * @apiParam {array} lottery_id 必选,彩票id
 * @apiParam {int} status 可选,状态,0未开彩,1已开彩,2.开彩中,不传代表获取所有
 * @apiParam {int} limit 可选,获取条数
 * @apiParam {int} offset 可选,从第几条开始获取
 * @apiSuccess (Success) {number} status 状态码
 * @apiSuccess (Success) {string} msg  消息
 * @apiSuccess (Success) {object} data 数据
 * @apiSuccess (Success) {number} data.index.id 数据id
 * @apiSuccess (Success) {number} data.index.issue 奖期
 * @apiSuccess (Success) {string} data.index.lottery_id 彩票id
 * @apiSuccess (Success) {string} data.index.started_at 开始时间
 * @apiSuccess (Success) {string} data.index.ended_at 结束时间
 * @apiSuccess (Success) {string} data.index.status 0未开彩,1已开彩,2.开彩中
 * @apiSuccess (Success) {string} data.index.reward_codes 开奖号码
 * @apiSuccess (Success) {number} data.index.total_bet_money 所有用户投注总额
 * @apiSuccess (Success) {number} data.index.total_reward_money 总共派发彩金
 * @apiSuccess (Success) {number} data.index.total_bet_num 总投注人数
 * @apiSuccess (Success) {number} data.index.total_reward_num 中奖人数
 * @apiSuccess (Success)Example Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *        "status": 200,
 *        "data":[
 *           {
 *               "id": 1,
 *               "issue": "19120427 ",
 *               "lottery_id": "1",
 *               "started_at": "1575452920",
 *               "ended_at": "1575594763",
 *               "stop_bet_at": "1575594703",
 *               "status": "1",
 *               "reward_codes": null,
 *               "total_bet_money": "0",
 *               "total_reward_money": "0",
 *               "total_bet_num": "0",
 *               "total_reward_num": "0",
 *               "created_at": "2019-12-04 09:49:18",
 *               "updated_at": "2019-12-04 09:49:21"
 *           }
 *           ],
 *        "msg": "请求成功"
 *     }
 */
$router->get('/lottery-issues/{lotteryId:[0-9]+}', function (Request $request, $lotteryId) {
    return End::toSuccessJson(
        LotteryRepository::getIssues(
            $lotteryId,
            $request->input('datetime')?: [],
            $request->input('status'),
            (int) $request->input('limit'),
            (int) $request->input('offset')
        )
    );
});

/**
 * @api {get} /issue/{issue_id} 获取某期奖期详情
 * @apiGroup Lotteries
 * @apiName 根据彩种获取将期
 * /issues/1
 * @apiParam {array} lottery_id 必选,彩票id
 * @apiSuccess (Success) {number} status 状态码
 * @apiSuccess (Success) {string} msg  消息
 * @apiSuccess (Success) {object} data 数据
 * @apiSuccess (Success) {number} data.id 数据id
 * @apiSuccess (Success) {number} data.issue 奖期
 * @apiSuccess (Success) {string} data.lottery_id 彩票id
 * @apiSuccess (Success) {string} data.started_at 开始时间
 * @apiSuccess (Success) {string} data.ended_at 结束时间
 * @apiSuccess (Success) {string} data.status 0未开彩,1已开彩,2.开彩中
 * @apiSuccess (Success) {string} data.reward_codes 开奖号码
 * @apiSuccess (Success) {number} data.total_bet_money 所有用户投注总额
 * @apiSuccess (Success) {number} data.total_reward_money 总共派发彩金
 * @apiSuccess (Success) {number} data.total_bet_num 总投注人数
 * @apiSuccess (Success) {number} data.total_reward_num 中奖人数
 * @apiSuccess (Success)Example Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *        "status": 200,
 *        "data":{
 *               "id": 1,
 *               "issue": "19120427 ",
 *               "lottery_id": "1",
 *               "started_at": "1575452920",
 *               "ended_at": "1575594763",
 *               "stop_bet_at": "1575594703",
 *               "status": "1",
 *               "reward_codes": null,
 *               "total_bet_money": "0",
 *               "total_reward_money": "0",
 *               "total_bet_num": "0",
 *               "total_reward_num": "0",
 *               "created_at": "2019-12-04 09:49:18",
 *               "updated_at": "2019-12-04 09:49:21"
 *         },
 *        "msg": "请求成功"
 *     }
 */
$router->get('/issue/{issueId:[0-9]+}', function ($issueId) {
    return End::toSuccessJson(IssueRepository::find((int) $issueId));
});


/**
 * @api {get} /issue/{issue}获取某期奖期详情
 * @apiGroup Lotteries
 * @apiName 根据彩种获取将期
 * /issues/2167856734
 * @apiParam {array} lottery_id 必选,彩票id
 * @apiSuccess (Success) {number} status 状态码
 * @apiSuccess (Success) {string} msg  消息
 * @apiSuccess (Success) {object} data 数据
 * @apiSuccess (Success) {number} data.id 数据id
 * @apiSuccess (Success) {number} data.issue 奖期
 * @apiSuccess (Success) {string} data.lottery_id 彩票id
 * @apiSuccess (Success) {string} data.started_at 开始时间
 * @apiSuccess (Success) {string} data.ended_at 结束时间
 * @apiSuccess (Success) {string} data.status 0未开彩,1已开彩,2.开彩中
 * @apiSuccess (Success) {string} data.reward_codes 开奖号码
 * @apiSuccess (Success) {number} data.total_bet_money 所有用户投注总额
 * @apiSuccess (Success) {number} data.total_reward_money 总共派发彩金
 * @apiSuccess (Success) {number} data.total_bet_num 总投注人数
 * @apiSuccess (Success) {number} data.total_reward_num 中奖人数
 * @apiSuccess (Success)Example Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *        "status": 200,
 *        "data":{
 *               "id": 1,
 *               "issue": "19120427 ",
 *               "lottery_id": "1",
 *               "started_at": "1575452920",
 *               "ended_at": "1575594763",
 *               "stop_bet_at": "1575594703",
 *               "status": "1",
 *               "reward_codes": null,
 *               "total_bet_money": "0",
 *               "total_reward_money": "0",
 *               "total_bet_num": "0",
 *               "total_reward_num": "0",
 *               "created_at": "2019-12-04 09:49:18",
 *               "updated_at": "2019-12-04 09:49:21"
 *         },
 *        "msg": "请求成功"
 *     }
 */
$router->get('/issues/{issue}', function ($issue) {
    return End::toSuccessJson(IssueRepository::getByIssue($issue));
});
