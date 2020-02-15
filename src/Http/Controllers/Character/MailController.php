<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018, 2019  Leon Jacobs
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

namespace Seat\Web\Http\Controllers\Character;

use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Eveapi\Models\Mail\MailHeader;
use Seat\Eveapi\Models\RefreshToken;
use Seat\Services\Repositories\Character\Mail;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Character\Intel\MailDataTable;
use Seat\Web\Http\DataTables\Scopes\CharacterMailScope;
use Seat\Web\Models\User;

/**
 * Class MailController.
 * @package Seat\Web\Http\Controllers\Character
 */
class MailController extends Controller
{
    use Mail;

    /**
     * @param int $character_id
     * @param \Seat\Web\Http\DataTables\Character\Intel\MailDataTable $dataTable
     * @return mixed
     */
    public function index(int $character_id, MailDataTable $dataTable)
    {
        $token = RefreshToken::where('character_id', $character_id)->first();
        $characters = collect();
        if ($token) {
            $characters = User::with('characters')->find($token->user_id)->characters;
        }

        return $dataTable
            ->addScope(new CharacterMailScope($character_id, request()->input('characters', [])))
            ->render('web::character.mail', compact('characters'));

    }

    /**
     * @param int $character_id
     * @param int $mail_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(int $character_id, int $mail_id)
    {
        $mail = MailHeader::with('body', 'sender', 'recipients', 'recipients.entity')
            ->where('mail_id', $mail_id)
            ->first();

        return view('web::common.mails.modals.read.content', compact('mail'));
    }

    /**
     * @param int $character_id
     * @param int $message_id
     * @return array|string
     * @throws \Throwable
     */
    public function getMailRead(int $character_id, int $message_id)
    {

        $message = $this->getCharacterMailMessage($character_id, $message_id);

        $from = CharacterInfo::find($message->from) ?: $message->from;

        $characters = $message
            ->recipients
            ->where('recipient_type', 'character')
            ->map(function ($recipient) {

                return CharacterInfo::find($recipient->recipient_id) ?: $recipient->recipient_id;
            });

        $corporations = $message
            ->recipients
            ->where('recipient_type', 'corporation')
            ->map(function ($recipient) {

                return CorporationInfo::find($recipient->recipient_id) ?: $recipient->recipient_id;
            });

        return view('web::character.mail-read', compact('message', 'from', 'characters', 'corporations'))
            ->render();

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
     * @param int $message_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getMailTimelineRead(int $message_id)
    {

        $message = $this->getCharacterMailTimeline($message_id);

        return view('web::character.mail-timeline-read', compact('message'));

    }
}
