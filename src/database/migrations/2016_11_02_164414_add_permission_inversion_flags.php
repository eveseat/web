<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPermissionInversionFlags extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('role_user', function (Blueprint $table) {

            $table->boolean('not')->after('user_id')->default(false);
        });

        Schema::table('permission_role', function (Blueprint $table) {

            $table->boolean('not')->after('role_id')->default(false);
        });

        Schema::table('affiliation_user', function (Blueprint $table) {

            $table->boolean('not')->after('affiliation_id')->default(false);
        });

        Schema::table('affiliation_role', function (Blueprint $table) {

            $table->boolean('not')->after('affiliation_id')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('role_user', function (Blueprint $table) {

            $table->dropColumn('not');
        });

        Schema::table('permission_role', function (Blueprint $table) {

            $table->dropColumn('not');
        });

        Schema::table('affiliation_user', function (Blueprint $table) {

            $table->dropColumn('not');
        });

        Schema::table('affiliation_role', function (Blueprint $table) {

            $table->dropColumn('not');
        });
    }
}
