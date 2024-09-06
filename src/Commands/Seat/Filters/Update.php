<?php

namespace Seat\Web\Commands\Seat\Filters;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Seat\Web\Jobs\UpdateCharacterFilters;

class Update extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seat:filters:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs squad and character scheduling rule updates';

    /**
     * Run the command
     * @return void
     */
    public function handle(): void
    {
        UpdateCharacterFilters::dispatch()->onQueue('high');
        $this->line('Scheduled character filter updates for all characters!');
    }
}