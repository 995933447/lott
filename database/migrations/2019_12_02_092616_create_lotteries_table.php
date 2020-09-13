<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLotteriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lotteries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('彩票名称');
            $table->string('code')->comment('彩票代码');
            $table->string('icon')->comment('图片')->default('');
            $table->integer('lottery_category_id')->comment('游戏分类id');
            $table->tinyInteger('status')->comment('状态,1有效,0无效')->default(1);
            $table->text('description')->comment('描述')->nullable();
            $table->integer('limit_time')->comment('频率,0代表未知')->default(0);
            $table->integer('issue_num_day')->comment('每日总共奖期数量,0代表未知')->default(0);
            $table->tinyInteger('is_official')->comment('是否官方彩,1是,0不是')->default(1);
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
        Schema::dropIfExists('lotteries');
    }
}
