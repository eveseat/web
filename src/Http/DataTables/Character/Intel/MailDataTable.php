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

namespace Seat\Web\Http\DataTables\Character\Intel;

use Seat\Eveapi\Models\Mail\MailHeader;
use Yajra\DataTables\Services\DataTable;

/**
 * Class MailDataTable.
 *
 * @package Seat\Web\Http\DataTables\Character\Intel
 */
class MailDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->editColumn('timestamp', function ($row) {
                return view('web::partials.date', ['datetime' => $row->timestamp]);
            })
            ->editColumn('action', function ($row) {
                return view('web::common.mails.buttons.read', ['character_id' => $row->character_id, 'mail_id' => $row->mail_id]);
            })
            ->addColumn('sender', function ($row) {
                switch ($row->sender->category) {
                    case 'character':
                        return view('web::partials.character', ['character' => $row->from]);
                    case 'corporation':
                        return view('web::partials.corporation', ['corporation' => $row->from]);
                    case 'alliance':
                        return view('web::partials.alliance', ['alliance' => $row->from]);
                }

                return '';
            })
            ->addColumn('recipients', function ($row) {
                return view('web::common.mails.modals.read.tags', compact('row'));
            })
            ->filterColumn('sender', function ($query, $keyword) {
                return $query->whereHas('sender', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('name LIKE ?', ["%$keyword%"]);
                });
            })
            ->filterColumn('recipients', function ($query, $keyword) {
                return $query->whereHas('recipients.entity', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('name LIKE ?', ["%$keyword%"]);
                })->orWhereHas('recipients.mailing_list', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('name LIKE ?', ["%$keyword%"]);
                });
            })
            ->rawColumns(['timestamp', 'sender', 'subject', 'recipients', 'action'])
            ->make(true);
    }

    /**
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->postAjax()
            ->columns($this->getColumns())
            ->addAction()
            ->parameters([
                'drawCallback' => 'function () { $("img").unveil(100); ids_to_names(); }',
            ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return MailHeader::with('sender', 'body', 'recipients', 'recipients.entity', 'recipients.mailing_list');
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return [
            ['data' => 'timestamp', 'title' => trans('web::mail.date')],
            ['data' => 'sender', 'title' => trans('web::mail.sender'), 'orderable' => false],
            ['data' => 'subject', 'title' => trans('web::mail.subject')],
            ['data' => 'recipients', 'title' => trans('web::mail.recipients')],
            ['data' => 'body.body', 'visible' => false],
        ];
    }
}
