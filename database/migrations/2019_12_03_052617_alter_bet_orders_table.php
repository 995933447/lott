<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBetOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('bet_order_details');

        Schema::table('bet_orders', function (Blueprint $table) {
            $table->dropColumn('total_bet_money');

            $table->string('bet_money')->comment('投注金额');
            $table->text('codes')->comment('投注号码');
            $table->tinyInteger('reward_status')->comment('中奖状态,0.未中奖,1.已中奖,2.和')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
