<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LotteryBetType extends Model
{
    const STATUS_FIELD = 'status';

    const PLAY_FACE_FIELD = 'play_face';

    const VALID_STATUS = 1;

    const X_PLAY_FACE = 0;

    const Y_PLAY_FACE = 1;
}
