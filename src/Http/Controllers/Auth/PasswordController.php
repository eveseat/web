<?php

namespace Seat\Web\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

/**
 * Class PasswordController
 * @package Seat\Web\Http\Controllers\Auth
 */
class PasswordController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Create a new password controller instance.
     */
    public function __construct()
    {

        $this->middleware('guest');
    }

    /**
     * Override to return the correct view
     *
     * @return \Illuminate\View\View
     */
    public function getEmail()
    {

        return view('web::auth.password');
    }

    /**
     * Override to return the correct view
     *
     * @param null $token
     *
     * @return $this
     * @throws \Seat\Web\Http\Controllers\Auth\NotFoundHttpException
     */
    public function getReset($token = null)
    {

        if (is_null($token)) {
            throw new NotFoundHttpException;
        }

        return view('web::auth.reset')->with('token', $token);
    }
}
