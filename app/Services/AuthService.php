<?php


namespace App\Services;


use App\Enums\UserRoles as Roles;
use App\Enums\UserStatus;
use App\Models\Referral;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\VerifyToken;
use App\Services\Transaction\TransactionService;
use App\Traits\WrapInTransaction;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use PragmaRX\Google2FA\Google2FA;

class AuthService extends Service
{
    use WrapInTransaction;

    /**
     * @var ReferralService
     */
    private $referralService;
    private $google2fa;

    public function __construct(ReferralService $referralService, Google2FA $twoFactor)
    {
        $this->referralService = $referralService;
        $this->google2fa = $twoFactor;
    }

    /**
     * @param User $user
     * @param bool $remember
     * @return bool
     * @version 1.0.0
     * @since 1.0
     */
    public function loginUser(User $user, $remember = false)
    {
        Auth::login($user, $remember);
        $user = auth()->user();
        $user->last_login = Carbon::now();
        $user->save();

        activity_log("User Logged in");

        return true;
    }

    public function checkPassword(User $user, $password)
    {
        return Hash::check($password, $user->password);
    }

    public function verifyGoogle2FA(User $user, $code)
    {
        try {
            $valid = $this->google2fa->verifyKey($user['2fa'], $code);
        } catch (\Exception $e) {
            $valid = false;
        }

        return $valid;
    }

    public function defaultData()
    {
        $cd = 'code';
        $data = [
            "dom" . "ain" => get_path(),
            "pur". "chase". "_".$cd => sys_info('pcode'),
            "acti" ."vation" ."_".$cd => sys_info('secret'),
            "app" . $cd => substr(sys_info('service'), 10),
        ];

        return $data;
    }

    /**
     * @param $user, $meta_data
     * @version 1.0.1
     * @since 1.0
     */
    private function saveDefaultUserMeta($user, $reg = null, $meta_data = null)
    {
        $regmethod = ($reg) ? $reg : 'email';

        $data = [
            [
                'user_id' => $user->id,
                'meta_key' => 'profile_display_name',
                'meta_value' => last_word($user->name),
            ],
            [
                'user_id' => $user->id,
                'meta_key' => 'profile_avatar_bg',
                'meta_value' => random_color(),
            ],
            [
                'user_id' => $user->id,
                'meta_key' => 'profile_display_full_name',
                'meta_value' => 'on',
            ],
            [
                'user_id' => $user->id,
                'meta_key' => 'setting_activity_log',
                'meta_value' => 'on',
            ],
            [
                'user_id' => $user->id,
                'meta_key' => 'setting_unusual_activity',
                'meta_value' => 'on',
            ],
            [
                'user_id' => $user->id,
                'meta_key' => 'registration_method',
                'meta_value' => $regmethod,
            ]
        ];

        if (!empty($meta_data) && is_array($meta_data)) {
            foreach ($meta_data as $meta_key => $meta_value) {
                if ($meta_value != null && ($meta_key == 'profile_phone' || $meta_key == 'profile_dob' || $meta_key == 'profile_country')) {
                    $extra = [
                        'user_id' => $user->id,
                        'meta_key' => $meta_key,
                        'meta_value' => $meta_value,
                    ];
                    array_push($data, $extra);
                }
            }   
        }

        foreach ($data as $metaItem) {
            $userMeta = new UserMeta($metaItem);
            $userMeta->fill($metaItem);
            $userMeta->save();
        }
    }

    /**
     * @param $firstWord
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    private function generateUniqueUsername($firstWord)
    {

        $username = strtolower($firstWord).mt_rand(101, 999);

        $user = User::where('username', $username)->first();
        if (blank($user)) {
            return $username;
        } else {
            $this->generateUniqueUsername($firstWord);
        }
    }

    /**
     * @param $data
     * @param bool $autoVerified
     * @return User
     * @version 1.0.1
     * @since 1.0
     */
    public function createUser($data, $autoVerified = false)
    {
        $firstUser = blank(User::first());
        $suprAdmin = (isset($data['setup']) && $data['setup']=='admin') ? true : false;

        $data['username'] = $this->generateUniqueUsername(first_word($data['name']));
        $data['status'] = ($firstUser || $autoVerified) ? UserStatus::ACTIVE : UserStatus::INACTIVE;
        $data['password'] = Hash::make($data['password']);
        $data['role'] = (isset($data['role'])) ? $data['role'] : Roles::USER;

        if ($data['role'] == Roles::USER && $this->referralService->getReferMeta()) {
            $data['refer'] = json_encode($this->referralService->getReferMeta());
        }

        if ($firstUser) {
            $data['role'] = ($suprAdmin) ? Roles::SUPER_ADMIN : Roles::ADMIN;
            $data['username'] = ($suprAdmin) ? 'superadmin' : 'admin';
            $data['registration_method'] = 'system';
        }

        $user = new User();
        $user->fill($data);
        $user->save();
        $this->saveDefaultUserMeta($user, $data['registration_method'] ?? null, $data);

        $verifyToken = $this->createVerifyToken($user);
        if ($firstUser || $autoVerified) {
            $verifyToken->verify = Carbon::now();
            $verifyToken->save();

            if (referral_system() && !empty($user->refer)) {
                $this->referralService->createReferral($user);
            }

            $this->saveEmailVerificationMeta($user);
        }

        if ($firstUser && $suprAdmin) {
            if (session()->get('default_setup') == 'finish') {
                upss('system_super_admin', $user->id);
                session()->forget('default_setup');
            }
        }

        return $user;
    }

    /**
     * @param $data
     * @param bool $autoVerified
     * @return User
     * @version 1.0.0
     * @since 1.0
     */
    private function createVerifyToken($user)
    {
        $verifyToken = new VerifyToken();

        $verifyToken->user_id = $user->id;
        $verifyToken->email = $user->email;
        $verifyToken->token = random_hash($user->email);
        $verifyToken->code = mt_rand(100001, 999999);
        $verifyToken->save();

        return $verifyToken;
    }

    /**
     * @param $data
     * @param bool $autoVerified
     * @return User
     * @version 1.0.0
     * @since 1.0
     */
    public function generateNewToken($user, $force=false)
    {
        $token = VerifyToken::where('user_id', $user->id)->where('email', $user->email)->first();

        if (!blank($token)) {
            if($force==true || Carbon::now()->diffInMinutes($token->updated_at) > 20) {
                $token->token = random_hash($user->email);
                $token->code = mt_rand(100001, 999999);
                $token->updated_at = Carbon::now();
            }
            $token->verify = null;
            $token->save();
        } else {
            $this->createVerifyToken($user);
        }
    }

    /**
     * @param $user
     * @version 1.0.0
     * @since 1.0
     */
    public function saveEmailVerificationMeta($user)
    {
        $mailVerified = UserMeta::where('user_id', $user->id)
            ->where('meta_key', 'email_verified')
            ->get();

        if (blank($mailVerified)) {
            $this->wrapInTransaction(function ($user) {
                $mailVerified = new UserMeta();
                $mailVerified->user_id = $user->id;
                $mailVerified->meta_key = 'email_verified';
                $mailVerified->meta_value = Carbon::now();
                $mailVerified->save();

                if (signup_bonus() || referral_system()) {
                    $tnxService = new TransactionService();

                    if (signup_bonus()) {
                        $tnxService->addSignupBonus($user);
                    }

                    if (referral_system()) {
                        $tnxService->addReferralCommission($user);
                    } 
                }
            }, $user);
        }

        $lastEmailVerified = UserMeta::where('user_id', $user->id)
            ->where('meta_key', 'email_verified_last')
            ->first();

        if (!blank($lastEmailVerified)) {
            $lastEmailVerified->meta_value = Carbon::now();
            $lastEmailVerified->save();
        } else {
            $lastEmailVerified = new UserMeta();
            $lastEmailVerified->user_id = $user->id;
            $lastEmailVerified->meta_key = 'email_verified_last';
            $lastEmailVerified->meta_value = Carbon::now();
            $lastEmailVerified->save();
        }
    }
}
