<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBetOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bet_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->comment('用户ID');
            $table->integer('lottery_id')->comment('彩票ID');
            $table->string('lottery_code')->comment('彩票代码');
            $table->integer('play_type_id')->comment('玩法ID');
            $table->string('play_type_code')->comment('玩法代码');
            $table->tinyInteger('play_face')->comment('面盘,0.X盘,1.Y盘')->default(0);
            $table->string('issue')->comment('奖期');
            $table->string('total_bet_money')->comment('投注总金额');
            $table->string('valid_bet_money')->comment('有效投注金额')->default('0');
            $table->tinyInteger('status')->comment('状态,0.投注成功,1.结算中,2.已结算,3.取消下注')->default(0);
            $table->string('win')->comment('盈利金额')->default('0');
            $table->string('reward_money')->comment('中奖金额')->default('0');
            $table->text('reward_codes')->comment('中奖号码')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bets');
    }
}
