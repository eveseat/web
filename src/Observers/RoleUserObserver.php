<?php

namespace Seat\Web\Observers;

use Seat\Web\Models\Acl\RoleUser;
use Seat\Web\Models\CharacterSchedulingRule;

class RoleUserObserver
{
    public function saved(RoleUser $roleUser): void
    {
        CharacterSchedulingRule::updateUserRefreshTokenSchedule($roleUser->user);
    }

    public function deleted(RoleUser $roleUser): void
    {
        CharacterSchedulingRule::updateUserRefreshTokenSchedule($roleUser->user);
    }
}