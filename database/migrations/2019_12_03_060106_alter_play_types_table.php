<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPlayTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('play_types', 'odd_types');
        Schema::rename('lottery_play_types', 'lottery_odd_types');

        Schema::table('lottery_odd_types', function (Blueprint $table) {
            $table->dropColumn('play_type_id');
            $table->integer('odd_type_id')->comment('投注类型ID');
        });

        Schema::table('bet_orders', function (Blueprint $table) {
            $table->dropColumn('play_type_id');
            $table->integer('odd_type_id')->comment('投注类型ID');

            $table->dropColumn('play_type_code');
            $table->string('odd_type_code')->comment('投注类型代码');
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
