<?php

use App\Models\BetType;
use Illuminate\Database\Seeder;

class BetTypeTableSeeder extends Seeder
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
                'name' => '合值',
                'code' => 'hezhi'
            ],
            [
                'id' => 2,
                'name' => '普通投注',
                'code' => 'putong'
            ],
            [
                'id' => 3,
                'name' => '幸运',
                'code' => 'xingyun'
            ],
            [
                'id' => 4,
                'name' => '不中',
                'code' => 'buzhong'
            ],
            [
                'id' => 5,
                'name' => '连赢',
                'code' => 'lianying'
            ],
            [
                'id' => 6,
                'name' => '复式',
                'code' => 'fushi'
            ],
        ];
        foreach ($data as $value) {
            BetType::create($value);
        }
    }
}
