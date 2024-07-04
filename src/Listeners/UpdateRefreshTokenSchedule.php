<?php

namespace Seat\Web\Listeners;

use Carbon\Carbon;
use Seat\Eveapi\Models\RefreshToken;
use Seat\Eveapi\Models\RefreshTokenSchedule;
use Seat\Web\Events\UserRoleAdded;
use Seat\Web\Events\UserRoleRemoved;
use Seat\Web\Models\Acl\Role;

class UpdateRefreshTokenSchedule
{
    const DEFAULT_UPDATE_INTERVAL = 60 * 60;

    public function handle(UserRoleAdded|UserRoleRemoved $event): void
    {
        $user = $event->user();

        $update_interval = $user->roles()
            ->whereHas('character_scheduling_rule')
            ->get()
            ->min('character_scheduling_rule.update_interval') ?? self::DEFAULT_UPDATE_INTERVAL;

        $user->refresh_tokens()->with('token_schedule')->get()
            ->each(function (RefreshToken $token) use ($update_interval) {
                $schedule = $token->token_schedule;

                if($schedule === null) {
                    $schedule = new RefreshTokenSchedule();
                    $schedule->character_id = $token->character_id;
                    $schedule->last_update = Carbon::createFromTimestamp(0); // this field is not optional, therefore set it to the earliest date possible
                }

                $schedule->update_interval = $update_interval;
                $schedule->save();
            });
    }
}