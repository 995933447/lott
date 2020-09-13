<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTransactionLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('lottery_categories')) return;
        Schema::create('order_transaction_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->comment('用户id');
            $table->integer('order_id')->comment('订单id');
            $table->string('before_transaction_balance')->comment('交易前余额');
            $table->string('after_transaction_balance')->comment('交易后余额');
            $table->string('remark')->comment('交易备注');
            $table->string('transaction_money')->comment('交易金额');
            $table->string('type')->comment('交易类型,0.投注,1.派彩,2.取消订单');
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
        Schema::dropIfExists('order_transection_logs');
    }
}
