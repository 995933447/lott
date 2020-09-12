<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('issue')->comment('奖期');
            $table->integer('lottery_id')->comment('彩票id');
            $table->integer('started_at')->comment('开始时间');
            $table->integer('ended_at')->comment('结束时间');
            $table->integer('stop_bet_at')->comment('封盘时间');
            $table->tinyInteger('status')->comment('状态,0未开彩,1已开彩,2开采中')->default(0);
            $table->string('reward_codes')->comment('开彩号码')->default('');
            $table->string('total_bet_money')->comment('总投总额')->default('0');
            $table->string('total_reward_money')->comment('派彩总额')->default('0');
            $table->integer('total_bet_num')->comment('投注总人数')->default(0);
            $table->integer('total_reward_num')->comment('中奖总人数')->default(0);
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
        Schema::dropIfExists('lottery_issue');
    }
}
