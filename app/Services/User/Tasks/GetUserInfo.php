<?php
namespace App\Services\User\Tasks;

use App\Services\ServeResult;
use App\Services\TaskServiceContract;
use App\Utils\Marker\MarkedKeysEnum;
use App\Utils\Marker\MarkInstance;
use Illuminate\Support\Facades\Auth;

class GetUserInfo implements TaskServiceContract
{
    public function run(): ServeResult
    {
        if (($info = Auth::user()->toArray()) && !MarkInstance::marked(MarkedKeysEnum::CURRENT_USER_IS_AGENT)) {
            $info['agent_name'] = Auth::user()->agent->username?? '';
            $info['is_bind_password'] = Auth::user()->is_bind_password;
            $info['balance'] = Auth::user()->balance->balance?: 0;
        }
        return ServeResult::make([], $info);
    }
}
