<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserLoginHistoriesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('user_login_histories', function (Blueprint $table) {

            $table->integer('user_id');
            $table->string('source');
            $table->string('user_agent');
            $table->enum('action', ['login', 'logout', 'failed']);

            // Indexes
            $table->index('user_id');

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

        Schema::drop('user_login_histories');
    }
}
