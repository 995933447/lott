<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BetType extends Model
{
    use ExtraTrait;

    const CODE_FIELD = 'code';
    const NAME_FIELD = 'name';

    const HEZHI_TYPE_CODE = 'hezhi';
    const NORMAL_TYPE_CODE = 'putong';
    const XINGYUN_TYPE_CODE = 'xingyun';
    const BUZHONG_TYPE_CODE = 'buzhong';
    const LIANYING_TYPE_CODE = 'lianying';
    const FUSHI_TYPE_CODE = 'fushi';
}
