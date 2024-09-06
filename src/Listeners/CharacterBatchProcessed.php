<?php

namespace Seat\Web\Listeners;

use Seat\Web\Events\CharacterFilterDataUpdate;
use Seat\Eveapi\Events\CharacterBatchProcessed as BatchEvent;

class CharacterBatchProcessed
{
    public static function handle(BatchEvent $event)
    {
        event(new CharacterFilterDataUpdate($event->character));
    }
}