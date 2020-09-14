<?php

use App\Models\Lottery;
use Illuminate\Database\Seeder;

class LotteryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => '广西快乐十分',
                'status' => Lottery::VALID_STATUS,
                'code' => Lottery::GUANGXIKUAILE10_TYPE_CODE,
                'lottery_category_id' => 1,
                'limit_time' => 20,
                'issue_num_day' => 40,
                'is_official' => Lottery::IS_OFFICIAL_STATUS,
                'started_at' => '09:00:00',
                'ended_at' => '22:30:00',
                'sort' => 0,
            ],
            [
                'name' => '快三',
                'status' => Lottery::VALID_STATUS,
                'code' => Lottery::GUANGXIKUAI3_TYPE_CODE,
                'lottery_category_id' => 2,
                'limit_time' => 20,
                'issue_num_day' => 40,
                'is_official' => Lottery::IS_OFFICIAL_STATUS,
                'started_at' => '09:10:00',
                'ended_at' => '22:35:00',
                'sort' => 0,
            ]
        ];
        foreach ($data as $value) {
            Lottery::create($value);
        }
    }
}
