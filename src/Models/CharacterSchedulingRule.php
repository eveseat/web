<?php

namespace Seat\Web\Models;

use Carbon\Carbon;
use Seat\Eveapi\Models\RefreshToken;
use Seat\Eveapi\Models\RefreshTokenSchedule;
use Seat\Services\Models\ExtensibleModel;
use Seat\Web\Models\Acl\Role;

/**
 * @property int $id
 * @property int $role_id
 * @property int update_interval
 *
 * @property Role $role
 */
class CharacterSchedulingRule extends ExtensibleModel
{
    const DEFAULT_UPDATE_INTERVAL = 60 * 60;

    public $timestamps = false;
    protected $primaryKey = "role_id";
    public $incrementing = false;

    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public static function updateUserRefreshTokenSchedule(User $user): void
    {
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