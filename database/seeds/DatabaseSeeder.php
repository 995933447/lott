<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call('LotteryCategoryTableSeeder');
         $this->call('LotteryTableSeeder');
         $this->call('BetTypeTableSeeder');
         $this->call('LotteryBetTypeTableSeeder');
    }
}
