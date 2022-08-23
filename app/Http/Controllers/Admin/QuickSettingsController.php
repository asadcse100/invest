<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;

use App\Mail\SendTestEmail;
use App\Http\Controllers\Controller;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class QuickSettingsController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type = 'quick';
        $currencies = get_currencies('list'); 
        $system_admin = gss('system'. '_' .'super' .'_'. 'admin');

        $skip = $request->get('skip', false);
        $step = (!empty($request->get('step'))) ? $request->get('step') : 'website';
        $state = (Session::has('step') && Session::get('step')) ? Session::get('step') : $step;

        if ($skip == 'quick') {
            Setting::updateOrCreate(['key' => 'quick' .'_'. 'setup'. '_' .'done'], ['value' => time()]);
            return redirect()->route('admin.dashboard');
        }

        if ($state=='complete' && Session::has('step')) {
            Setting::updateOrCreate(['key' => 'quick'. '_' .'setup' .'_'. 'done'], ['value' => time()]);
            Session::forget('step');
            return view('admin.settings.quick.completed');
        }

        if (in_array($state, ['website', 'mail', 'mailer', 'payments', 'misc', 'currencies']) && (Auth::user()->id == $system_admin)) {
            return view('admin.settings.application-setup', compact('type', 'currencies', 'state'));
        } else {
            return redirect()->route('admin.dashboard');
        }

    }

    /**
     * @param Request $request
     * @return mixed
     * @throws ValidationException
     * @version 1.0.0
     * @since 1.0
     */
    private function validateSettings(Request $request)
    {
        if (filled($formType = $request->get('form_type'))) {
            $method = 'validate'.Str::studly($formType);
            if (method_exists($this, $method)) {
                return $this->$method($request);
            }
        }

        throw ValidationException::withMessages([__("Unable to performed this action!")]);
    }

    private function validateWebsiteSetting(Request $request)
    {
        return $request->validate([
            'site_name' => 'required|min:3',
            'site_email' => 'required|email|not_in:info@yourdomain.com'
        ]);
    }

    /**
     * @param Request $request
     * @return array
     * @version 1.0.0
     * @since 1.0
     */
    private function validateMailSetting(Request $request)
    {
        return $request->validate([
            'mail_recipient' => 'required|email|not_in:info@yourdomain.com',
            'mail_from_name' => 'required',
            'mail_from_email' => 'required|email|not_in:noreply@yourdomain.com',
            'mail_driver' => 'required',
            'mail_smtp_host' => 'nullable|required_if:mail_driver,smtp|not_in:mail.yourdomain.com',
            'mail_smtp_port' => 'nullable|required_if:mail_driver,smtp',
            'mail_smtp_secure' => 'nullable|required_if:mail_driver,smtp',
            'mail_smtp_user' => 'nullable|required_if:mail_driver,smtp',
            'mail_smtp_password' => 'nullable|required_if:mail_driver,smtp'
        ]);
    }

    /**
     * @param Request $request
     * @return array
     * @version 1.0.0
     * @since 1.0
     */
    private function validatePaymentSetting(Request $request)
    {
        return $request->validate([
            'deposit_fiat_minimum' => 'bail|required|numeric|min:0',
            'deposit_fiat_maximum' => 'bail|required|numeric|min:0',
            'deposit_crypto_minimum' => 'bail|required|numeric|min:0',
            'deposit_crypto_maximum' => 'bail|required|numeric|min:0',
            'withdraw_fiat_minimum' => 'bail|required|numeric|min:0',
            'withdraw_fiat_maximum' => 'bail|required|numeric|min:0',
            'withdraw_crypto_minimum' => 'bail|required|numeric|min:0',
            'withdraw_crypto_maximum' => 'bail|required|numeric|min:0'
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function quickRegister(Request $request)
    {
        $type = 'system';
        $systemErrs = $request->session()->has(str_replace(['_', '-'], '', $type).'_error');

        if ($request->ajax()) {
            $state = $request->get('state', false);
            if ($state=='revoke') {
                $this->quickRevoke(); session()->put('system_revoke', true);
                return response()->json([ 'msg' => __('Application status successfully revoked.'), 'timeout' => 900, 'url' => route('admin.quick.register')]);
            } else {
                throw ValidationException::withMessages(['invalid' => __('Sorry, unable to perform the action.')]);
            }
        }

        if (system_admin_setup($type) && !$systemErrs) {
            return redirect()->route('admin.dashboard');
        } else {
            return view('admin.settings.application-setup', compact('type'));
        }
    }

    /**
     * @param Request $request
     * @return array
     * @version 1.0.0
     * @since 1.0
     */
    private function validateCurrenciesSetting(Request $request)
    {
        $input = $request->validate([
            'base_currency' => 'required',
            'alter_currency' => 'required',
            'supported_currency' => 'required|array|min:1'
        ]);

        $input['supported_currency'] = array_fill_keys($input['supported_currency'], "on");
        $input['supported_currency'][$request->get('base_currency')] = "on";
        $input['supported_currency'][$request->get('alter_currency')] = "on";

        return $input;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     * @version 1.0.0
     * @since 1.0
     */
    public function quickRevoke()
    {
        upss('app_acquire', null);
        upss('site_merchandise', null);
        upss('payout_batch', null);
        Cache::forget(get_m5host());
        return true;
    }

    /**
     * @param Request $request
     * @return array
     * @version 1.0.0
     * @since 1.0
     */
    private function validateMiscSetting(Request $request)
    {
        return $request->validate([
            'time_zone' => 'required',
            'date_format' => 'required',
            'time_format' => 'required',
            'decimal_fiat_calc' => 'required|numeric|min:2|max:6',
            'decimal_crypto_calc' => 'required|numeric|min:4|max:12',
            'decimal_fiat_display' => 'required|numeric|min:1|max:4',
            'decimal_crypto_display' => 'required|numeric|min:4|max:8'
        ]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     * @version 1.0.0
     * @since 1.0
     */
    public function updateSettings(Request $request)
    {
        $whats    = ($request->get('form_type')) ? ucfirst(str_replace(['_', '-'], ' ', $request->get('form_type'))) : 'Settings';
        $next     = ($request->get('form_next')) ? str_replace('-', '_', strtolower($request->get('form_next'))) : '';
        
        if ($request->form_type == 'test-mail-setting') {
            $tested = false;
            try {
                $user = Auth::user();
                $sendTo = $user->email;
                $slug = 'users-welcome-email';

                Mail::to($sendTo)->send(new SendTestEmail($user, $slug));
                $tested = true;
             } catch (\Exception $e) {
                save_mailer_log($e, 'system-email-test');
                throw ValidationException::withMessages(['invalid' => __('Unable to send test email. Please check your email configuration.'), 'trace' => $e->getMessage()]);
             }
             if ($tested) {
                Setting::updateOrCreate(['key' => 'system_mailer_tested'], ['value' => time()]);
             }
        } else {
            $settings   = $this->validateSettings($request);
            foreach ($settings as $key => $value) {
                $value = (is_array($value)) ? json_encode(array_map('strip_tags_map', $value)) : strip_tags($value);
                Setting::updateOrCreate(['key' => $key], ['value' => $value]);
            }
        }

        if ($next) {
            Session::flash('step', $next);
            return response()->json([ 'msg' => __(':what successfully updated.', ['what' => $whats]), 'timeout' => 400, 'url' => route('admin.quick-setup', ['step' => $next]) ]);
        } else {
            Session::forget('step');
            return response()->json([ 'msg' => __(':what successfully updated.', ['what' => $whats]), 'timeout' => 900, 'url' => route('admin.dashboard')]);
        }
    }
}
