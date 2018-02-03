<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('group_user', function (Blueprint $table) {

            $table->bigInteger('user_id');
            $table->integer('group_id');

            $table->index('user_id');
            $table->index('group_id');

            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');

            // TODO: Fix constraint so that we can have a cascaded delete
//            $table->foreign('group_id')->references('id')
//                ->on('groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('user_groups');
    }
}
