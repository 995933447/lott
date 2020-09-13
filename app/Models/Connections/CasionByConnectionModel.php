<?php
namespace App\Models\Connections;

use Illuminate\Database\Eloquent\Model;

class CasionByConnectionModel extends Model
{
    const CONNECTION = 'mysql_extend_casion_by';
    protected $connection = self::CONNECTION;
}
