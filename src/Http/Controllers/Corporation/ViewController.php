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

namespace Seat\Web\Http\Controllers\Corporation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Seat\Services\Repositories\Corporation\CorporationRepository;
use Seat\Services\Repositories\Eve\EveRepository;
use Seat\Web\Validation\StarbaseModule;

/**
 * Class ViewController
 * @package Seat\Web\Http\Controllers\Corporation
 */
class ViewController extends Controller
{

    use CorporationRepository, EveRepository;

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAssets($corporation_id)
    {

        $assets = collect($this->getCorporationAssets($corporation_id));

        return view('web::corporation.assets', compact('assets'));
    }

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getBookmarks($corporation_id)
    {

        $bookmarks = collect($this->getCorporationBookmarks($corporation_id));

        return view('web::corporation.bookmarks', compact('bookmarks'));
    }

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getContacts($corporation_id)
    {

        $contacts = $this->getCorporationContacts($corporation_id);
        $labels = $this->getCorporationContactsLabels($corporation_id);

        return view('web::corporation.contacts', compact('contacts', 'labels'));
    }

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getContracts($corporation_id)
    {

        $contracts = collect($this->getCorporationContracts($corporation_id));

        return view('web::corporation.contracts', compact('contracts'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCorporations()
    {

        $corporations = $this->getAllCorporationsWithAffiliationsAndFilters();

        return view('web::corporation.list', compact('corporations'));

    }

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndustry($corporation_id)
    {

        $jobs = $this->getCorporationIndustry($corporation_id);

        return view('web::corporation.industry', compact('jobs'));
    }

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getKillmails($corporation_id)
    {

        $killmails = $this->getCorporationKillmails($corporation_id);

        return view('web::corporation.killmails', compact('killmails'));
    }

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getMarket($corporation_id)
    {

        $orders = $this->getCorporationMarketOrders($corporation_id);
        $states = $this->getEveMarketOrderStates();

        return view('web::corporation.market', compact('orders', 'states'));
    }

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPoco($corporation_id)
    {

        $pocos = $this->getCorporationCustomsOffices($corporation_id);

        return view('web::corporation.pocos', compact('pocos'));
    }

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getStandings($corporation_id)
    {

        $standings = $this->getCorporationStandings($corporation_id);

        return view('web::corporation.standings', compact('standings'));
    }

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getStarbases($corporation_id)
    {

        // The basic strategy here is that we will first try and get
        // as much information as possible about the starbases.
        // After that we will take the list of starbases and
        // attempt to determine the fuel usage as well as
        // the tower name as per the assets list.
        $starbases = $this->getCorporationStarbases($corporation_id);
        $starbase_states = $this->getEveStarbaseTowerStates();

        return view('web::corporation.starbases',
            compact('starbases', 'starbase_states'));
    }

    /**
     * @param \Seat\Web\Validation\StarbaseModule $request
     * @param                                     $corporation_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function postStarbaseModules(StarbaseModule $request, $corporation_id)
    {

        $starbase = $this->getCorporationStarbases($corporation_id, $request->starbase_id);
        $module_contents = $this->getCorporationAssetContents($corporation_id);

        return view('web::corporation.starbase.ajax.modules-tab',
            compact('starbase', 'module_contents'));

    }

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSummary($corporation_id)
    {

        $sheet = $this->getCorporationSheet($corporation_id);

        // Check if we managed to get any records for
        // this character. If not, redirect back with
        // an error.
        if (empty($sheet))
            return redirect()->back()
                ->with('error', trans('web::seat.unknown_corporation'));

        $divisions = $this->getCorporationDivisions($corporation_id);
        $wallet_divisions = $this->getCorporationWalletDivisions($corporation_id);

        return view('web::corporation.summary',
            compact('divisions', 'sheet', 'wallet_divisions'));

    }

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getTracking($corporation_id)
    {

        $tracking = $this->getCorporationMemberTracking($corporation_id);

        return view('web::corporation.tracking', compact('tracking'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param                          $corporation_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getJournal(Request $request, $corporation_id)
    {

        $journal = $this->getCorporationWalletJournal(
            $corporation_id, 50, $request);
        $transaction_types = $this->getEveTransactionTypes();

        return view('web::corporation.journal',
            compact('journal', 'transaction_types'));

    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param                          $corporation_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getTransactions(Request $request, $corporation_id)
    {

        $transactions = $this->getCorporationWalletTransactions(
            $corporation_id, 50, $request);

        return view('web::corporation.transactions', compact('transactions'));
    }

}
