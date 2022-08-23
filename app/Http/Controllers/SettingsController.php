<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessEmail;
use App\Models\Setting;
use App\Models\UserMeta;
use App\Services\SettingsService;
use App\Services\SocialAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use PragmaRX\Google2FA\Google2FA;

class SettingsController extends Controller
{
    /**
     * @var SettingsService
     */
    private $settingsService;

    public function __construct(SettingsService $settingsService, Google2FA $google2fa)
    {
        $this->settingsService = $settingsService;
        $this->google2fa = $google2fa;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @version 1.0.0
     * @since 1.0
     */
    public function view()
    {
        $metas = UserMeta::where('user_id', auth()->user()->id)->pluck('meta_value', 'meta_key');
        $google2fa = $this->google2fa;
        $secret2fa = $google2fa->generateSecretKey();
        $qrcode2fa = $google2fa->getQRCodeUrl(
            site_info('name'),
            auth()->user()->email,
            $secret2fa
        );

        return view('user.account.settings', compact('metas', 'qrcode2fa', 'secret2fa'));
    }

    /**
     * @param Request $request
     * @version 1.0.0
     * @since 1.0
     */
    public function saveSettings(Request $request)
    {
        $updated = false;
        $validFields = ["profile_settings"];
        $input = $request->only($validFields);

        if (!empty($input)) {
            $updated = true;
            foreach ($input as $setting) {
                $key = $setting['option'] ?? '';
                $value = $setting['value'] ?? '';
                if ($this->isValidOption($key)) {
                    $this->settingsService->updateSettings($key, $value);
                } else {
                    $updated =  false;
                }
            }
        }
        if ($updated) {
            return response()->json(['title' => __('Profile Updated'), 'msg' => __('Profile setting has been updated successfully.')]);
        }
        return response()->json(['type' => 'warning', 'title' => __('Update Failed'), 'msg' => __('Sorry, unable to update your profile setting.')]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @version 1.1.0
     */
    public function preference(Request $request)
    {
        if ($request->has('utheme') && !empty($request->get('utheme'))) {
            $key = 'profile_theme_mode';
            $value = ($request->get('utheme') == 'dark') ? 'dark' : 'light';
            $this->settingsService->updateSettings($key, $value);
            return true;
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
        $this->validate($request, [
            'user_new_email' => "required|email|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,9}$/ix|max:190|not_in:" . auth()->user()->email . "|unique:users,email," . auth()->user()->id,
        ], [
            'user_new_email.not_in' => __("The new email address cannot be the same as your current email address."),
            'user_new_email.unique' => __("The chosen email is already registered with us. Please use a different email address."),
            'user_new_email.regex' => __("Please enter a valid email address.")
        ]);

        $emailMetaCount = $this->settingsService->emailMetaCount($request->user_new_email);
        if ($emailMetaCount > 0) {
            throw ValidationException::withMessages(['user_new_email' => __("The chosen email is already registered with us. Please use a different email address.")]);
        }

        $this->settingsService->changeEmail($request);

        return response()->json(['msg' => __("Now we need to verify your new email address. We have sent an email to new email (:new_email) to verify your address. Please check your inbox (including spam folder) for the verification link.", ['new_email' => $request->input('user_new_email')])]);
    }

    /**
     * @param Request $request
     * @throws ValidationException
     * @version 1.0.0
     * @since 1.0
     */
    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'current_password' => "required|max:190|min:6|different:new_password",
            'new_password' => "required|confirmed|max:190|min:6",
        ], [
            'current_password.different' => __("The new password should be different than your current password."),
            'new_password.*' => __("The password should be minimum 6 character and match with your confirmed password."),
        ]);

        $this->settingsService->changePassword($request);
    }

    /**
     * @param Request $request
     * @throws ValidationException
     * @version 1.0.0
     * @since 1.0
     */
    public function addPassword(Request $request, SocialAuth $socialAuth)
    {
        $user = auth()->user();

        if ($user->meta('registration_method') !== 'social' && $user->meta('last_password_changed')) {
            throw ValidationException::withMessages(['error' => __('Invalid action taken')]);
        }

        $this->validate($request, [
            'new_password' => "required|confirmed|max:190|min:6",
        ], [
            'new_password.*' => __("The password should be minimum 6 character and match with your confirmed password."),
        ]);

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        $user->user_metas()->updateOrCreate([
            'meta_key' => 'last_password_changed'
        ], [
            'meta_value' => now()->timestamp
        ]);

        try {
            ProcessEmail::dispatch('users-change-password-success', $user);
        } catch (\Exception $e) {
            save_mailer_log($e, 'users-change-password-success');
        }

        $socialAuth->set($user->connected_with)->revoke();

        return response()->json([
            'msg' => __("Your new password is successfully added and revoked your :platform account.", ['platform' => $user->connected_with]),
            'reload' => true
        ]);
    }

    /**
     * @param Request $request
     * @throws ValidationException
     * @version 1.0.0
     * @since 1.0
     */
    public function google2fa(Request $request, $state)
    {
        if (!in_array($state, ['disable', 'enable'])) {
            throw ValidationException::withMessages(['invalid' => __('Sorry, we are unable to proceed your request.')]);
        }

        if ($state == 'disable') {
            $this->validate($request, [
                'google2fa_code' => 'required',
            ], [
                'google2fa_code.required' => __('The authentication code is required to disable.'),
            ]);
        } else {
            $this->validate($request, [
                'google2fa_code' => 'required',
                'google2fa_secret' => 'required'
            ], [
                'google2fa_code.required' => __('The authentication code is required to enable.'),
                'google2fa_secret.required' => __('The secret key is missing for authentication.'),
            ]);
        }

        $code   = $request->input('google2fa_code');
        $secret = ($state == 'enable') ? $request->input('google2fa_secret') : data_get(auth()->user(), '2fa', 0);

        try {
            $valid = $this->google2fa->verifyKey($secret, $code);
        } catch (\Exception $e) {
            $valid = false;
            throw ValidationException::withMessages(['invalid' => __('Sorry, unable to verify authentication code.')]);
        }

        if ($valid) {
            $update = ($state == 'disable') ? 0 : $secret;
            auth()->user()->update(['2fa' => $update]);

            $upmsg  = ($state == 'disable') ? __('2FA authentication successfully disabled.') : __('2FA authentication successfully enabled.');
            return response()->json(['msg' => $upmsg, 'reload' => 800]);
        } else {
            throw ValidationException::withMessages(['invalid' => __("You've entered wrong authentication code.")]);
        }
    }

    /**
     * @version 1.0.0
     * @since 1.0
     */
    public function resendVerification()
    {
        $this->settingsService->resendVerification();
        return response()->json(['msg' => __("We've sent a verification link to your new email.")]);
    }

    /**
     * @version 1.0.0
     * @since 1.0
     */
    public function cancelRequest()
    {
        $this->settingsService->cancelRequest();
        return response()->json(['msg' => __('Request for email change has been cancelled.')]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse 
     * @version 1.0.0
     * @since 1.1.2
     */
    public function social(Request $request, SocialAuth $social)
    {
        $request->validate([
            'password' => 'required'
        ]);

        if (!Hash::check($request->password, auth()->user()->password)) {
            throw ValidationException::withMessages(['confirm-password' => __("The password you entered is incorrect.")]);
        }

        if (!social_auth()) {
            throw ValidationException::withMessages(['error' => __("Sorry, but we're facing some technical issue.")]);
        }

        if (!Arr::has($social->filterPlatforms(), $request->platform)) {
            throw ValidationException::withMessages(['error' => __("Sorry, we are unable to proceed your request.")]);
        }

        if ($request->action === 'link') {
            return response()->json([
                'msg' => __('We are redirecting you to authenticate from :Social account.', ['social' => __($request->platform)]),
                'url' => $social->set($request->platform)->link()->getTargetUrl()
            ]);
        }

        if ($request->action === 'revoke') {
            $social->set($request->platform)->revoke();
            return response()->json([
                'msg' => __('Successfully revoked your social account.', ['social' => __($request->platform)]),
                'reload' => true
            ]);
        }

        throw ValidationException::withMessages(['error' => __('Unable to process your request.')]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @version 1.0.0
     * @since 1.0
     */
    public function updateUserSettings(Request $request)
    {
        $update = $request->only(['meta', 'value', 'type']);
        if (!blank($update) && isset($update['meta'])) {
            $meta = $update['meta'] ?? false;
            if ($this->isValidMetaKey($meta)) {
                $type = $update['type'] ?? '';
                $value = isset($update['value']) ? strip_tags($update['value']) : '';
                $key = ($type) ? $type . '_' . $meta : $meta;

                if ($this->isValidMetaValue($value, $meta)) {
                    $this->settingsService->updateSettings($key, $value);
                    return response()->json(['msg' => __('Setting has been successfully updated.')]);
                }
            }
        }
        return response()->json(['type' => 'warning', 'msg' => __('Failed to update setting. You may need to reload the page to try again.')]);
    }

    /**
     * @param $name
     * @return boolean
     * @version 1.0.0
     * @since 1.0
     */
    public function isValidOption($name)
    {
        $fields = ['setting_activity_log', 'setting_unusual_activity'];
        return in_array($name, $fields);
    }

    /**
     * @param $meta
     * @return boolean
     * @version 1.0.0
     * @since 1.0
     */
    public function isValidMetaKey($meta)
    {
        $keys = ['perpage', 'display'];
        return in_array($meta, $keys);
    }

    /**
     * @param $value
     * @param $meta
     * @return boolean
     * @version 1.0.0
     * @since 1.0
     */
    public function isValidMetaValue($value, $meta)
    {
        $what = [
            'perpage' => 'pgtn_pr_pg',
            'display' => 'pgtn_dnsty',
        ];

        if ($this->isValidMetaKey($meta)) {
            $config_key = $what[$meta] ?? '';
            if ($config_key) {
                $config = config('investorm.' . $config_key);
                return in_array($value, $config);
            }
        }
        return false;
    }
}
