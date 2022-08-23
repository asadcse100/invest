<?php

namespace App\Services;

use App\Jobs\ProcessEmail;
use App\Models\User;
use App\Models\UserMeta;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\FacebookProvider;
use Laravel\Socialite\Two\GoogleProvider;

class SocialAuth extends Service
{

    const LINK_SESSION = 'social_link';

    const REVOKE_SESSION = 'social_revoke';

    const SIGNUP_SESSION = 'social_signup';

    private $availablePlatforms = [
        'facebook' => FacebookProvider::class,
        'google' => GoogleProvider::class
    ];

    private $platform;

    private $userInfo;

    private $config = [];

    private $authService;

    private $social_meta = 'social_account_';

    private $is_signup = false;

    public $user = null;

    private $linked = false;

    private $revoked = false;

    public $signup_info = null;

    public $redirect_url = null;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    private function setUser(User $user)
    {
        $this->user = $user;
    }

    private function setLinked(bool $value)
    {
        $this->linked = $value;
    }

    public function setRevoked(bool $value)
    {
        $this->revoked = $value;
    }

    public function isLinked()
    {
        return $this->linked;
    }

    public function isRevoked()
    {
        return $this->revoked;
    }

    public function isSignup()
    {
        return $this->is_signup;
    }

    /**
     * @param string $platform
     * @return this
     * @version 1.0.0
     * @since 1.1.2
     */
    public function set(string $platform)
    {
        if (!Arr::has($this->filterPlatforms(), $platform)) {
            abort(404);
        }
        $this->platform = $platform;
        $this->social_meta = $this->social_meta . $this->platform;
        $this->setConfig();
        $this->redirect_url = session()->pull('redirect_url');
        if (blank($this->redirect_url)) {
            $this->redirect_url = url()->previous();
            session()->put('redirect_url', $this->redirect_url);
        }
        return $this;
    }

    /**
     * @return array
     * @version 1.0.0
     * @since 1.1.2
     */
    public function filterPlatforms()
    {
        return array_filter($this->availablePlatforms, function ($platform) {
            return social_auth($platform);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @return array
     * @version 1.0.0
     * @since 1.1.2
     */
    public function getUser()
    {
        try {
            $this->userInfo = Socialite::buildProvider($this->availablePlatforms[$this->platform], $this->config)->stateless()->user();
        } catch (\Exception $ex) {
            $this->error([
                'error' => __('Unable to connect with :Platform. Please try again later.', ['platform' => __($this->platform)])
            ]);
        }

        $validator = Validator::make(data_get($this->userInfo, 'user', []), [
            'name' => 'required',
            'id' => 'required',
            'email' => 'required'
        ]);

        if ($validator->fails()) {
            $this->error([
                'error' => __('Unable to get proper information from :Platform. Please check your permissions and try again.', ['platform' => __($this->platform)])
            ]);
        }

        return $this;
    }

    /**
     * @return HTTP_REDIRECT
     * @version 1.0.0
     * @since 1.1.2
     */
    public function redirect()
    {
        try {
            return Socialite::buildProvider($this->availablePlatforms[$this->platform], $this->config)->redirect();
        } catch (\Exception $ex) {
            $this->error([
                'error' => __('Unable to connect with :Platform. Please try again later.', ['platform' => __($this->platform)])
            ]);
        }
    }

    /**
     * @return void
     * @version 1.0.0
     * @since 1.1.2
     */
    private function setConfig()
    {
        $this->config = array_merge(
            $this->config,
            [
                'client_id' => gss('social_' . $this->platform . '_id'),
                'client_secret' => gss('social_' . $this->platform . '_secret'),
                'redirect' => route('auth.social.callback', $this->platform)
            ]
        );
    }

    /**
     * @return array
     * @version 1.0.0
     * @since 1.1.2
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @throws HttpResponseException
     * @return void
     * @since 1.1.2
     * @version 1.0.0
     */
    public function process()
    {
        if (session()->has(self::LINK_SESSION) && Auth::check()) {
            $this->verifyMeta();
            $this->addMeta(Auth::user());
            session()->forget(self::LINK_SESSION);
            $this->setLinked(true);
            $this->setUser(Auth::user());
            return;
        }

        $userMeta = $this->findSocialMeta();

        if (!blank($userMeta) && !blank($userMeta->user)) {
            $this->setUser($userMeta->user);
            return;
        }

        $user = User::where('email', $this->userInfo['email'])->first();

        if (!blank($user)) {
            $this->error([ 
                'error' => __('The :email cannot be registered.', ['email' => hide_email($user->email)]) 
            ]);
        }

        if (allowed_signup()) {
            $this->is_signup = true;
            $this->signup_info = $this->userInfo;
            return;
        }

        $this->error([
            'notice' => __('New registration is not allowed. Please feel free to contact us for more information.')
        ]);
    }

    /**
     * @param User $user
     * @return void
     * @version 1.0.0
     * @since 1.1.2
     */
    private function addMeta(User $user)
    {
        $user->user_metas()->updateOrCreate(
            ['meta_key' => $this->social_meta, 'user_id' => $user->id],
            ['meta_value' => $this->userInfo['id']]
        );
    }

    /**
     * @param User $user
     * @return void
     */
    private function resetMeta(User $user)
    {
        $user->user_metas()->updateOrCreate(
            ['meta_key' => $this->social_meta, 'user_id' => $user->id],
            ['meta_value' => null]
        );
    }

    /**
     * @return HTTP_REDIRECT
     * @version 1.0.0
     * @since 1.1.2
     */
    public function revoke()
    {
        $this->resetMeta(Auth::user());
        $this->setRevoked(true);
        $this->setUser(Auth::user());
        return;
    }

    /**
     * @return HTTP_REDIRECT
     * @version 1.0.0
     * @since 1.1.2
     */
    public function link()
    {
        session()->put(self::LINK_SESSION, $this->social_meta);
        return $this->redirect();
    }

    /**
     * @return HTTP_REDIRECT
     * @version 1.0.0
     * @since 1.1.2
     */
    public function signup()
    {
        if (session()->has(self::SIGNUP_SESSION)) {
            $this->userInfo = session()->get(self::SIGNUP_SESSION)['data'];
            $this->registerUser();
            session()->forget(self::SIGNUP_SESSION);
            return;
        }
    }

    /**
     * @throws HttpResponseException
     * @return void
     * @version 1.0.0
     * @since 1.1.2
     */
    private function verifyMeta()
    {
        $meta = $this->findSocialMeta();

        if (session(self::REVOKE_SESSION) && blank($meta)) {
            $this->error([
                'errors' => __('We are unable to revoke your social access due to authentication.')
            ]);
        }

        if (!blank($meta) && $meta->user_id !== Auth::id()) {
            $this->error(['errors' => __('We are unable to :action your social access due to authentication.', [
                    'action' => session(self::LINK_SESSION) ? __('link') : __('revoke')
                ])
            ]);
        }

        if (session(self::LINK_SESSION) && !blank($meta)) {
            $this->error([
                'errors' => __('You already have a :Platform account linked with your account.', ['platform' => __($this->platform)])
            ]);
        }

        if (session(self::LINK_SESSION) && $this->authExists(Auth::user())) {
            $this->error([
                'errors' => __('You already have another social account linked.')
            ]);
        }
    }

    /**
     * @return UserMeta
     * @version 1.0.0
     * @since 1.1.2
     */
    private function findSocialMeta()
    {
        return UserMeta::where([
            'meta_key' => $this->social_meta,
            'meta_value' => $this->userInfo['id']
        ])->first();
    }

    /**
     * @return void
     * @version 1.0.0
     * @since 1.1.2
     */
    private function registerUser()
    {
        $data = array_merge($this->userInfo->user, [
            'registration_method' => 'social',
            'password' => rand(100000, 1000000)
        ]);

        $user = $this->authService->createUser($data, true);
        $this->addMeta($user);
        try {
            ProcessEmail::dispatch('users-welcome-email', $user);
        } catch (\Exception $ex) {
            save_mailer_log($ex);
        }
        $this->setUser($user);
        return;
    }

    private function error(array $msg)
    {
        session()->forget([self::LINK_SESSION, self::REVOKE_SESSION, self::SIGNUP_SESSION, 'redirect_url']);
        throw new HttpResponseException(redirect($this->redirect_url)->withErrors($msg));
    }

    /**
     * @param User $user
     * @return bool
     * @since 1.1.2
     * @version 1.0.0
     */
    public function authExists(User $user, $return = null)
    {
        $platforms = array_keys($this->availablePlatforms);
        $flag = false;
        foreach ($platforms as $platform) {
            $meta = 'social_account_' . $platform;
            if ($user->meta($meta)) {
                $flag = $return ? $platform : true;
                break;
            }
        }
        return $flag;
    }
}
