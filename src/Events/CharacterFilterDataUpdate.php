<?php

namespace Seat\Web\Events;

use Illuminate\Queue\SerializesModels;
use Seat\Web\Models\User;

/**
 * This event is fired when character filters, like used in squads, need to recompute because the data they are based on changed.
 */
class CharacterFilterDataUpdate
{
    use SerializesModels;

    public User $user;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}