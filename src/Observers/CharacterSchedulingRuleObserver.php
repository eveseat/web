<?php

namespace Seat\Web\Observers;

use Seat\Web\Models\CharacterSchedulingRule;

class CharacterSchedulingRuleObserver
{
    public function saved(CharacterSchedulingRule $rule)
    {
        foreach ($rule->role->users as $affected_user) {
            CharacterSchedulingRule::updateUserRefreshTokenSchedule($affected_user);
        }
    }

    public function deleted(CharacterSchedulingRule $rule)
    {
        foreach ($rule->role->users as $affected_user) {
            CharacterSchedulingRule::updateUserRefreshTokenSchedule($affected_user);
        }
    }
}