<?php

namespace Seat\Web\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Support\Facades\Event;

/**
 * Class HomeController
 * @package Seat\Web\Http\Controllers
 */
class HomeController extends Controller
{

    /**
     * @return \Illuminate\View\View
     */
    public function getHome()
    {

        return view('web::home');
    }

}
