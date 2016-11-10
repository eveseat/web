<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStandingsProfileStandingsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('standings_profile_standings', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('standings_profile_id');
            $table->enum('type', [
                'character', 'corporation', 'alliance'
            ]);
            $table->integer('elementID');
            $table->double('standing');

            $table->index('standings_profile_id');
            $table->index('elementID');
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

        Schema::dropIfExists('standings_profile_standings');
    }
}
