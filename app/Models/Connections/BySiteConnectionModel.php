<?php
namespace App\Models\Connections;

use Illuminate\Database\Eloquent\Model;

class BySiteConnectionModel extends Model
{
    const CONNECTION = 'mysql_extend_by_site';
    protected $connection = self::CONNECTION;
}
