<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2021 Leon Jacobs
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
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Character\Intel\MailDataTable;
use Seat\Web\Http\DataTables\Scopes\CharacterMailScope;

/**
 * Class MailController.
 *
 * @package Seat\Web\Http\Controllers\Character
 */
class MailController extends Controller
{
    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
     * @param  \Seat\Web\Http\DataTables\Character\Intel\MailDataTable  $dataTable
     * @return mixed
     */
    public function index(CharacterInfo $character, MailDataTable $dataTable)
    {
        return $dataTable
            ->addScope(new CharacterMailScope(request()->input('characters', [])))
            ->render('web::character.mail', compact('character'));
    }

    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
     * @param  int  $mail_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(CharacterInfo $character, int $mail_id)
    {
        $mail = MailHeader::with('body', 'sender', 'recipients', 'recipients.entity')
            ->where('mail_id', $mail_id)
            ->first();

        return view('web::common.mails.modals.read.content', compact('mail'));
    }

    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character_id
     * @param  int  $message_id
     * @return array|string
     *
     * @throws \Throwable
     */
    public function getMailRead(CharacterInfo $character, int $message_id)
    {
        $message = $this->getCharacterMailMessage($character->character_id, $message_id);

        $from = $character ?: $message->from;

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
     * @param  int  $message_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getMailTimelineRead(int $message_id)
    {
        $message = $this->getCharacterMailTimeline($message_id);

        return view('web::character.mail-timeline-read', compact('message'));

    }

    /**
     * Get the mail timeline for all of the characters
     * a logged in user has access to. Either by owning the
     * api key with the characters, or having the correct
     * affiliation & role.
     *
     * Supplying the $message_id will return only that
     * mail.
     *
     * @param  int  $message_id
     * @return mixed
     */
    private function getCharacterMailTimeline(int $message_id = null)
    {
        // Get the User for permissions and affiliation
        // checks
        $user = auth()->user();
        $messages = MailHeader::with('body', 'recipients', 'recipients.entity', 'sender');

        // If a user is not a super user, only return their own mail and those
        // which they are affiliated to to receive.
        if (! $user->isAdmin()) {

            $messages = $messages->whereHas('recipients', function ($sub_query) {

                // TODO : add scope to MailHeader

                // collect metadata related to required permission
                $permissions = auth()->user()->roles()->with('permissions')->get()
                    ->pluck('permissions')
                    ->flatten()
                    ->filter(function ($permission) {
                        return $permission->title == 'character.mail';
                    });

                if ($permissions->filter(function ($permission) { return ! $permission->hasFilters(); })->isNotEmpty())
                    return $sub_query;

                // extract entity ids and group by entity type
                $map = $permissions->map(function ($permission) {
                    $filters = json_decode($permission->pivot->filters);

                    return [
                        'characters'   => collect($filters->character ?? [])->pluck('id')->toArray(),
                        'corporations' => collect($filters->corporation ?? [])->pluck('id')->toArray(),
                        'alliances'    => collect($filters->alliance ?? [])->pluck('id')->toArray(),
                    ];
                });

                // collect at least user owned characters
                $owned_range = auth()->user()->associatedCharacterIds();

                $characters_range = $map->pluck('characters')->flatten()->toArray();

                $corporations_range = CharacterInfo::whereHas('affiliation', function ($affiliation) use ($map) {
                    $affiliation->whereIn('corporation_id', $map->pluck('corporations')->flatten()->toArray());
                })->select('character_id')->get()->pluck('character_id')->toArray();

                $alliances_range = CharacterInfo::whereHas('affiliation', function ($affiliation) use ($map) {
                    $affiliation->whereIn('alliance_id', $map->pluck('alliances')->flatten()->toArray());
                })->select('character_id')->get()->pluck('character_id')->toArray();

                $character_ids = array_merge(
                    $characters_range, $corporations_range, $alliances_range, $owned_range,
                    $map->pluck('corporations')->flatten()->toArray(), $map->pluck('alliances')->flatten()->toArray());

                $sub_query->whereIn('recipient_id', $character_ids);
            });
        }

        // Filter by messageID if its set
        if (! is_null($message_id))
            return $messages->where('mail_id', $message_id)
                ->first();

        return $messages->select('mail_id', 'subject', 'from', 'timestamp')
            ->orderBy('timestamp', 'desc')
            ->distinct()
            ->paginate(25);
    }
}
