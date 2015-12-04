<?php

namespace Seat\Web\Http\Controllers;

use App\Http\Controllers\Controller;

/**
 * Class HomeController
 * @package Seat\Web\Http\Controllers
 */
class HomeController extends Controller
{

    /**
     *
     * @return \Illuminate\View\View
     */
    public function getHome()
    {

        return view('web::home');
    }

}
