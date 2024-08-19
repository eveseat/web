<?php

use \Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('character_scheduling_rules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("name");
            $table->integer("interval")->unsigned();
            $table->json("filter");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('character_scheduling_rules');
    }
};