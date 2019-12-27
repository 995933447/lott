<?php
namespace App\Repositories;

use App\Models\Agent;
use App\Models\Connections\BySiteConnectionModel;
use App\Models\User;

class UserRepository
{
    public static function find(int $id, bool $isAgent): ?BySiteConnectionModel
    {
        $model = $isAgent? new Agent(): new User();
        $statusField = $isAgent? Agent::STATUS_FIELD : User::STATUS_FIELD;
        $validStatus = $isAgent? Agent::VALID_STATUS : User::VALID_STATUS;

        return $model->where([$statusField => $validStatus, $model->getPrimaryKey() => $id])->first();
    }
}
