<?php
namespace App\Models\Connections;

use Illuminate\Database\Eloquent\Model;

class ByBalanceConnectionModel extends Model
{
    const CONNECTION = 'mysql_extend_by_balance';
    protected $connection = self::CONNECTION;
}
