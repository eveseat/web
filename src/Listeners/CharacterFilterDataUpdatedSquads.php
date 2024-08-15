<?php

namespace Seat\Web\Listeners;

use Seat\Web\Events\CharacterFilterDataUpdate;
use Seat\Web\Models\Squads\Squad;

class CharacterFilterDataUpdatedSquads
{
    public static function handle(CharacterFilterDataUpdate $event)
    {
        $user = $event->user;

        $member_squads = $user->squads;

        // retrieve all auto squads from which the user is not already a member.
        $other_squads = Squad::where('type', 'auto')->whereDoesntHave('members', function ($query) use ($user) {
            $query->where('id', $user->id);
        })->get();

        // remove the user from squads to which he's non longer eligible.
        $member_squads->each(function (Squad $squad) use ($user) {
            if (! $squad->isUserEligible($user))
                $squad->members()->detach($user->id);
        });

        // add the user to squads from which he's not already a member.
        $other_squads->each(function (Squad $squad) use ($user) {
            if ($squad->isUserEligible($user))
                $squad->members()->save($user);
        });
    }
}