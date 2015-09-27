<?php

namespace Seat\Web\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Seat\Web\Models\User;

/**
 * Class AuthController
 * @package Seat\Web\Http\Controllers\Auth
 */
class AuthController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * @var string
     */
    protected $redirectAfterLogout = '/home';

    /**
     * Create a new authentication controller instance.
     */
    public function __construct()
    {

        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        return Validator::make($data, [
            'name'     => 'required|max:255',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     *
     * @return User
     */
    protected function create(array $data)
    {

        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * Override to return the correct view
     *
     * @return \Illuminate\View\View
     */
    public function getLogin()
    {

        return view('web::auth.login');
    }

    /**
     * Override to return the correct view
     *
     * @return \Illuminate\View\View
     */
    public function getRegister()
    {

        return view('web::auth.register');

    }

    /**
     * Override to return the correct Language
     *
     * @return mixed
     */
    protected function getFailedLoginMessage()
    {

        return Lang::get('web::auth.failed');
    }

}
