<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddAffiliationInvertionColumn extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function($table) {
            $table->boolean('invertedAffiliations')->default(false);
        });
    }
    
     /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('roles', function($table) {
            $table->dropColumn('invertedAffiliations');
        });
    }

}