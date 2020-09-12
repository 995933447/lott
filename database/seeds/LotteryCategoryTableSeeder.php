<?php

use App\Models\LotteryCategory;
use Illuminate\Database\Seeder;

class LotteryCategoryTableSeeder extends Seeder
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
              'id' => 1,
              'name' => '快乐十分',
              'status' => LotteryCategory::VALID_STATUS,
              'lottery_num' => 1,
              'sort' => 0,
            ],
            [
                'id' => 2,
                'name' => '快三',
                'status' => LotteryCategory::VALID_STATUS,
                'lottery_num' => 1,
                'sort' => 0,
            ]
        ];
        foreach ($data as $value) {
            LotteryCategory::create($value);
        }
    }
}
