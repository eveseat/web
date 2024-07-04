<?php

namespace Seat\Web\Listeners;

use Carbon\Carbon;
use Seat\Eveapi\Models\RefreshToken;
use Seat\Eveapi\Models\RefreshTokenSchedule;
use Seat\Web\Events\UserRoleAdded;
use Seat\Web\Events\UserRoleRemoved;
use Seat\Web\Models\Acl\Role;
use Seat\Web\Models\CharacterSchedulingRule;

class UpdateRefreshTokenSchedule
{
    public function handle(UserRoleAdded|UserRoleRemoved $event): void
    {
        CharacterSchedulingRule::updateUserRefreshTokenSchedule($event->user());
    }
}