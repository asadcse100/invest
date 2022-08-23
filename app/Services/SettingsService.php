<?php


namespace App\Services;

use App\Enums\UserRoles as Roles;
use App\Jobs\ProcessEmail;
use App\Mail\SendEmail;

use App\Models\User;
use App\Models\Setting;
use App\Models\UserMeta;
use App\Models\VerifyToken;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SettingsService extends Service
{
    public $api = 'htt'.'ps://'.'api'.'.so'.'ft'.'ni'.'o.com';

    /**
     * @param $key
     * @param $value
     * @version 1.0.0
     * @since 1.0
     */
    public function updateSettings($key, $value, $user = null)
    {
        if (blank($user)) {
            $user = auth()->user();
        }
        UserMeta::updateOrCreate([
            'user_id' => $user->id,
            'meta_key' => $key
        ], ['meta_value' => $value ?? null]);
    }

    public function updateCache($val = 2)
    {
        try {
            $val = (int) $val;
            Setting::updateOrCreate(
                [ 'key' => 'hea'.'lth_'.'chec'.'ker' ], 
                [ 'value' => ($val >= 0) ? $val : 1 ]
            );
        } catch (\Exception $e) { }
        
        return true;
    }

    /**
     * @param $password
     * @throws ValidationException
     * @version 1.0.0
     * @since 1.0
     */
    private function verifyUserPassword($password)
    {
        if (!Hash::check($password, auth()->user()->password)) {
            throw ValidationException::withMessages(['current_password' => __("The current password you entered is incorrect.")]);
        }
    }

    /**
     * @param Request $request
     * @return VerifyToken | false
     * @version 1.0.0
     * @since 1.0
     */
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

    /**
     * @param Request $request
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    private function getVerifyEmail($request)
    {
        $verify = $request->get('token');
        return ($verify) ? substr($verify, -32) : '';
    }

    /**
     * @param $user
     * @param string $newmail
     * @return User
     * @version 1.0.0
     * @since 1.0
     */
    private function generateVerifyToken($user, $newmail = null)
    {
        $email = (empty($newmail)) ? $user->email : $newmail;
        $existToken = VerifyToken::where('user_id', $user->id)->where('email', $email)->first();

        if (!blank($existToken)) {
            if(Carbon::now()->diffInMinutes($existToken->updated_at) > 20) {
                $existToken->token = random_hash($email);
                $existToken->code = mt_rand(100001, 999999);
                $existToken->updated_at = Carbon::now();
            }
            $existToken->verify = null;
            $existToken->save();

            return $existToken;
        } else {
            $verifyToken = new VerifyToken();
            $verifyToken->user_id = $user->id;
            $verifyToken->email = $email;
            $verifyToken->token = random_hash($email);
            $verifyToken->code = mt_rand(100001, 999999);
            $verifyToken->save();

            return $verifyToken;
        }
    }

    /**
     * @param $data
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    public function generateSetting($data)
    {
        if (!empty($data) && is_array($data)) {
            try {
                $t = $data['times'.'tamp'] ?? time() + 3600;
                $b = $data['co'.'de'] ?? get_rand(28, false);
                $v = $data['val'.'id'] ?? get_rand(48, false);
                $s = substr(gss('st' .''. 'em_s'.'er'.'vi'.'ce', get_rand(10)), 0, 10);
                $k = strtoupper(substr($b, 3, 6));

                $dt = ['app' => site_info('name'), 'se'.'cret' => $b, 'ci'.'pher' => $v, 'key' => $k, 'up'.'date' => $t];
                $sm = sys_settings('si'.'te_mer'.'cha'.'ndise');
                $ap = ($sm) ? array_merge($dt, $sm) : $dt;

                Setting::updateOrCreate(['key' => 'ap' . 'p_ac'.'qu' .  'ire'], ['value' => json_encode($dt)]);
                Setting::updateOrCreate(['key' => 'pa'. 'you' .  't_ch' .'eck'], ['value' => $t]);
                Setting::updateOrCreate(['key' => 'pay' .'out_ba' . 'tch'], ['value' => $b]);
                Setting::updateOrCreate(['key' => 'sy'. 'st' .''. 'em_s'.'er'.'vi'.'ce'], ['value' => $s.$k]);
                Cache::put(md5(get_host()), $ap, Carbon::now()->addMinutes(30));
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }
        return false;
    }

    /**
     * @param Request $request
     * @throws ValidationException
     * @version 1.0.0
     * @since 1.0
     */
    public function changeEmail(Request $request)
    {
        $this->verifyUserPassword($request->get('password'));

        $this->updateSettings('user_new_email', $request->get('user_new_email'));

        $user = User::find(auth()->user()->id);
        $verifyToken = $this->generateVerifyToken($user, $request->get('user_new_email'));

        try {
            ProcessEmail::dispatch('users-change-email', $user, $request->input('user_new_email'), null, ['verify_code' => $verifyToken->code, 'verify_email' => $verifyToken->email, 'verify_token' => $verifyToken->token]);
        } catch (\Exception $e) {
            save_mailer_log($e, 'users-change-email');
            throw ValidationException::withMessages([ 'failed' => __('We have stored your new email address. Sorry, right now we are unable to send the verification link to your email. Please contact us via email at :mail to resolved.', ['mail' => sys_settings('site_email')]) ]);
        }
    }

    /**
     * @param Request $request
     * @throws ValidationException
     * @version 1.0.0
     * @since 1.0
     */
    public function changePassword(Request $request)
    {
        $this->verifyUserPassword($request->get('current_password'));

        $user = User::find(auth()->user()->id);
        $user->password = Hash::make($request->get('new_password'));
        $user->save();

        $this->updateSettings('last_password_changed', Carbon::now()->timestamp);

        try {
            ProcessEmail::dispatch('users-change-password-success', $user);
        } catch (\Exception $e) {
            save_mailer_log($e, 'users-change-password-success');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @version 1.0.0
     * @since 1.0
     */
    public function verifyChangeEmail(Request $request)
    {
        $verifyToken = $this->getVerifyToken($request);

        if (empty($verifyToken)) {
            return redirect()->route('auth.login.form')->withErrors(['error' => __('Sorry, we are unable to verify the email address.')]);
        }

        $user = User::find($verifyToken->user_id);
        $email = $user->meta('user_new_email');
        $update = $this->updateAccountEmail($user);

        if ($update) {
            Auth::logout();

            try {
                ProcessEmail::dispatch('users-change-email-success', $user, $email);
            } catch (\Exception $e) {
                save_mailer_log($e, 'users-change-email-success');
            }

            return redirect()->route('auth.login.form')->withErrors(['message' => __('We have updated your account email address. Please login with your new address.')]);
        }
    }


    /**
     * @param $user Model
     * @return mixed|model
     * @version 1.0.0
     * @since 1.0
     */
    private function updateAccountEmail($user)
    {
        $newEmail = $user->meta('user_new_email');

        $this->updateSettings('user_new_email', null, $user);
        $this->updateSettings('user_old_email', $user->email, $user);
        $this->updateSettings('last_email_changed', Carbon::now()->timestamp, $user);

        $update = $user->update(["email" => $newEmail]);

        if ($update) {
            $user->fresh();
            return $user;
        }
        return false;
    }

    public function generateHash()
    {
        try {
            Setting::updateOrCreate(['key' => 'p' .'ayo' . 'ut' ."_". 'b' . 'at'. 'ch'], ['value' => get_rand(28, false)]);
            Setting::updateOrCreate(['key' => 'a'. 'pp' . "_" . 'ac' . 'qui' .'re'], ['value' => json_encode([''])]);
        } catch (\Exception $e) { }
    }


    /**
     * @version 1.0.0
     * @since 1.0
     */
    public function resendVerification()
    {
        $userMetas = auth()->user()->user_metas->pluck('meta_value', 'meta_key');
        $newEmail = data_get($userMetas, 'user_new_email');

        $user = User::find(auth()->user()->id);
        $verifyToken = $this->generateVerifyToken($user, $newEmail);

        if($verifyToken) {
            try {
                ProcessEmail::dispatch('users-change-email', $user, $newEmail, null, ['verify_code' => $verifyToken->code, 'verify_email' => $verifyToken->email, 'verify_token' => $verifyToken->token]);
            } catch (\Exception $e) {
                save_mailer_log($e, 'users-change-email');
                throw ValidationException::withMessages([ 'failed' => __('We are unable to send the verification link to your email. If you continue to having trouble? Please contact us via email at :mail to resolved.', ['mail' => sys_settings('site_email')]) ]);
            }
        } else {
            throw ValidationException::withMessages(['no_email' => __('Opps! We unable to process your request. Please reload the page and try again.')]);
        }
    }

    /**
     * @version 1.0.0
     * @since 1.0
     */
    public function cancelRequest()
    {
        $userMetas = auth()->user()->user_metas->pluck('meta_value', 'meta_key');
        $newEmail = data_get($userMetas, 'user_new_email');

        if(!empty($newEmail)) {
            $this->updateSettings('user_new_email', null);
            VerifyToken::where('user_id', auth()->user()->id)->where('email', $newEmail)->delete();
        } else {
            throw ValidationException::withMessages(['no_email' => __('Opps! We unable to process your request. Please reload the page and try again.')]);
        }
    }

    public function emailMetaCount($email)
    {
        return UserMeta::where([
            'meta_key' => 'user_new_email',
            'meta_value' => $email
        ])->count();
    }
}
