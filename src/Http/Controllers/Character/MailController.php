<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2020 Leon Jacobs
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
use Seat\Services\Repositories\Character\Mail;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Character\Intel\MailDataTable;
use Seat\Web\Http\DataTables\Scopes\CharacterScope;
use Seat\Web\Models\User;
use Yajra\DataTables\DataTables;

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
        $characters = (User::find($character_id))->group->users;

        return $dataTable
            ->addScope(new CharacterScope('character.mail', $character_id, request()->input('characters', [])))
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
     *
     * @return mixed
     * @throws \Exception
     */
    public function getMailData(int $character_id)
    {

        if (! request()->has('all_linked_characters'))
            return abort(500);

        if (request('all_linked_characters') === 'false')
            $character_ids = collect($character_id);

        $user_group = User::find($character_id)->group->users
            ->filter(function ($user) {

                return $user->name !== 'admin' && $user->id !== 1;
            })
            ->pluck('id');

        if (request('all_linked_characters') === 'true')
            $character_ids = $user_group;

        $mail = $this->getCharacterMail($character_ids);

        return DataTables::of($mail)
            ->editColumn('from', function ($row) {

                $character_id = $row->character_id;

                $character = CharacterInfo::find($row->from) ?: $row->from;

                return view('web::partials.character', compact('character', 'character_id'));
            })
            ->editColumn('subject', function ($row) {

                return view('web::character.partials.mailtitle', compact('row'));
            })
            ->editColumn('tocounts', function ($row) {

                return view('web::character.partials.mailtocounts', compact('row'));
            })
            ->addColumn('read', function ($row) {

                return view('web::character.partials.mailread', compact('row'));

            })
            ->rawColumns(['from', 'subject', 'tocounts', 'read'])
            ->make(true);

    }

    /**
     * @param int $character_id
     * @param int $message_id
     *
     * @return \Illuminate\View\View
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
