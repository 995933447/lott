<?php
namespace App\Http\Controllers;

use App\Models\Issue;
use App\Services\Issue\Tasks\CancelIssue;
use App\Services\Issue\Tasks\ResetIssue;
use App\Services\ServiceDispatcher;
use App\Utils\Formatters\End;
use Illuminate\Http\Request;

class IssueController
{
    public function resetIssue(Request $request)
    {
        $result = ServiceDispatcher::dispatch(
            ServiceDispatcher::TASK_SERVICE,
            new ResetIssue(
                Issue::find($request->input('issue_id')),
                $request->input('issue'),
                $request->input('status')
            )
        );

        if ($result->hasErrors()) {
            return End::toSuccessJson($result->getErrors(), $result->getError());
        }

        return End::toSuccessJson();
    }

    public function cancelIssue($issueId)
    {
        $result = ServiceDispatcher::dispatch(ServiceDispatcher::TASK_SERVICE, new CancelIssue(Issue::find($issueId)));
        if ($result->hasErrors()) {
            return End::toSuccessJson($result->getErrors(), $result->getError());
        }
        return End::toSuccessJson();
    }
}
