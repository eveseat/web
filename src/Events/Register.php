<?php
/**
 * Created by PhpStorm.
 *  * User: Herpaderp Aldent
 * Date: 12.04.2018
 * Time: 20:56
 */

namespace Seat\Web\Events;

use Illuminate\Auth\Events\Registered as RegisterEvent;
use Illuminate\Support\Facades\Artisan;


class Register
{
    public static function handle(RegisterEvent $event)
    {
        Artisan::call('esi:update:characters', [
            'character_id' => $event->user->character_id
        ]);
    }
}