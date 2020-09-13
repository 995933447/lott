<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLotteryPlayTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lottery_play_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('lottery_id')->comment('彩票代码');
            $table->string('play_type_id')->comment('玩法代码');
            $table->tinyInteger('play_face')->comment('玩法面盘,0.X盘,1.Y盘')->default(0);
            $table->tinyInteger('status')->comment('状态,1有效,0无效')->default(1);
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
        Schema::dropIfExists('lottery_play_types');
    }
}
