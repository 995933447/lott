<?php
namespace App\Repositories;

use App\Models\Issue;

class IssueRepository
{
    public static function find(int $id): array
    {
        return ($model = new Issue())->with('lottery')->where($model->getPrimaryKey(), $id)->first()->toArray();
    }

    public static function getByIssue(string $issue): array
    {
        return Issue::where(Issue::ISSUE_FIELD, $issue)->get()->toArray();
    }
}
