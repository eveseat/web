<?php
/*
This file is part of SeAT

Copyright (C) 2015, 2016  Leon Jacobs

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

namespace Seat\Web\Http\Controllers\Character;

use Seat\Services\Repositories\Character\Mail;
use Seat\Web\Http\Controllers\Controller;

class MailController extends Controller
{

    use Mail;

    /**
     * @param $character_id
     *
     * @return \Illuminate\View\View
     */
    public function getMail(int $character_id)
    {

        $mail = $this->getCharacterMail($character_id);

        return view('web::character.mail', compact('mail'));

    }

    /**
     * @param $character_id
     * @param $message_id
     *
     * @return \Illuminate\View\View
     */
    public function getMailRead(int $character_id, int $message_id)
    {

        $message = $this->getCharacterMailMessage($character_id, $message_id);

        return view('web::character.mail-read', compact('message'));

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getMailTimeline()
    {

        $messages = $this->getCharacterMailTimeline();

        return view('web::character.mail-timeline', compact('messages'));
    }

    /**
     * @param $message_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getMailTimelineRead(int $message_id)
    {

        $message = $this->getCharacterMailTimeline($message_id);

        return view('web::character.mail-timeline-read', compact('message'));

    }

}
