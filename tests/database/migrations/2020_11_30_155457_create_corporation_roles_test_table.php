<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCorporationRolesTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corporation_roles', function (Blueprint $table) {

            $table->bigInteger('corporation_id');
            $table->bigInteger('character_id');
            $table->enum('type', [
                'roles',
                'grantable_roles',
                'roles_at_hq',
                'grantable_roles_at_hq',
                'roles_at_base',
                'grantable_roles_at_base',
                'roles_at_other',
                'grantable_roles_at_other',
            ]);
            $table->string('role');

            $table->primary(['corporation_id', 'character_id', 'type', 'role'],
                'corporation_roles_primary_key');
            $table->index('corporation_id');
            $table->index('character_id');
            $table->index('role');
            $table->index(['type', 'role']);

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
        Schema::dropIfExists('corporation_roles_test');
    }
}
