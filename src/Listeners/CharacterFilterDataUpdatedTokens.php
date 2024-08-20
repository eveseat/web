<?php

namespace Seat\Web\Listeners;

use Seat\Web\Events\CharacterFilterDataUpdate;
use Seat\Web\Models\CharacterSchedulingRule;

class CharacterFilterDataUpdatedTokens
{
    public static function handle(CharacterFilterDataUpdate $update)
    {
        CharacterSchedulingRule::updateRefreshTokenSchedule($update->character->refresh_token);
    }
}