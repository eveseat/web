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

namespace Seat\Web\Http\Controllers;

use Seat\Web\Http\DataTables\Character\Financial\ContractDataTable;
use Seat\Web\Http\DataTables\Character\Financial\MarketDataTable;
use Seat\Web\Http\DataTables\Character\Financial\WalletJournalDataTable;
use Seat\Web\Http\DataTables\Character\Financial\WalletTransactionDataTable;
use Seat\Web\Http\DataTables\Character\Industrial\IndustryDataTable;
use Seat\Web\Http\DataTables\Character\Industrial\MiningDataTable;
use Seat\Web\Http\DataTables\Character\Industrial\PlanetaryInteractionDataTable;
use Seat\Web\Http\DataTables\Character\Industrial\ResearchDataTable;
use Seat\Web\Http\DataTables\Character\Intel\AssetDataTable;
use Seat\Web\Http\DataTables\Character\Intel\BookmarkDataTable;
use Seat\Web\Http\DataTables\Character\Intel\CalendarDataTable;
use Seat\Web\Http\DataTables\Character\Intel\MailDataTable;
use Seat\Web\Http\DataTables\Character\Intel\NotificationDataTable;
use Seat\Web\Http\DataTables\Character\Military\FittingDataTable;
use Seat\Web\Http\DataTables\Character\Military\KillMailDataTable;
use Seat\Web\Http\DataTables\Character\Military\StandingDataTable;
use Seat\Web\Http\DataTables\Scopes\CharacterScope;

/**
 * Class CharacterController
 *
 * @package Seat\Web\Http\Controllers
 */
class CharacterController extends Controller
{
    public function show(int $character_id)
    {

    }

    /**
     * @param \Seat\Web\Http\DataTables\Character\Intel\AssetDataTable $dataTable
     * @return mixed
     */
    public function assets(AssetDataTable $dataTable)
    {
        return $dataTable
            ->addScope(new CharacterScope())
            ->render('web::character.assets');
    }

    /**
     * @param \Seat\Web\Http\DataTables\Character\Intel\BookmarkDataTable $dataTable
     * @return mixed
     */
    public function bookmarks(BookmarkDataTable $dataTable)
    {
        return $dataTable
            ->addScope(new CharacterScope())
            ->render('web::character.bookmarks');
    }

    /**
     * @param \Seat\Web\Http\DataTables\Character\Intel\CalendarDataTable $dataTable
     * @return mixed
     */
    public function calendars(CalendarDataTable $dataTable)
    {
        return $dataTable
            ->addScope(new CharacterScope())
            ->render('web::character.calendar');
    }

    /**
     * @param \Seat\Web\Http\DataTables\Character\Financial\ContractDataTable $dataTable
     * @return mixed
     */
    public function contracts(ContractDataTable $dataTable)
    {
        return $dataTable
            ->addScope(new CharacterScope())
            ->render('web::character.contracts');
    }

    /**
     * @param \Seat\Web\Http\DataTables\Character\Military\FittingDataTable $dataTable
     * @return mixed
     */
    public function fittings(FittingDataTable $dataTable)
    {
        return $dataTable
            ->addScope(new CharacterScope())
            ->render('web::character.fittings');
    }

    /**
     * @param \Seat\Web\Http\DataTables\Character\Industrial\IndustryDataTable $dataTable
     * @return mixed
     */
    public function industries(IndustryDataTable $dataTable)
    {
        return $dataTable
            ->addScope(new CharacterScope())
            ->render('web::character.industry');
    }

    /**
     * @param \Seat\Web\Http\DataTables\Character\Military\KillMailDataTable $dataTable
     * @return mixed
     */
    public function kill_mails(KillmailDataTable $dataTable)
    {
        return $dataTable
            ->addScope(new CharacterScope())
            ->render('web::character.killmails');
    }

    /**
     * @param \Seat\Web\Http\DataTables\Character\Intel\MailDataTable $dataTable
     * @return mixed
     */
    public function mails(MailDataTable $dataTable)
    {
        return $dataTable
            ->addScope(new CharacterScope())
            ->render('web::character.mail');
    }

    /**
     * @param \Seat\Web\Http\DataTables\Character\Financial\MarketDataTable $dataTable
     * @return mixed
     */
    public function markets(MarketDataTable $dataTable)
    {
        return $dataTable
            ->addScope(new CharacterScope())
            ->render('web::character.market');
    }

    /**
     * @param \Seat\Web\Http\DataTables\Character\Industrial\MiningDataTable $dataTable
     * @return mixed
     */
    public function mining(MiningDataTable $dataTable)
    {
        return $dataTable
            ->addScope(new CharacterScope())
            ->render('web::character.mining-ledger');
    }

    /**
     * @param \Seat\Web\Http\DataTables\Character\Intel\NotificationDataTable $dataTable
     * @return mixed
     */
    public function notifications(NotificationDataTable $dataTable)
    {
        return $dataTable
            ->addScope(new CharacterScope())
            ->render('web::character.notifications');
    }

    /**
     * @param \Seat\Web\Http\DataTables\Character\Industrial\PlanetaryInteractionDataTable $dataTable
     * @return mixed
     */
    public function planetary_interactions(PlanetaryInteractionDataTable $dataTable)
    {
        return $dataTable
            ->addScope(new CharacterScope())
            ->render('web::character.pi');
    }

    /**
     * @param \Seat\Web\Http\DataTables\Character\Industrial\ResearchDataTable $dataTable
     * @return mixed
     */
    public function researches(ResearchDataTable $dataTable)
    {
        return $dataTable
            ->addScope(new CharacterScope())
            ->render('web::character.research');
    }

    public function sheet(int $character_id)
    {

    }

    public function skills(int $skills)
    {

    }

    /**
     * @param \Seat\Web\Http\DataTables\Character\Military\StandingDataTable $dataTable
     * @return mixed
     */
    public function standings(StandingDataTable $dataTable)
    {
        return $dataTable
            ->addScope(new CharacterScope())
            ->render('web::character.standings');
    }

    /**
     * @param \Seat\Web\Http\DataTables\Character\Financial\WalletJournalDataTable $dataTable
     * @return mixed
     */
    public function wallet_journals(WalletJournalDataTable $dataTable)
    {
        return $dataTable
            ->addScope(new CharacterScope())
            ->render('web::character.wallet.journal.journal');
    }

    /**
     * @param \Seat\Web\Http\DataTables\Character\Financial\WalletTransactionDataTable $dataTable
     * @return mixed
     */
    public function wallet_transactions(WalletTransactionDataTable $dataTable)
    {
        return $dataTable
            ->addScope(new CharacterScope())
            ->render('web::character.wallet.transactions.transactions');
    }
}
