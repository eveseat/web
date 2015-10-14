<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSecurityLogsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('security_logs', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->text('message');
            $table->string('category')->nullable();

            // Indexes
            $table->index('category');

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

        Schema::drop('security_logs');
    }
}
