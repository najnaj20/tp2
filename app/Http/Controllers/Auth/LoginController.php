<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function sendFailedLoginResponse(Request $request)
    {
    $request->session()->put('login_error', trans('auth.failed'));

    throw ValidationException::withMessages([
        $this->username() => [trans('auth.failed')],
    ]);
    }

    public function login(Request $request)
    {
    $request->validate([
        $this->username() => 'required|string',
        'password' => 'required|string',
    ]);

    $throttles = $this->isUsingThrottlesLoginsTrait();
    if ($throttles && $this->hasTooManyLoginAttempts($request)) {
        $this->fireLockoutEvent($request);
        return $this->sendLockoutResponse($request);
    }

    if (Auth::attempt([
        'email' => $request->email,
        'password' => $request->password
    ], $request->get('remember'))) {
        return $this->sendLoginResponse($request);
    }

    if ($throttles) {
        $this->incrementLoginAttempts($request);
    }

    return $this->sendFailedLoginResponse($request);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
    $request->session()->put('login_error', trans('auth.failed'));

    throw ValidationException::withMessages([
        $this->username() => [trans('auth.failed')],
        'g-recaptcha-response' => ['Captcha tidak valid.'],
    ]);
    }

    public function login(Request $request)
    {
    $request->validate([
        $this->username() => 'required|string',
        'password' => 'required|string',
        'g-recaptcha-response' => 'required|recaptcha',
    ]);

    $throttles = $this->isUsingThrottlesLoginsTrait();
    if ($throttles && $this->hasTooManyLoginAttempts($request)) {
        $this->fireLockoutEvent($request);
        return $this->sendLockoutResponse($request);
    }

    if (Auth::attempt([
        'email' => $request->email,
        'password' => $request->password
    ], $request->get('remember'))) {
        return $this->sendLoginResponse($request);
    }

    if ($throttles) {
        $this->incrementLoginAttempts($request);
    }

    return $this->sendFailedLoginResponse($request);

}
