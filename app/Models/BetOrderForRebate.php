<?php
namespace App\Models;

use App\Models\Connections\CasionByConnectionModel;
use App\Utils\Formatters\Money;

class BetOrderForRebate extends CasionByConnectionModel
{
    protected $table = 'boya_lottery';

    const GAME_NO_FIELD = 'game_no';

    const SETTLEMENT_STATUS = 1;
    const NO_SETTLEMENT_STATUS = 1;

    public function setBetContentAttribute($value)
    {
        $this->attributes['bet_content'] = is_array($value)? json_encode($value): $value;
    }

    public function setGameResultAttribute($value)
    {
        $this->attributes['game_result'] = is_array($value)? json_encode($value): $value;
    }
}
