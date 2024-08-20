<?php

namespace Seat\Web\Events;

use Illuminate\Queue\SerializesModels;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Web\Models\User;

/**
 * This event is fired when character filters, like used in squads, need to recompute because the data they are based on changed.
 */
class CharacterFilterDataUpdate
{
    use SerializesModels;

    public CharacterInfo $character;

    /**
     * @param User $user
     */
    public function __construct(CharacterInfo $character)
    {
        $this->character = $character;
    }
}