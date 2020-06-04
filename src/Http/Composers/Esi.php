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

namespace Seat\Web\Http\Composers;

use Illuminate\Contracts\View\View;
use Seat\Eveapi\Models\Status\EsiStatus;
use Seat\Eveapi\Traits\RateLimitsEsiCalls;
use Seat\Services\Repositories\Configuration\UserRespository;

/**
 * Class Esi.
 * @package Seat\Web\Http\Composers
 */
class Esi
{
    use UserRespository, RateLimitsEsiCalls;

    /**
     * Bind data to the view.
     *
     * @param  View $view
     *
     * @return void
     * @throws \Exception
     */
    public function compose(View $view)
    {

        $view->with('esi_status', EsiStatus::latest()->first());
        $view->with('is_rate_limited', $this->isEsiRateLimited());
        $view->with('rate_limit_ttl', $this->getRateLimitKeyTtl());
    }
}
