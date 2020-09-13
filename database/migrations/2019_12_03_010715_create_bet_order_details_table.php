<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBetOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bet_order_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('bet_order_id')->comment('下注订单id');
            $table->string('bet_money')->comment('下注金额');
            $table->string('codes')->comment('下注号码');
            $table->string('reward_money')->comment('中奖金额')->comment('0');
            $table->string('reward_codes')->comment('中奖号码')->default('');
            $table->string('reward_status')->comment('中奖状态,0.未中奖,1.已中奖,2.和');
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
        Schema::dropIfExists('bet_order_details');
    }
}
