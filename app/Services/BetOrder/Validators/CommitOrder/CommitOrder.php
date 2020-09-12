<?php
namespace App\Services\BetOrder\Validators\CommitOrder;

use App\Models\Issue;
use App\Models\Lottery;
use App\Models\UserBalance;
use App\Repositories\IssueRepository;
use App\Services\BetOrder\Validators\CommitOrder\BetDataValidators\KuaiLeShiFen\GuangXiKuaiLeShiFen;
use App\Services\BetOrder\Validators\CommitOrder\BetDataValidators\KuaiSan\GuangXiKuaiSan;
use App\Services\ServeResult;
use App\Services\ValidatorServiceContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommitOrder implements ValidatorServiceContract
{
    private $betDataValidators = [
        Lottery::GUANGXIKUAILE10_TYPE_CODE => GuangXiKuaiLeShiFen::class,
        Lottery::GUANGXIKUAI3_TYPE_CODE => GuangXiKuaiSan::class
    ];

    public function validate(Request $request): ServeResult
    {
        $baseValidator = Validator::make($data = $request->all(), [
            'bet_type_code' => 'bail|required',
            'lottery_code' => 'bail|required',
            'odds' => 'bail|required|numeric',
            'codes' => 'bail|required|array',
            'money' => [
                'bail',
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    if (bccomp(UserBalance::where(UserBalance::USER_ID_FIELD, Auth::id())->value(UserBalance::BALANCE_FIELD), $value) < 0)
                        $fail('用户余额不足');
                }
            ],
            'face' => 'bail|required|in:0,1',
            'issue' => [
                'bail',
                'required',
                function ($attribute, $value, $fail) use ($data) {
                    $data = ($lottery = (new lottery))
                            ->rightJoin(($issue = (new Issue())->getTable()), $issue . '.' . Issue::LOTTERY_ID_FIELD, '=', $lottery->getTable() . '.' . $lottery->getPrimaryKey())
                            ->where($issue . '.' . Issue::ISSUE_FIELD, $value)
                            ->where($lottery->getTable() . '.' . Lottery::CODE_FIELD, $data['lottery_code'])
                            ->first();
                    if (!$data) {
                       return $fail('奖期不正确');
                    }
                    if ($data['started_at'] > time()) {
                       return $fail('奖期未开始');
                    }
                    if ($data['stop_bet_at'] <= time()) {
                       return $fail('游戏已封盘');
                    }
                }
            ]
        ], [
            'bet_type_code.required' => '缺少参数bet_type_code',
            'lottery_code.required' => '缺少参数lottery_code',
            'odds.required' => '缺少参数odds',
            'odds.required' => 'odds参数非法',
            'codes.required' => '缺少参数codes',
            'codes.array' => 'codes参数非法',
            'money.required' => '请输入投注金额',
            'money.numeric' => '投注金额必须为有效数值',
            'face.required' => '缺少face参数',
            'face.in' => 'face参数不合法',
            'issue.required' => 'issue参数不合法'
        ]);
        if ($baseValidator->fails()) {
            return ServeResult::make($baseValidator->errors()->toArray());
        }

        return $this->validateBetDataIsLegality($request->input('lottery_code'), $request->input('bet_type_code'), $data);
    }

    private function validateBetDataIsLegality(string $lotteryCode, string $betTypeCode, array $data): ServeResult
    {
        return (new $this->betDataValidators[$lotteryCode])->handle($betTypeCode, $data);
    }
}
