<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveMainCharacterFromGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('groups', function (Blueprint $table) {

            $table->dropColumn('main_character_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('groups', function (Blueprint $table) {

            $table->string('main_character_id')->after('id')->nullable();
        });
    }
}
