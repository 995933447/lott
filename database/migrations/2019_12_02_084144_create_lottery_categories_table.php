<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLotteryCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lottery_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('名称');
            $table->string('icon')->comment('图片')->default('');
            $table->text('description')->comment('描述')->nullable();
            $table->tinyInteger('status')->comment('状态')->default(1);
            $table->string('remark')->comment('备注')->default('');
            $table->tinyInteger('lottery_num')->comment('彩票数量')->default(0);
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
        Schema::dropIfExists('lottery_categories');
    }
}
