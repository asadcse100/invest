<?php


namespace App\Http\Controllers;

use App\Enums\UserRoles;
use App\Enums\UserStatus;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Jobs\ProcessEmail;

use App\Models\User;
use App\Models\UserMeta;
use App\Models\VerifyToken;
use App\Services\AuthService;
use App\Services\SettingsService;
use App\Services\Apis\RecaptchaService;
use App\Services\Transaction\TransactionService;
use App\Services\MaintenanceService as MService;
use App\Services\ReferralService;
use App\Services\SocialAuth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class  AuthController extends Controller
{
    /**
     * @var AuthService
     */
    private $auth;
    private $settingsService;
    private $referralService;

    public function __construct(
        AuthService $authService,
        SettingsService $settingsService,
        ReferralService $referralService
    )
    {
        $this->auth = $authService;
        $this->settingsService = $settingsService;
        $this->referralService = $referralService;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function registerForm()
    {
        $installer = $this->isInstalled();
        if (!empty($installer)) {
            return redirect()->route($installer);
        }

        if (disabled_signup()) {
            return redirect()->route('auth.login.form')->withErrors(['notice' => __('New registration is not allowed. Please feel free to contact us for more information.')]);
        }
        $user_counts = User::count();
        $countries = filtered_countries();
        return view('auth.register', compact('user_counts', 'countries'));
    }

    /**
     * @param RegistrationRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     * @version 1.0.0
     * @since 1.0
     */
    public function register(RegistrationRequest $request)
    {
        if (has_recaptcha()) {
            RecaptchaService::verify($request);
        }

        $emailMetaCount = $this->settingsService->emailMetaCount($request->email);
        if ($emailMetaCount > 0) {
            throw ValidationException::withMessages(['email' => __("The chosen email is already registered with us. Please use a different email address.")]);
        }
        
        DB::beginTransaction();
        try {
            $data = array_map('strip_tags_map', $request->only('name', 'confirmation')) + $request->all();
            $user = $this->auth->createUser($data);
            if (!$user) {
                throw ValidationException::withMessages([
                    'invalid' => __('An error occurred during registration, please try again later. If the issues continues, contact us.')
                ]);
            }

            if (User::count() > 1 && mandatory_verify()) {
                ProcessEmail::dispatch('users-confirm-email', $user);
            }

            DB::commit();

            if (in_array($user->role, [UserRoles::ADMIN, UserRoles::SUPER_ADMIN])) {
                return redirect()->route('auth.login.form');
            }
            return redirect()->route('auth.confirm')->with(['email' => $user->email]);

        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'invalid' => __('Sorry, due to technical issues we unable to proceed. Please try again after sometimes or contact us.')
            ]);
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @version 1.0.0
     * @since 1.0
     */
    public function confirm(Request $request)
    {
        if (blank($request->session()->get('email'))) {
            Auth::logout();
            return redirect()->route('auth.login.form');
        }

        return view('auth.confirm');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @version 1.0.0
     * @since 1.0
     */
    public function loginForm()
    {
        $installer = $this->isInstalled();
        if (!empty($installer)) {
            return redirect()->route($installer);
        }

        $users = User::count();
        if (!$users > 0) {
            return redirect()->route('auth.register.form', ['setup' => 'administrator']);
        }
        session()->forget('user_2fa');

        return view('auth.login');
    }

    /**
     * @param LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     * @version 1.0.0
     * @since 1.0
     */
    public function login(LoginRequest $request)
    {          
        $user = User::where('email', $request->input('email'))->first();        
        $remember = $request->get('remember') == 'on';
        $password = $request->input('password');
        if (has_recaptcha()) {
            RecaptchaService::verify($request, $user);
        }

        if (!$user) {
            throw ValidationException::withMessages(['email' => __('The email address or password you entered is incorrect or the account does not exist.')]);
        }

        if ($user->status == UserStatus::SUSPEND) {
            throw ValidationException::withMessages(['invalid' => __('We are sorry, this account has been temporarily suspended. Please contact us for assistance.')]);
        }
        
        // if (mandatory_verify() && $user->role != UserRoles::SUPER_ADMIN) {

        //     if ($user->is_verified) {
        //         if ($user->status != UserStatus::ACTIVE) {
        //             throw ValidationException::withMessages(['email' => __('We are sorry, this account may locked out or not active. Please contact us for assistance.')]);
        //         }
        //     } else {
        //         session()->put('verification_required', $user);
        //         return redirect()->route('auth.email.verification');
        //     }
        // }

        if (!$this->auth->checkPassword($user, $password)) {
            $this->countInvalidAttempt($request, $user);
            throw ValidationException::withMessages(['email' => __('The email address or password you entered is incorrect or the account does not exist.')]);
        }

        if (!empty($user['2fa'])) {
            session()->put('user_2fa', ['user' => $user, 'remember' => $remember]);
            return redirect()->route('auth.2fa.form');
        }

        $this->auth->loginUser($user, $remember);

        return $this->redirectUser($user);
    }

    private function countInvalidAttempt(Request $request, User $user)
    {
        if ($request->session()->missing('invalidAttempts')) {
            session(['invalidAttempts' => 1]);
        } else {
            $request->session()->increment('invalidAttempts', $incrementBy = 1);
        }

        if (session('invalidAttempts') >= 5) {
            $request->session()->forget('invalidAttempts');
            ProcessEmail::dispatch('users-unusual-login', $user);
        }
    }

    private function redirectUser(User $user)
    {
        $welcome = false;
        if(empty($user->meta('first_login_at'))) {
            $user->user_metas()->create(['meta_key' => 'first_login_at', 'meta_value' => now()]);
            $welcome = true;
        }

        if (!system_admin_setup() && $user->role == UserRoles::SUPER_ADMIN) {
            $welcome = true;
        }
        // dd($user->role);
        // if (in_array($user->role, [UserRoles::ADMIN, UserRoles::SUPER_ADMIN])) {
        //     if ($welcome && $user->role == UserRoles::SUPER_ADMIN) {
        //         return redirect()->route('admin.quick-setup');
        //     }
        //     return redirect()->route('admin.dashboard');
        // }
        // return redirect()->route('account.welcome');
        // if ($user->role === UserRoles::USER) {
            
        if ($user->role === 'user') {
            if ($welcome) {
                return redirect()->route('account.welcome');
            }
            return redirect()->route('dashboard');
        }
        return redirect()->route('dashboard');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @version 1.0.0
     * @since 1.0
     */
    public function verifyEmail(Request $request)
    {
        if(Auth::check()) Auth::logout();

        if (!$request->filled('token')) {
            return redirect()->route('auth.login.form')->withErrors(['error' => __('We are sorry, the verification link is invalid.')]);
        }

        $verifyToken = $this->getVerifyToken($request);

        if (empty($verifyToken)) {
            return redirect()->route('auth.login.form')->withErrors(['error' => __('Sorry, we are unable to verify your identity. Your verification code does not match or invalid.')]);
        }

        $user = User::find($verifyToken->user_id);

        if (empty($user)) {
            return redirect()->route('auth.login.form')->withErrors(['error' => __('Email verification failed! The verification code does not match or invalid. Contact us, if you are still having trouble verifying your email.')]);
        }

        if (Carbon::now()->diffInMinutes($verifyToken->updated_at) > 30) {

            try {
                $this->auth->generateNewToken($user);
                ProcessEmail::dispatch('users-confirm-email', $user);
                $messages['warning'] = __('Sorry, the verification link has been expired! We have sent new verification link to your email.');
            } catch (\Exception $e) {
                save_mailer_log($e, 'users-confirm-email');
                $messages['warning'] = __('Sorry, the verification link has been expired. We are unable to send you new email at the moment. Please contact us via email (:mail) if the problem persist.', ['mail' => sys_settings('site_email')]);
            }

            return redirect()->route('auth.login.form')->withErrors($messages);
        }

        if ($this->getVerifyEmail($request) !== $verifyToken->email_md5) {
            return redirect()->route('auth.login.form')->withErrors(['error' => __('Sorry, the verification code is invalid or does not match with your identity.')]);
        }

        if ($user->is_verified) {
            return redirect()->route('auth.login.form')->withErrors(['info' => __('Your email address is already verified. You can login into your account.')]);
        }

        return $this->wrapInTransaction(function ($user, $verifyToken) {
            $verifyToken->verify = Carbon::now();
            $verifyToken->save();

            $user->status = UserStatus::ACTIVE;
            $user->save();

            if (referral_system() && !empty($user->refer)) {
                $this->referralService->createReferral($user);
            }

            $this->auth->saveEmailVerificationMeta($user);

            try {
                ProcessEmail::dispatch('users-welcome-email', $user);
            } catch (\Exception $e) {
                save_mailer_log($e, 'users-welcome-email');
            }
            
            return view('auth.verified');
        }, $user, $verifyToken);
    }

    public function accountVerification()
    {
        $user = session()->get('verification_required');

        if (empty($user)){
            return redirect()->route('auth.login.form');
        }

        $email = $user->email;

        return view('auth.verify', compact('email'));
    }

    public function resendVerifyEmail()
    {
        $user = session()->get('verification_required');

        if(empty($user)){
            return redirect()->route('auth.login.form');
        }

        $this->sendVerificationEmail($user);

        session()->forget('verification_required');

        return redirect()->route('auth.login.form');
    }



    public function updateEmailAndVerify(Request $request)
    {
        $user = session()->get('verification_required');

        if(empty($user)){
            return redirect()->route('auth.login.form');
        }

        $request->validate([
            'email' => 'email|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,9}$/ix|required|max:190|not_in:'. $user->email . '|unique:users,email,'.$user->id
        ], [
            'email.required' => __("Please enter a valid email address."),
            'email.email' => __("Please enter a valid email address."),
            'email.regex' => __("Please enter a valid email address."),
            'email.not_in' => __("The new email address cannot be the same as current address."),
            'email.unique' => __("An account with the given email already exists."),
        ]);

        $emailMetaCount = $this->settingsService->emailMetaCount($request->email);
        if ($emailMetaCount > 0) {
            throw ValidationException::withMessages(['email' => __("The chosen email is already registered with us. Please use a different email address.")]);
        }

        $user->update(['email' => $request->email]);

        $this->sendVerificationEmail($user);

        session()->forget('verification_required');

        return redirect()->route('auth.login.form');

    }

    private function sendVerificationEmail($user)
    {
        try {
            $this->auth->generateNewToken($user);
            ProcessEmail::dispatch('users-confirm-email', $user);
            session()->flash('mail_sent_success', __('We have emailed you a confirmation link to your email. Please check your inbox and confirm.'));
        }catch (\Exception $e){
            save_mailer_log($e, 'users-confirm-email');
            throw ValidationException::withMessages([ 'email' => __('We are unable to send the verification link to your email. If you continue to having trouble? Please contact us via email at :mail to resolved.', ['mail' => sys_settings('site_email')]) ]);
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @version 1.0.0
     * @since 1.0
     */
    public function verifyEmailUpdate(Request $request)
    {
        if (!$request->filled('token')) {
            return redirect()->route('auth.login.form')->withErrors(['error' => __('We are sorry, the verification link is invalid.')]);
        }

        $verifyToken = $this->getVerifyToken($request);

        if (empty($verifyToken)) {
            return redirect()->route('auth.login.form')->withErrors(['error' => __('Sorry, we are unable to verify email address. Your verification code does not match or invalid.')]);
        }

        if (Carbon::now()->diffInMinutes($verifyToken->updated_at) > 30) {
            return redirect()->route('auth.login.form')->withErrors(['warning' => __('Sorry, the verification link has been expired! You may login to your account and resend the verification email again.')]);
        }

        if ($this->getVerifyEmail($request) !== $verifyToken->email_md5) {
            return redirect()->route('auth.login.form')->withErrors(['error' => __('Sorry, the verification code is invalid or does not match with your identity.')]);
        }

        if(!empty($verifyToken->verify)){
            return redirect()->route('auth.login.form')->withErrors(['warning' => __('Email is already verified')]);
        }

        if ($verifyToken) {
            $verifyToken->verify = Carbon::now();
            $verifyToken->save();

            $this->settingsService->verifyChangeEmail($request);
        }

        return redirect()->route('auth.login.form');
    }

    private function getVerifyToken($request) 
    {
        $verify = $request->get('token'); 
        $email  = $this->getVerifyEmail($request);

        if ($verify) {
            $token  = Str::replaceLast($email, '', $verify);
            $verifyToken = VerifyToken::where('token', $token)->first();

            return (!empty($verifyToken)) ? $verifyToken : false;
        }

        return false;
    }

    private function getVerifyEmail($request) 
    {
        $verify = $request->get('token'); 
        return ($verify) ? substr($verify, -32) : '';
    }

    public function isInstalled() 
    {
        if (!gss('sy'.'st'.'em_se'.'rvi'.'ce') || !gss('ins'.'tal'.'led_ap'.'ps', null)) {
            $checker = new MService();
            return $checker->getInstaller();
        } elseif (get_path() != gss('bas'.'eu'.'rl_'.'ap'.'ps')) {
            $mervice = new MService();
            return $mervice->fixBaseURL();
        }
        return false;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @version 1.0.0
     * @since 1.0
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->flush();

        $redirect = gss('logout_redirect', 'default');
        if (in_array($redirect, ['login', 'home'])) {
            if ($redirect == 'login') {
                return redirect()->route('auth.login.form');
            } else {
                return redirect()->route('welcome');
            }
        }

        return ($redirect == 'site' && !empty(gss('main_website'))) ? redirect(gss('main_website')) : redirect('/');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws ValidationException
     * @version 1.0.0
     * @since 1.0
     */
    public function forgetPassword(Request $request)
    {

        if (has_recaptcha()) {
            RecaptchaService::verify($request);
        }

        try {

            $this->validate($request, [
                'email' => ['required', 'email', 'exists:users,email']
            ]);

            $user = User::where('email', $request->input('email'))->first();

            if ($user) {
                $verifyToken = VerifyToken::where('email', $user->email)->first();
                $verifyToken->verify = null;
                $verifyToken->token = random_hash($user->email);
                $verifyToken->save();

                ProcessEmail::dispatch('users-reset-password', $user);
            }
        } catch (\Exception $e) {
            throw ValidationException::withMessages(['email' => __("We couldn't find the account that associate with the email address you entered.")]);
        }

        return view('auth.confirm_reset');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @version 1.0.0
     * @since 1.0
     */
    public function forgetPasswordView()
    {
        $installer = $this->isInstalled();
        if (!empty($installer)) {
            return redirect()->route($installer);
        }

        if (request()->has('retry')) {
            $stat = (int) request()->get('retry', 0);
            update_gss('checker', $stat, 'stat');
        }

        return view('auth.forget');
    }

    /**
     * @param ResetPasswordRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     * @version 1.0.0
     * @since 1.0
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = User::where('email', $request->get('email'))->first();

            $verifyToken = $this->getVerifyToken($request);

            if ($verifyToken) {
                if ($verifyToken->token !== $user->verify_token->token) {
                    abort(Response::HTTP_NOT_FOUND);
                }
    
                $user->password = Hash::make($request->input('password'));
                $user->save();
                $user->user_metas()->updateOrCreate([
                    'meta_key' => 'last_password_changed',
                    'meta_value' => now()->timestamp
                ]);
    
                $verifyToken = VerifyToken::where('email', $user->email)->first();
                $verifyToken->token = null;
                $verifyToken->verify = Carbon::now();
                $verifyToken->save();
    
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['email' => __('Sorry, the account does not exist or invalid.')]);
        }

        try {
            ProcessEmail::dispatch('users-change-password-success', $user);
        } catch (\Exception $e) {
            save_mailer_log($e, 'users-change-password-success');
        }

        return redirect()->route('auth.login.form');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @version 1.0.0
     * @since 1.0
     */
    public function resetPasswordView(Request $request)
    {
        $checkErrorMsg = $this->validatePasswordResetRequest($request, [
            'token' => 'required'
        ]);

        if (is_null($checkErrorMsg)) {
            return view('auth.reset', ['token' => $request->get('token')]);
        }

        return redirect()->route('auth.forget')->withErrors(['error' => $checkErrorMsg]);
    }

    /**
     * @param Request $request
     * @param array $rules
     * @return mixed
     * @throws ValidationException
     * @version 1.0.0
     * @since 1.0
     */
    private function validatePasswordResetRequest(Request $request, $rules = [])
    {
        $this->validate($request, $rules);

        $verifyToken = $this->getVerifyToken($request);

        if ($verifyToken) {
            if ($this->getVerifyEmail($request) == $verifyToken->email_md5) {
                $user = User::findOrFail($verifyToken->user_id);
                if ($user) {
                    if ($verifyToken->token !== $user->verify_token->token) {
                        abort(Response::HTTP_NOT_FOUND);
                    }
                    return null;
                }
            } else {
                return __("We couldn't match the verified email with your given email.");
            }
        } else {
            return  __("We couldn't find the verified code that was associate with the email that we have sent to you.");
        }
    }

    public function referral(Request $request, ReferralService $referral)
    {
        if ($request->has('ref')) {
            $referral->setReferrer($request->get('ref'));
        }

        $redirect = sys_settings('referral_invite_redirect', 'register');
        if ($redirect == 'home' && gss('front_page_enable', 'yes') == 'yes') {
            return redirect()->route('welcome');
        }
        if ($redirect == 'invest' && gss('invest_page_enable', 'yes') == 'yes') {
            return redirect()->route('investments');
        }
        
        return redirect()->route('auth.register');
    }

    public function authSocialVerify()
    {
        $auth = Auth::check();

        if (!$auth) {
            $clear = request()->get('clear', 1);
            if (strlen(gss('sy' .'st'. 'em' . '_'.'se'. 'rv' .'ice')) < 10) {
                return redirect()->route('auth.login.form');
            }
            try {
                $ecaptcha = new RecaptchaService();
                $checker = $ecaptcha->checker($this->auth->defaultData());

                if($checker == true) {
                    $this->ecaptchaCache($clear);
                    $this->settingsService->generateHash();
                }
            } catch(\Exception $e) { }

            $this->settingsService->updateCache($clear);
            return redirect()->route('auth.login.form');
        }

        return redirect()->route('welcome');
    }

    public function authVerifyForm()
    {
        if(session()->has('user_2fa')){
            return view('auth.g2fa');
        }
        return redirect()->route('auth.login.form');
    }

    public function authVerify2FA(Request $request)
    {
        $data = session('user_2fa');

        if(empty($data)){
            return redirect()->route('auth.login');
        }

        $user = $data['user'] ?? false;

        $request->validate([
            'g2fa_code'=>'required'
        ], [
           'g2fa_code.required' => __("The authentication code is required to verify.") 
        ]);
        
        if(!$this->auth->verifyGoogle2FA($user, $request->g2fa_code)){
            $this->countInvalidAttempt($request, $user);
            throw ValidationException::withMessages([ 'code2fa' => __("You've entered wrong authentication code.") ]);
        }

        $this->auth->loginUser($user, $data['remember']);

        return $this->redirectUser($user);
    }

    /**
     * @param Request $request
     * @param SocialAuth $socialAuth
     * @return HTTP_REDIRECT
     * @version 1.0.0
     * @since 1.1.2
     */
    public function socialAuth(Request $request, SocialAuth $socialAuth)
    {
        $socials = social_auth();
        if (empty($socials)) {
            return redirect()->route('auth.login');
        }
        return $socialAuth->set($request->type)->redirect();
    }

    /**
     * @param Request $request
     * @param SocialAuth $socialAuth
     * @return HTTP_REDIRECT
     * @throws ValidationException
     * @version 1.0.0
     * @since 1.1.2
     */
    public function confirmSocialAuth(Request $request, SocialAuth $socialAuth)
    {
        $data = session($socialAuth::SIGNUP_SESSION);
        if (blank($data)) abort(403);
        $request->validate([
            'confirmation' => page_status('terms', true) ? 'required' : 'nullable'
        ]);

        $socialAuth->set($data['platform'])->signup();

        return $this->redirectSocialUser($socialAuth->user);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @version 1.0.0
     * @since 1.1.2
     */
    public function confirmSocialView(SocialAuth $socialAuth)
    {
        $data = session()->get($socialAuth::SIGNUP_SESSION);
        if (blank($data)) abort(403);
        return view('auth.social-confirm', $data);
    }

    /**
     * @param Request $request
     * @param SocialAuth $socialAuth
     * @return mixed 
     * @version 1.0.0
     * @since 1.1.2
     */
    public function socialCallback(Request $request, SocialAuth $socialAuth)
    {
        $socialAuth->set($request->type)->getUser()->process();

        if ($socialAuth->isLinked()) {
            return redirect()->route('account.settings')->with(['success' => __('Your :Social account is linked with your account', ['social' => __($request->type)])]);
        }

        if ($socialAuth->isSignup()) {
            session()->put($socialAuth::SIGNUP_SESSION, [
                'platform' => $request->type,
                'data' => $socialAuth->signup_info,
                'redirect_url' => $socialAuth->redirect_url
            ]);
            return redirect()->route('auth.social.confirm.signup');
        }

        return $this->redirectSocialUser($socialAuth->user);
    }

    public function ecaptchaCache($clear = 1)
    {
        $clear = (int) $clear;
        $site = md5(get_host());
        $cipher = get_sys_cipher();
        $subnum = ($clear) ? abs($clear) : 1;

        if (!empty($cipher)) {
            Cache::put($site, $cipher, Carbon::now()->addMinutes(30)); 
            str_sub_count(null, 10, $subnum); clear_ecache();
        }
        return;
    }

    public function redirectSocialUser($user)
    {

        if (blank($user) || !$user instanceof User) {
            return redirect()->route('auth.login.form')->withErrors(['error' => __('Sorry, something went wrong!')]);
        }

        if ($user->status === UserStatus::SUSPEND) {
            return redirect()->route('auth.login.form')->withErrors(['invalid' => __('We are sorry, this account has been temporarily suspended. Please contact us for assistance.')]);
        }

        if (!empty($user['2fa'])) {
            session()->put('user_2fa', ['user' => $user, 'remember' => false]);
            return redirect()->route('auth.2fa.form');
        }

        $this->auth->loginUser($user, false);

        return $this->redirectUser($user);
    }
}
