<?php

use App\Models\LotteryBetType;
use Illuminate\Database\Seeder;

class LotteryBetTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array (
            0 =>
                array (
                    'id' => 1,
                    'lottery_id' => '1',
                    'play_face' => '0',
                    'status' => '1',
                    'bet_type_id' => '1',
                    'sort' => '0',
                ),
            1 =>
                array (
                    'id' => 2,
                    'lottery_id' => '1',
                    'play_face' => '0',
                    'status' => '1',
                    'bet_type_id' => '2',
                    'sort' => '0',
                ),
            2 =>
                array (
                    'id' => 3,
                    'lottery_id' => '1',
                    'play_face' => '0',
                    'status' => '1',
                    'bet_type_id' => '3',
                    'sort' => '0',
                ),
            3 =>
                array (
                    'id' => 4,
                    'lottery_id' => '1',
                    'play_face' => '0',
                    'status' => '1',
                    'bet_type_id' => '4',
                    'sort' => '0',
                ),
            4 =>
                array (
                    'id' => 5,
                    'lottery_id' => '1',
                    'play_face' => '0',
                    'status' => '1',
                    'bet_type_id' => '5',
                    'sort' => '0',
                ),
            5 =>
                array (
                    'id' => 6,
                    'lottery_id' => '1',
                    'play_face' => '0',
                    'status' => '1',
                    'bet_type_id' => '6',
                    'sort' => '0',
                ),
            6 =>
                array (
                    'id' => 7,
                    'lottery_id' => '2',
                    'play_face' => '0',
                    'status' => '1',
                    'bet_type_id' => '1',
                    'sort' => '0',
                ),
            7 =>
                array (
                    'id' => 8,
                    'lottery_id' => '1',
                    'play_face' => '1',
                    'status' => '1',
                    'bet_type_id' => '1',
                    'sort' => '0',
                ),
            8 =>
                array (
                    'id' => 9,
                    'lottery_id' => '1',
                    'play_face' => '1',
                    'status' => '1',
                    'bet_type_id' => '2',
                    'sort' => '0',
                ),
        );
        foreach ($data as $value) {
            LotteryBetType::create($value);
        }
    }
}
