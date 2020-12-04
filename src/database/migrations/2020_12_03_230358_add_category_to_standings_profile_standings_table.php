<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class AddTypeToStandingsProfileStandingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('standings_profile_standings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('category')->nullable()->after('entity_id')->default(null);
        });

        $table = DB::table('standings_profile_standings')
            ->select('*')->get();

        $output = new ConsoleOutput();
        $progress = new ProgressBar($output, count($table));

        foreach ($table as $row) {
            $universe_name = DB::table('universe_names')
                ->where('entity_id', $row->entity_id)->first();

            if ($universe_name) {
                DB::table('standings_profile_standings')
                    ->where('entity_id', $row->entity_id)
                    ->update([
                        'category' => $universe_name->category
                    ]);
            }

            $progress->advance();
        }

        $progress->finish();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('standings_profile_standings', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
