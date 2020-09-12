define({ "api": [
  {
    "type": "post",
    "url": "/order",
    "title": "投注,需先登录并携带bearer token",
    "group": "BetOrder",
    "name": "__",
    "permission": [
      {
        "name": "token"
      }
    ],
    "parameter": {
      "examples": [
        {
          "title": "请求样例",
          "content": "/order",
          "type": "json"
        }
      ],
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bet_type_code",
            "description": "<p>投注类型code,可用code有hezhi,putong,xingyun,buzhong,lianying,fushi</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "lottery_code",
            "description": "<p>彩票code,可用code有gxkl10,gxk3</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "odds",
            "description": "<p>如：0.95</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "money",
            "description": "<p>如：12.6</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "face",
            "description": "<p>玩法面盘:0.x盘,y盘</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "issue",
            "description": "<p>奖期,如:34567890</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "codes",
            "description": "<p>奖期,如:和值投注方式['合值:号码:单'],三不中投注方式['三不中:号码:1,3,5'],特殊的连赢投注方式为['三连赢:合值:单','三连赢:合值:大','三连赢:平码:大']</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success": [
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "status",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>消息</p>"
          },
          {
            "group": "Success",
            "type": "object",
            "optional": false,
            "field": "data",
            "description": "<p>数据</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.id",
            "description": "<p>数据id</p>"
          },
          {
            "group": "Success",
            "optional": false,
            "field": "Example",
            "description": "<p>Success-Response: HTTP/1.1 200 OK { &quot;status&quot;: 200, &quot;data: [ ], &quot;msg&quot;: &quot;请求成功&quot; }</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "routes/web.php",
    "groupTitle": "BetOrder"
  },
  {
    "type": "delete",
    "url": "/order/{orderId}",
    "title": "",
    "group": "BetOrder",
    "name": "____",
    "permission": [
      {
        "name": "token"
      }
    ],
    "parameter": {
      "examples": [
        {
          "title": "请求样例",
          "content": "/order/{orderId}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success": [
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "status",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>消息</p>"
          },
          {
            "group": "Success",
            "type": "object",
            "optional": false,
            "field": "data",
            "description": "<p>数据</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.id",
            "description": "<p>数据id</p>"
          },
          {
            "group": "Success",
            "optional": false,
            "field": "Example",
            "description": "<p>Success-Response: HTTP/1.1 200 OK { &quot;status&quot;: 200, &quot;data: [ ], &quot;msg&quot;: &quot;请求成功&quot; }</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "routes/web.php",
    "groupTitle": "BetOrder"
  },
  {
    "type": "get",
    "url": "/orders/page-size/1",
    "title": "获取历史注单分页",
    "group": "BetOrder",
    "name": "__________orders_page_size__pageSize_",
    "permission": [
      {
        "name": "token"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "lottery_codes",
            "description": "<p>可选,彩票code,如：[gxk3],多个传多个,如:[gxk3, gxkl10],不传则返回所有彩票分类下的彩种</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "datetime",
            "description": "<p>日期时间,如[&quot;2017-01-01 13:00:00&quot;, &quot;2017-01-02 13:00:00&quot;]代表2017-01-01 13:00:00到2017-01-02 13:00:00</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "status",
            "description": "<p>状态,0.投注成功,1.结算中,2.已结算,3.取消下注</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success": [
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "status",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>消息</p>"
          },
          {
            "group": "Success",
            "type": "object",
            "optional": false,
            "field": "data",
            "description": "<p>数据</p>"
          },
          {
            "group": "Success",
            "optional": false,
            "field": "Example",
            "description": "<p>Success-Response: HTTP/1.1 200 OK { &quot;code&quot;: 200, &quot;data&quot;: { &quot;current_page&quot;: 1, &quot;data&quot;: [], &quot;first_page_url&quot;: &quot;http://192.168.56.128/orders/page-size/10?page=1&quot;, &quot;from&quot;: null, &quot;last_page&quot;: 1, &quot;last_page_url&quot;: &quot;http://192.168.56.128/orders/page-size/10?page=1&quot;, &quot;next_page_url&quot;: null, &quot;path&quot;: &quot;http://192.168.56.128/orders/page-size/10&quot;, &quot;per_page&quot;: 10, &quot;prev_page_url&quot;: null, &quot;to&quot;: null, &quot;total&quot;: 0 }, &quot;message&quot;: &quot;&quot; }</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "routes/web.php",
    "groupTitle": "BetOrder"
  },
  {
    "type": "get",
    "url": "/order-logs/page-size/50",
    "title": "获取游戏账变",
    "group": "BetOrder",
    "name": "________order_logs_page_size__pageSize_",
    "permission": [
      {
        "name": "token"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "type",
            "description": "<p>可选,类型,不传代表获取全部类型.交易类型,0.投注,1.派彩,2.取消订单</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "datetime",
            "description": "<p>日期时间,如[&quot;2017-01-01 13:00:00&quot;, &quot;2017-01-02 13:00:00&quot;]代表2017-01-01 13:00:00到2017-01-02 13:00:00</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success": [
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "status",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>消息</p>"
          },
          {
            "group": "Success",
            "type": "object",
            "optional": false,
            "field": "data",
            "description": "<p>数据</p>"
          },
          {
            "group": "Success",
            "optional": false,
            "field": "Example",
            "description": "<p>Success-Response: HTTP/1.1 200 OK { &quot;code&quot;: 200, &quot;data&quot;: { &quot;current_page&quot;: 1, &quot;data&quot;: [], &quot;first_page_url&quot;: &quot;http://192.168.56.128/orders/page-size/10?page=1&quot;, &quot;from&quot;: null, &quot;last_page&quot;: 1, &quot;last_page_url&quot;: &quot;http://192.168.56.128/orders/page-size/10?page=1&quot;, &quot;next_page_url&quot;: null, &quot;path&quot;: &quot;http://192.168.56.128/orders/page-size/10&quot;, &quot;per_page&quot;: 10, &quot;prev_page_url&quot;: null, &quot;to&quot;: null, &quot;total&quot;: 0 }, &quot;message&quot;: &quot;&quot; }</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "routes/web.php",
    "groupTitle": "BetOrder"
  },
  {
    "type": "post",
    "url": "/login",
    "title": "登录",
    "group": "Login",
    "name": "__",
    "parameter": {
      "examples": [
        {
          "title": "请求样例",
          "content": "/login",
          "type": "json"
        }
      ],
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "username",
            "description": "<p>用户名</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "password",
            "description": "<p>密码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "is_agent",
            "description": "<p>,是否代理,0否,1是</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "captcha[id]",
            "description": "<p>,验证码唯一id,如：5deda44ed73f4</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "captcha[value]",
            "description": "<p>,验证码</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success": [
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "status",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>消息</p>"
          },
          {
            "group": "Success",
            "type": "object",
            "optional": false,
            "field": "data",
            "description": "<p>数据</p>"
          },
          {
            "group": "Success",
            "optional": false,
            "field": "Example",
            "description": "<p>Success-Response: HTTP/1.1 200 OK { &quot;status&quot;: 200, &quot;data&quot;: [ { }, ], &quot;msg&quot;: &quot;请求成功&quot; }</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "routes/web.php",
    "groupTitle": "Login"
  },
  {
    "type": "get",
    "url": "/lottery-issues/1/page-size/50",
    "title": "分页获取彩种将期历史",
    "group": "Lotteries",
    "name": "____________lottery_issues__lotteryId__0_9____page_size__pageSize__0_9___",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "lottery_id",
            "description": "<p>必选,彩票id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "status",
            "description": "<p>必选,状态,0未开彩,1已开彩,2.开彩中,不传代表获取所有</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page-size",
            "description": "<p>必选,每页条数</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success": [
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "status",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>消息</p>"
          },
          {
            "group": "Success",
            "type": "object",
            "optional": false,
            "field": "data",
            "description": "<p>数据</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.index.id",
            "description": "<p>数据id</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.index.issue",
            "description": "<p>奖期</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.index.lottery_id",
            "description": "<p>彩票id</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.index.started_at",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.index.ended_at",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.index.status",
            "description": "<p>0未开彩,1已开彩,2.开彩中</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.index.reward_codes",
            "description": "<p>开奖号码</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.index.total_bet_money",
            "description": "<p>所有用户投注总额</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.index.total_reward_money",
            "description": "<p>总共派发彩金</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.index.total_bet_num",
            "description": "<p>总投注人数</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.index.total_reward_num",
            "description": "<p>中奖人数</p>"
          },
          {
            "group": "Success",
            "optional": false,
            "field": "Example",
            "description": "<p>Success-Response: HTTP/1.1 200 OK { &quot;code&quot;: 200, &quot;data&quot;: { &quot;current_page&quot;: 1, &quot;data&quot;: [ { &quot;id&quot;: 257, &quot;issue&quot;: &quot;201932911&quot;, &quot;lottery_id&quot;: &quot;1&quot;, &quot;started_at&quot;: &quot;1575866103&quot;, &quot;ended_at&quot;: &quot;1575866803&quot;, &quot;stop_bet_at&quot;: &quot;1575866773&quot;, &quot;status&quot;: &quot;0&quot;, &quot;reward_codes&quot;: null, &quot;total_bet_money&quot;: &quot;0&quot;, &quot;total_reward_money&quot;: &quot;0&quot;, &quot;total_bet_num&quot;: &quot;0&quot;, &quot;total_reward_num&quot;: &quot;0&quot;, &quot;created_at&quot;: &quot;2019-12-09 04:35:03&quot;, &quot;updated_at&quot;: &quot;2019-12-09 04:35:03&quot; }, { &quot;id&quot;: 255, &quot;issue&quot;: &quot;201932910&quot;, &quot;lottery_id&quot;: &quot;1&quot;, &quot;started_at&quot;: &quot;1575864782&quot;, &quot;ended_at&quot;: &quot;1575865380&quot;, &quot;stop_bet_at&quot;: &quot;1575865350&quot;, &quot;status&quot;: &quot;1&quot;, &quot;reward_codes&quot;: [ &quot;05&quot;, &quot;03&quot;, &quot;09&quot;, &quot;01&quot;, &quot;04&quot; ], &quot;total_bet_money&quot;: &quot;0&quot;, &quot;total_reward_money&quot;: &quot;0&quot;, &quot;total_bet_num&quot;: &quot;0&quot;, &quot;total_reward_num&quot;: &quot;0&quot;, &quot;created_at&quot;: &quot;2019-12-09 04:13:02&quot;, &quot;updated_at&quot;: &quot;2019-12-09 04:35:11&quot; } ], &quot;first_page_url&quot;: &quot;http://192.168.56.128/lottery-issues/1/page-size/2?page=1&quot;, &quot;from&quot;: 1, &quot;last_page&quot;: 17, &quot;last_page_url&quot;: &quot;http://192.168.56.128/lottery-issues/1/page-size/2?page=17&quot;, &quot;next_page_url&quot;: &quot;http://192.168.56.128/lottery-issues/1/page-size/2?page=2&quot;, &quot;path&quot;: &quot;http://192.168.56.128/lottery-issues/1/page-size/2&quot;, &quot;per_page&quot;: 2, &quot;prev_page_url&quot;: null, &quot;to&quot;: 2, &quot;total&quot;: 34 }, &quot;message&quot;: &quot;&quot; }</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "routes/web.php",
    "groupTitle": "Lotteries"
  },
  {
    "type": "get",
    "url": "/issue/{issue_id}",
    "title": "获取某期奖期详情",
    "group": "Lotteries",
    "name": "__________issues_1",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "lottery_id",
            "description": "<p>必选,彩票id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success": [
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "status",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>消息</p>"
          },
          {
            "group": "Success",
            "type": "object",
            "optional": false,
            "field": "data",
            "description": "<p>数据</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.id",
            "description": "<p>数据id</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.issue",
            "description": "<p>奖期</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.lottery_id",
            "description": "<p>彩票id</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.started_at",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.ended_at",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.status",
            "description": "<p>0未开彩,1已开彩,2.开彩中</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.reward_codes",
            "description": "<p>开奖号码</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.total_bet_money",
            "description": "<p>所有用户投注总额</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.total_reward_money",
            "description": "<p>总共派发彩金</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.total_bet_num",
            "description": "<p>总投注人数</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.total_reward_num",
            "description": "<p>中奖人数</p>"
          },
          {
            "group": "Success",
            "optional": false,
            "field": "Example",
            "description": "<p>Success-Response: HTTP/1.1 200 OK { &quot;status&quot;: 200, &quot;data&quot;:{ &quot;id&quot;: 1, &quot;issue&quot;: &quot;19120427 &quot;, &quot;lottery_id&quot;: &quot;1&quot;, &quot;started_at&quot;: &quot;1575452920&quot;, &quot;ended_at&quot;: &quot;1575594763&quot;, &quot;stop_bet_at&quot;: &quot;1575594703&quot;, &quot;status&quot;: &quot;1&quot;, &quot;reward_codes&quot;: null, &quot;total_bet_money&quot;: &quot;0&quot;, &quot;total_reward_money&quot;: &quot;0&quot;, &quot;total_bet_num&quot;: &quot;0&quot;, &quot;total_reward_num&quot;: &quot;0&quot;, &quot;created_at&quot;: &quot;2019-12-04 09:49:18&quot;, &quot;updated_at&quot;: &quot;2019-12-04 09:49:21&quot; }, &quot;msg&quot;: &quot;请求成功&quot; }</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "routes/web.php",
    "groupTitle": "Lotteries"
  },
  {
    "type": "get",
    "url": "/issue/{issue}获取某期奖期详情",
    "title": "",
    "group": "Lotteries",
    "name": "__________issues_2167856734",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "lottery_id",
            "description": "<p>必选,彩票id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success": [
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "status",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>消息</p>"
          },
          {
            "group": "Success",
            "type": "object",
            "optional": false,
            "field": "data",
            "description": "<p>数据</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.id",
            "description": "<p>数据id</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.issue",
            "description": "<p>奖期</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.lottery_id",
            "description": "<p>彩票id</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.started_at",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.ended_at",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.status",
            "description": "<p>0未开彩,1已开彩,2.开彩中</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.reward_codes",
            "description": "<p>开奖号码</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.total_bet_money",
            "description": "<p>所有用户投注总额</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.total_reward_money",
            "description": "<p>总共派发彩金</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.total_bet_num",
            "description": "<p>总投注人数</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.total_reward_num",
            "description": "<p>中奖人数</p>"
          },
          {
            "group": "Success",
            "optional": false,
            "field": "Example",
            "description": "<p>Success-Response: HTTP/1.1 200 OK { &quot;status&quot;: 200, &quot;data&quot;:{ &quot;id&quot;: 1, &quot;issue&quot;: &quot;19120427 &quot;, &quot;lottery_id&quot;: &quot;1&quot;, &quot;started_at&quot;: &quot;1575452920&quot;, &quot;ended_at&quot;: &quot;1575594763&quot;, &quot;stop_bet_at&quot;: &quot;1575594703&quot;, &quot;status&quot;: &quot;1&quot;, &quot;reward_codes&quot;: null, &quot;total_bet_money&quot;: &quot;0&quot;, &quot;total_reward_money&quot;: &quot;0&quot;, &quot;total_bet_num&quot;: &quot;0&quot;, &quot;total_reward_num&quot;: &quot;0&quot;, &quot;created_at&quot;: &quot;2019-12-04 09:49:18&quot;, &quot;updated_at&quot;: &quot;2019-12-04 09:49:21&quot; }, &quot;msg&quot;: &quot;请求成功&quot; }</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "routes/web.php",
    "groupTitle": "Lotteries"
  },
  {
    "type": "get",
    "url": "/lottery-issues/1",
    "title": "根据彩种获取将期",
    "group": "Lotteries",
    "name": "__________lottery_issues__lottery_id_",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "lottery_id",
            "description": "<p>必选,彩票id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "status",
            "description": "<p>可选,状态,0未开彩,1已开彩,2.开彩中,不传代表获取所有</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>可选,获取条数</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "offset",
            "description": "<p>可选,从第几条开始获取</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success": [
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "status",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>消息</p>"
          },
          {
            "group": "Success",
            "type": "object",
            "optional": false,
            "field": "data",
            "description": "<p>数据</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.index.id",
            "description": "<p>数据id</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.index.issue",
            "description": "<p>奖期</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.index.lottery_id",
            "description": "<p>彩票id</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.index.started_at",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.index.ended_at",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.index.status",
            "description": "<p>0未开彩,1已开彩,2.开彩中</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.index.reward_codes",
            "description": "<p>开奖号码</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.index.total_bet_money",
            "description": "<p>所有用户投注总额</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.index.total_reward_money",
            "description": "<p>总共派发彩金</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.index.total_bet_num",
            "description": "<p>总投注人数</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.index.total_reward_num",
            "description": "<p>中奖人数</p>"
          },
          {
            "group": "Success",
            "optional": false,
            "field": "Example",
            "description": "<p>Success-Response: HTTP/1.1 200 OK { &quot;status&quot;: 200, &quot;data&quot;:[ { &quot;id&quot;: 1, &quot;issue&quot;: &quot;19120427 &quot;, &quot;lottery_id&quot;: &quot;1&quot;, &quot;started_at&quot;: &quot;1575452920&quot;, &quot;ended_at&quot;: &quot;1575594763&quot;, &quot;stop_bet_at&quot;: &quot;1575594703&quot;, &quot;status&quot;: &quot;1&quot;, &quot;reward_codes&quot;: null, &quot;total_bet_money&quot;: &quot;0&quot;, &quot;total_reward_money&quot;: &quot;0&quot;, &quot;total_bet_num&quot;: &quot;0&quot;, &quot;total_reward_num&quot;: &quot;0&quot;, &quot;created_at&quot;: &quot;2019-12-04 09:49:18&quot;, &quot;updated_at&quot;: &quot;2019-12-04 09:49:21&quot; } ], &quot;msg&quot;: &quot;请求成功&quot; }</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "routes/web.php",
    "groupTitle": "Lotteries"
  },
  {
    "type": "get",
    "url": "/lotteries?ids[]=1&ids[]=2",
    "title": "获取彩种",
    "group": "Lotteries",
    "name": "________lotteries",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "ids",
            "description": "<p>可选,彩票id,如：[1],多个传多个,如:[1, 2],不传则返回所有彩票</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success": [
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "status",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>消息</p>"
          },
          {
            "group": "Success",
            "type": "object",
            "optional": false,
            "field": "data",
            "description": "<p>数据</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.index.id",
            "description": "<p>分类id</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.index.name",
            "description": "<p>分类名称</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.index.icon",
            "description": "<p>图标</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.index.description",
            "description": "<p>分类描述</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.index.status",
            "description": "<p>分类状态</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.index.remark",
            "description": "<p>分类描述</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.index.lottery_num",
            "description": "<p>彩票游戏数量</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.index.sort",
            "description": "<p>排序</p>"
          },
          {
            "group": "Success",
            "optional": false,
            "field": "Example",
            "description": "<p>Success-Response: HTTP/1.1 200 OK { &quot;status&quot;: 200, &quot;data&quot;:{ &quot;id&quot;: 1, &quot;name&quot;: &quot;广西快乐十分&quot;, &quot;code&quot;: &quot;gxkl10&quot;, &quot;icon&quot;: &quot;&quot;, &quot;lottery_category_id&quot;: &quot;1&quot;, &quot;status&quot;: &quot;1&quot;, &quot;description&quot;: null, &quot;limit_time&quot;: &quot;20&quot;, &quot;issue_num_day&quot;: &quot;40&quot;, &quot;is_official&quot;: &quot;1&quot;, &quot;created_at&quot;: &quot;2019-12-03 02:46:57&quot;, &quot;updated_at&quot;: &quot;2019-12-03 02:46:59&quot;, &quot;sort&quot;: &quot;0&quot;, &quot;bet_type&quot;: [ { &quot;id&quot;: 1, &quot;name&quot;: &quot;合值&quot;, &quot;code&quot;: &quot;hezhi&quot;, &quot;created_at&quot;: &quot;2019-12-03 05:05:56&quot;, &quot;updated_at&quot;: &quot;2019-12-03 05:05:59&quot;, &quot;pivot&quot;: { &quot;lottery_id&quot;: &quot;1&quot;, &quot;bet_type_id&quot;: &quot;1&quot;, &quot;status&quot;: &quot;1&quot;, &quot;play_face&quot;: &quot;0&quot; } }, { &quot;id&quot;: 2, &quot;name&quot;: &quot;普通投注&quot;, &quot;code&quot;: &quot;putong&quot;, &quot;created_at&quot;: &quot;2019-12-03 05:07:31&quot;, &quot;updated_at&quot;: &quot;2019-12-03 05:07:36&quot;, &quot;pivot&quot;: { &quot;lottery_id&quot;: &quot;1&quot;, &quot;bet_type_id&quot;: &quot;2&quot;, &quot;status&quot;: &quot;1&quot;, &quot;play_face&quot;: &quot;0&quot; } }, ] }, &quot;msg&quot;: &quot;请求成功&quot; }</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "routes/web.php",
    "groupTitle": "Lotteries"
  },
  {
    "type": "get",
    "url": "/lotteries/categories?category_ids[]=1&category_ids[]=2",
    "title": "根据分类获取彩票",
    "group": "Lotteries",
    "name": "________lotteries_categories",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "category_ids",
            "description": "<p>可选,彩票分类id,如：[1],多个传多个,如:[1, 2],不传则返回所有彩票分类下的彩种</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success": [
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "status",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>消息</p>"
          },
          {
            "group": "Success",
            "type": "object",
            "optional": false,
            "field": "data",
            "description": "<p>数据</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.index.id",
            "description": "<p>分类id</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.index.name",
            "description": "<p>分类名称</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.index.icon",
            "description": "<p>图标</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.index.description",
            "description": "<p>分类描述</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.index.status",
            "description": "<p>分类状态</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.index.remark",
            "description": "<p>分类描述</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.index.lottery_num",
            "description": "<p>彩票游戏数量</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.index.sort",
            "description": "<p>排序</p>"
          },
          {
            "group": "Success",
            "optional": false,
            "field": "Example",
            "description": "<p>Success-Response: HTTP/1.1 200 OK { &quot;status&quot;: 200, &quot;data&quot;: [ { &quot;id&quot;: 1, &quot;name&quot;: &quot;快乐十分&quot;, &quot;icon&quot;: &quot;&quot;, &quot;description&quot;: null, &quot;status&quot;: &quot;1&quot;, &quot;remark&quot;: &quot;&quot;, &quot;lottery_num&quot;: &quot;1&quot;, &quot;created_at&quot;: &quot;2019-12-03 02:45:27&quot;, &quot;updated_at&quot;: &quot;2019-12-03 02:45:32&quot;, &quot;sort&quot;: &quot;0&quot;, &quot;lottery&quot;: [ { &quot;id&quot;: 1, &quot;name&quot;: &quot;广西快乐十分&quot;, &quot;code&quot;: &quot;gxkl10&quot;, &quot;icon&quot;: &quot;&quot;, &quot;lottery_category_id&quot;: &quot;1&quot;, &quot;status&quot;: &quot;1&quot;, &quot;description&quot;: null, &quot;limit_time&quot;: &quot;20&quot;, &quot;issue_num_day&quot;: &quot;40&quot;, &quot;is_official&quot;: &quot;1&quot;, &quot;created_at&quot;: &quot;2019-12-03 02:46:57&quot;, &quot;updated_at&quot;: &quot;2019-12-03 02:46:59&quot;, &quot;sort&quot;: &quot;0&quot;, &quot;bet_type&quot;: [ { &quot;id&quot;: 1, &quot;name&quot;: &quot;合值&quot;, &quot;code&quot;: &quot;hezhi&quot;, &quot;created_at&quot;: &quot;2019-12-03 05:05:56&quot;, &quot;updated_at&quot;: &quot;2019-12-03 05:05:59&quot;, &quot;pivot&quot;: { &quot;lottery_id&quot;: &quot;1&quot;, &quot;bet_type_id&quot;: &quot;1&quot;, &quot;status&quot;: &quot;1&quot;, &quot;play_face&quot;: &quot;0&quot; } }, { &quot;id&quot;: 2, &quot;name&quot;: &quot;普通投注&quot;, &quot;code&quot;: &quot;putong&quot;, &quot;created_at&quot;: &quot;2019-12-03 05:07:31&quot;, &quot;updated_at&quot;: &quot;2019-12-03 05:07:36&quot;, &quot;pivot&quot;: { &quot;lottery_id&quot;: &quot;1&quot;, &quot;bet_type_id&quot;: &quot;2&quot;, &quot;status&quot;: &quot;1&quot;, &quot;play_face&quot;: &quot;0&quot; } }, ] } ], &quot;msg&quot;: &quot;请求成功&quot; }</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "routes/web.php",
    "groupTitle": "Lotteries"
  },
  {
    "type": "get",
    "url": "/lottery-categories",
    "title": "获取彩票分类",
    "group": "Lotteries",
    "name": "________lottery_categories",
    "success": {
      "fields": {
        "Success": [
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "status",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>消息</p>"
          },
          {
            "group": "Success",
            "type": "object",
            "optional": false,
            "field": "data",
            "description": "<p>数据</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.index.id",
            "description": "<p>分类id</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.index.name",
            "description": "<p>分类名称</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.index.icon",
            "description": "<p>图标</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.index.description",
            "description": "<p>分类描述</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.index.status",
            "description": "<p>分类状态</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.index.remark",
            "description": "<p>分类描述</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.index.lottery_num",
            "description": "<p>彩票游戏数量</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.index.sort",
            "description": "<p>排序</p>"
          },
          {
            "group": "Success",
            "optional": false,
            "field": "Example",
            "description": "<p>Success-Response: HTTP/1.1 200 OK { &quot;status&quot;: 200, &quot;data&quot;: [ { &quot;id&quot;: 1, &quot;name&quot;: &quot;快乐十分&quot;, &quot;icon&quot;: &quot;&quot;, &quot;description&quot;: null, &quot;status&quot;: &quot;1&quot;, &quot;remark&quot;: &quot;&quot;, &quot;lottery_num&quot;: &quot;1&quot;, &quot;created_at&quot;: &quot;2019-12-03 02:45:27&quot;, &quot;updated_at&quot;: &quot;2019-12-03 02:45:32&quot;, &quot;sort&quot;: &quot;0&quot; }, { &quot;id&quot;: 2, &quot;name&quot;: &quot;快三&quot;, &quot;icon&quot;: &quot;&quot;, &quot;description&quot;: null, &quot;status&quot;: &quot;1&quot;, &quot;remark&quot;: &quot;&quot;, &quot;lottery_num&quot;: &quot;0&quot;, &quot;created_at&quot;: &quot;2019-12-03 02:45:51&quot;, &quot;updated_at&quot;: &quot;2019-12-03 02:45:53&quot;, &quot;sort&quot;: &quot;0&quot; } ], &quot;msg&quot;: &quot;请求成功&quot; }</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "routes/web.php",
    "groupTitle": "Lotteries"
  },
  {
    "type": "put",
    "url": "/modify-password",
    "title": "修改密码",
    "group": "User",
    "name": "____",
    "permission": [
      {
        "name": "token"
      }
    ],
    "parameter": {
      "examples": [
        {
          "title": "请求样例",
          "content": "/modify-password",
          "type": "json"
        }
      ],
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "password",
            "description": "<p>新密码</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "password_comfirmation",
            "description": "<p>确认新密码</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success": [
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "status",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>消息</p>"
          },
          {
            "group": "Success",
            "type": "object",
            "optional": false,
            "field": "data",
            "description": "<p>数据</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.id",
            "description": "<p>数据id</p>"
          },
          {
            "group": "Success",
            "optional": false,
            "field": "Example",
            "description": "<p>Success-Response: HTTP/1.1 200 OK { &quot;status&quot;: 200, &quot;data: [ ], &quot;msg&quot;: &quot;请求成功&quot; }</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "routes/web.php",
    "groupTitle": "User"
  },
  {
    "type": "get",
    "url": "/user-info",
    "title": "获取用户信息",
    "group": "User",
    "name": "________user_info",
    "success": {
      "fields": {
        "Success": [
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "status",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>消息</p>"
          },
          {
            "group": "Success",
            "type": "object",
            "optional": false,
            "field": "data",
            "description": "<p>数据</p>"
          },
          {
            "group": "Success",
            "optional": false,
            "field": "Example",
            "description": "<p>Success-Response: HTTP/1.1 200 OK { &quot;code&quot;: 200, &quot;data&quot;: { &quot;id&quot;: 368, &quot;site_id&quot;: &quot;1&quot;, &quot;grade_id&quot;: &quot;1&quot;, &quot;level_id&quot;: &quot;1&quot;, &quot;agent_id&quot;: &quot;1&quot;, &quot;username&quot;: &quot;f505b7&quot;, &quot;status&quot;: &quot;1&quot;, &quot;register_ip&quot;: &quot;127.0.0.1&quot;, &quot;register_time&quot;: &quot;2019-11-18 02:41:58&quot;, &quot;register_url&quot;: &quot;&quot;, &quot;register_device&quot;: &quot;1&quot;, &quot;last_login_ip&quot;: &quot;127.0.0.1&quot;, &quot;last_login_time&quot;: &quot;2019-11-19 01:59:33&quot;, &quot;last_login_address&quot;: &quot;内网IP&quot;, &quot;realname&quot;: &quot;&quot;, &quot;mobile&quot;: &quot;&quot;, &quot;email&quot;: &quot;&quot;, &quot;qq&quot;: null, &quot;birthday&quot;: null, &quot;sex&quot;: &quot;0&quot;, &quot;is_online&quot;: &quot;0&quot;, &quot;focus_level&quot;: &quot;1&quot;, &quot;balance_status&quot;: &quot;1&quot;, &quot;safe_question&quot;: &quot;&quot;, &quot;safe_answer&quot;: &quot;&quot;, &quot;show_beginner_guide&quot;: &quot;1&quot;, &quot;delete_at&quot;: &quot;0&quot;, &quot;remark&quot;: &quot;&quot;, &quot;created_at&quot;: &quot;2019-11-18 02:41:58&quot;, &quot;updated_at&quot;: &quot;2019-11-19 01:59:33&quot;, &quot;agent_name&quot;: &quot;agent001&quot;, &quot;balance&quot;: &quot;10772.00&quot; }, &quot;message&quot;: &quot;&quot; }</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "routes/web.php",
    "groupTitle": "User"
  },
  {
    "type": "delete",
    "url": "/logout",
    "title": "用户等处",
    "group": "User",
    "name": "______logout",
    "permission": [
      {
        "name": "token"
      }
    ],
    "success": {
      "fields": {
        "Success": [
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "status",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>消息</p>"
          },
          {
            "group": "Success",
            "type": "object",
            "optional": false,
            "field": "data",
            "description": "<p>数据</p>"
          },
          {
            "group": "Success",
            "optional": false,
            "field": "Example",
            "description": "<p>Success-Response: HTTP/1.1 200 OK { &quot;code&quot;: 200, &quot;data&quot;: [], &quot;message&quot;: &quot;&quot; }</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "routes/web.php",
    "groupTitle": "User"
  },
  {
    "type": "get",
    "url": "/captcha",
    "title": "获取验证码",
    "group": "____",
    "name": "_____captcha",
    "success": {
      "fields": {
        "Success": [
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "status",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>消息</p>"
          },
          {
            "group": "Success",
            "type": "object",
            "optional": false,
            "field": "data",
            "description": "<p>数据</p>"
          },
          {
            "group": "Success",
            "type": "number",
            "optional": false,
            "field": "data.id",
            "description": "<p>数据id</p>"
          },
          {
            "group": "Success",
            "type": "string",
            "optional": false,
            "field": "data.captcha",
            "description": "<p>验证码</p>"
          },
          {
            "group": "Success",
            "optional": false,
            "field": "Example",
            "description": "<p>Success-Response: HTTP/1.1 200 OK { &quot;status&quot;: 200, &quot;data&quot;:{ &quot;captcha&quot;: &quot;data:image/jpeg;base64,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&quot;, &quot;id&quot;: &quot;5de785bae7ab0&quot; }, &quot;msg&quot;: &quot;请求成功&quot; }</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "routes/web.php",
    "groupTitle": "____"
  }
] });
