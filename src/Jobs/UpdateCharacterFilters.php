<?php

namespace Seat\Web\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Web\Events\CharacterFilterDataUpdate;

class UpdateCharacterFilters implements ShouldQueue
{
    use Queueable, InteractsWithQueue, Dispatchable;

    public function tags()
    {
        return ["web", "filters"];
    }

    /**
     * Go over all character and trigger a character filter update
     *
     * @return void
     */
    public function handle()
    {
        $characters = CharacterInfo::all();
        foreach ($characters as $character){
            event(new CharacterFilterDataUpdate($character));
        }
    }
}