<?php
namespace App\Services\Issue\Tasks\IssueManagers;

use App\Models\Issue;

interface IssueManagerContract
{
    public function getIssueResult(Issue $issue, string $lotteryCode): Issuer;

    public function createIssues(string $lotteryCode, string $date): Issuers;
}
