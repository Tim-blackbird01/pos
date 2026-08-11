<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Utils\BusinessUtil;
use App\Utils\ModuleUtil;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Rules\ReCaptcha;


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
     * All Utils instance.
     */
    protected $businessUtil;

    protected $moduleUtil;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(BusinessUtil $businessUtil, ModuleUtil $moduleUtil)
    {
        $this->middleware('guest')->except('logout');
        $this->businessUtil = $businessUtil;
        $this->moduleUtil = $moduleUtil;
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Accept either a username or an email address on the login form.
     *
     * @return void
     */
    public function username()
    {
        return 'login';
    }

    /**
     * Determine which user column should be used for the supplied login value.
     *
     * @return array
     */
    protected function credentials(Request $request)
    {
        $login = $request->input($this->username());
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        return [$field => $login, 'password' => $request->input('password')];
    }

    public function logout()
    {
        $this->businessUtil->activityLog(auth()->user(), 'logout');

        request()->session()->flush();
        \Auth::logout();

        return redirect('/login');
    }

    /**
     * The user has been authenticated.
     * Check if the business is active or not.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        $this->businessUtil->activityLog($user, 'login', null, [], false, $user->business_id);

        if (! $user->business->is_active) {
            \Auth::logout();

            return redirect('/login')
              ->with(
                  'status',
                  ['success' => 0, 'msg' => __('lang_v1.business_inactive')]
              );
        } elseif ($user->status != 'active') {
            \Auth::logout();

            return redirect('/login')
              ->with(
                  'status',
                  ['success' => 0, 'msg' => __('lang_v1.user_inactive')]
              );
        } elseif (! $user->allow_login) {
            \Auth::logout();

            return redirect('/login')
                ->with(
                    'status',
                    ['success' => 0, 'msg' => __('lang_v1.login_not_allowed')]
                );
        } elseif (($user->user_type == 'user_customer') && ! $this->moduleUtil->hasThePermissionInSubscription($user->business_id, 'crm_module')) {
            \Auth::logout();

            return redirect('/login')
                ->with(
                    'status',
                    ['success' => 0, 'msg' => __('lang_v1.business_dont_have_crm_subscription')]
                );
        }
    }

    protected function redirectTo()
    {
        $user = \Auth::user();
        if (! $user->can('dashboard.data') && $user->can('sell.create')) {
            return '/pos/create';
        }

        if ($user->user_type == 'user_customer') {
            return 'contact/contact-dashboard';
        }

        return '/home';
    }

    public function validateLogin(Request $request)
    {
        if(config('constants.enable_recaptcha')){
            $this->validate($request, [
                $this->username() => 'required|string',
                'password' => 'required|string',
                'g-recaptcha-response' => ['required', new ReCaptcha]
            ]);
        }else{
            $this->validate($request, [
                $this->username() => 'required|string',
                'password' => 'required|string',
            ]);
        }
       
    }

    /**
     * Redirect the user to Google to authorize sign-in.
     *
     * Google accounts are matched only to existing POS users by their verified
     * email address; this endpoint never creates a user or grants a role.
     */
    public function redirectToGoogle(Request $request)
    {
        if (empty(config('services.google.client_id')) || empty(config('services.google.client_secret'))) {
            return redirect()->route('login')->withErrors([
                'login' => 'Google sign-in is not configured yet.',
            ]);
        }

        $state = Str::random(40);
        $request->session()->put('google_oauth_state', $state);

        return redirect()->away('https://accounts.google.com/o/oauth2/v2/auth?'.http_build_query([
            'client_id' => config('services.google.client_id'),
            'redirect_uri' => $this->googleRedirectUri(),
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'state' => $state,
            'prompt' => 'select_account',
        ]));
    }

    /**
     * Complete the Google OAuth flow and sign in a matching active user.
     */
    public function handleGoogleCallback(Request $request)
    {
        $state = $request->session()->pull('google_oauth_state');
        if (empty($state) || ! hash_equals($state, (string) $request->input('state'))) {
            return redirect()->route('login')->withErrors(['login' => 'Google sign-in could not be verified. Please try again.']);
        }

        if ($request->filled('error') || ! $request->filled('code')) {
            return redirect()->route('login')->withErrors(['login' => 'Google sign-in was cancelled or denied.']);
        }

        try {
            $token = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'code' => $request->input('code'),
                'client_id' => config('services.google.client_id'),
                'client_secret' => config('services.google.client_secret'),
                'redirect_uri' => $this->googleRedirectUri(),
                'grant_type' => 'authorization_code',
            ])->throw()->json('access_token');

            $googleUser = Http::withToken($token)
                ->get('https://openidconnect.googleapis.com/v1/userinfo')
                ->throw()
                ->json();
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()->route('login')->withErrors(['login' => 'Unable to complete Google sign-in. Please try again.']);
        }

        $email = $googleUser['email'] ?? null;
        if (empty($email) || empty($googleUser['email_verified'])) {
            return redirect()->route('login')->withErrors(['login' => 'A verified Google email address is required to sign in.']);
        }

        $user = User::where('email', $email)->first();
        if (empty($user)) {
            return redirect()->route('login')->withErrors(['login' => 'No POS user is registered with this Google email address.']);
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        if ($response = $this->authenticated($request, $user)) {
            return $response;
        }

        return redirect()->intended($this->redirectPath());
    }

    private function googleRedirectUri()
    {
        return config('services.google.redirect') ?: route('auth.google.callback');
    }

}
