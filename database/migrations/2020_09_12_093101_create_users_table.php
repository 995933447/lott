<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('status')->nullable()->comment('用户状态');
            $table->string('username')->comment('用户名');
            $table->string('password')->comment('密码');
            $table->string('pay_password')->comment('密码')->nullable();
            $table->string('game_password')->comment('游戏密码,预留')->nullable();
            $table->integer('agent_id')->comment('代理ID')->nullable();
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
        Schema::dropIfExists('users');
    }
}
