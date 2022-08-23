<?php

namespace App\Http\Controllers\Admin;

use App\Rules\Gtmin;
use App\Enums\Boolean;
use App\Enums\PaymentMethodStatus;
use App\Enums\WithdrawMethodStatus;
use App\Enums\PaymentProcessorType;

use App\Models\WithdrawMethod;
use App\Models\PaymentMethod;
use App\Models\Language;
use App\Models\IvScheme;
use App\Models\Setting;
use App\Models\Page;

use App\Mail\SendTestEmail;
use App\Http\Controllers\Controller;
use App\Services\Apis\ExchangeRate\ExchangeRateApi;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ApplicationSettingsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @version 1.0.0
     * @since 1.0
     */
    public function general()
    {
        if (Schema::hasTable('languages')) {
            $languages = Language::where('status', Boolean::YES)->get();
        } else {
            $languages = [];
        }

        return view('admin.settings.general', compact('languages'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @version 1.0.0
     * @since 1.0
     */
    public function currency()
    {
        $currenciesAll = get_currencies('list');
        $currencies = get_currencies('list', '', true);
        $exchange_methods = exchange_methods();
        $exchange_rates = get_exchange_rates();

        return view('admin.settings.currency', compact(
            'currencies',
            'currenciesAll',
            'exchange_methods',
            'exchange_rates'
        ));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @version 1.0.0
     * @since 1.0
     */
    public function api()
    {
        return view('admin.settings.api');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @version 1.0.0
     * @since 1.0
     */
    public function misc()
    {
        return view('admin.settings.misc');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @version 1.0.0
     * @since 1.0
     */
    public function website()
    {
        return view('admin.settings.website');
    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @version 1.0.0
     * @since 1.0
     */
    public function userpanel()
    {
        if (!Schema::hasColumn('pages', 'pid')) {
            $pages = Page::whereNotIn('status', ['inactive'])->select('id', 'name', 'menu_name', 'slug')->get();
        } else {
            $pages = Page::main()->whereNotIn('status', ['inactive'])->select('id', 'name', 'menu_name', 'slug')->get();
        }
        $schemes = IvScheme::where('status', ['active'])->select('id', 'name', 'short')->get();
        $currencies = active_currencies('list');
        return view('admin.settings.userpanel', compact('pages', 'schemes', 'currencies'));
    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @version 1.0.0
     * @since 1.0
     */
    public function appearance()
    {
        return view('admin.settings.appearance');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @version 1.0.0
     * @since 1.0
     */
    public function rewards()
    {
        return view('admin.settings.rewards');
    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @version 1.0.0
     * @since 1.0
     */
    public function referrals()
    {
        return view('admin.settings.referrals');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @version 1.0.0
     * @since 1.0
     */
    public function componentSystem()
    {
        return view('admin.settings.comps-system');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @version 1.0.0
     * @since 1.0
     */
    public function email()
    {
        return view('admin.settings.email');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @version 1.0.0
     * @since 1.0
     */
    public function systemStatus()
    {
        return view('admin.settings.systems');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @version 1.0.0
     * @since 1.0
     */
    public function paymentMethods()
    {
        $paymentMethods = available_payment_methods();
        $methodDetail = PaymentMethod::whereIn('slug', array_column($paymentMethods, 'slug'))->get()->keyBy('slug');
        return view('admin.settings.gateway-payment', compact('paymentMethods', 'methodDetail'));
    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @version 1.0.0
     * @since 1.0
     */
    public function withdrawMethods()
    {
        $withdrawMethods = available_withdraw_methods();
        $methodDetail = WithdrawMethod::whereIn('slug', array_column($withdrawMethods, 'slug'))->get()->keyBy('slug');
        return view('admin.settings.gateway-withdraw', compact('withdrawMethods', 'methodDetail'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @version 1.0.0
     * @since 1.0
     */
    public function paymentOptions()
    {
        return view('admin.settings.gateway-options');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @version 1.0.0
     * @since 1.1.2
     */
    public function social()
    {
        return view('admin.settings.social');
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
            $method = 'validate' . Str::studly($formType);
            if (method_exists($this, $method)) {
                return $this->$method($request);
            }
        }

        throw ValidationException::withMessages([__("Unable to performed this action!")]);
    }

    /**
     * @param Request $request
     * @return array
     * @version 1.0.0
     * @since 1.0
     */
    private function validateGeneralSettings(Request $request)
    {
        return $request->validate([
            'time_zone' => 'required',
            'date_format' => 'required',
            'time_format' => 'required',
            'decimal_fiat_display' => 'bail|required|numeric|min:1|max:4',
            'decimal_crypto_display' => 'bail|required|numeric|min:4|max:8',
            'decimal_fiat_calc' => 'bail|required|numeric|min:2|max:6',
            'decimal_crypto_calc' => 'bail|required|numeric|min:4|max:12',
        ]);
    }

    /**
     * @param Request $request
     * @return array
     * @version 1.0.0
     * @since 1.0
     */
    private function validateLanguageSettings(Request $request)
    {
        return $request->validate([
            'default_system' => "nullable",
            'default_public' => "nullable",
            'show_as' => "nullable",
            'switcher' => "nullable|in:on,off"
        ]);
    }


    /**
     * @param Request $request
     * @return array
     * @version 1.0.0
     * @since 1.1.2
     */
    private function validateSystemComponentSettings(Request $request)
    {
        return $request->validate(
            [
                'gdpr_enable' => 'required|in:yes,no',
                'cookie_deny_btn' => 'required|in:yes,no',
                'cookie_consent_text' => 'required|string',
                'cookie_accept_btn_txt' => 'required|string',
                'cookie_deny_btn_txt' => 'required_if:cookie_deny_btn,yes|nullable|string',
                'cookie_banner_position' => 'required|in:bbox-left,bbox-right,bottom',
                'cookie_banner_background' => 'required|in:dark,light'
            ],
            [
                'cookie_accept_btn_txt.required' => __('The accept button text field is required.'),
                'cookie_accept_btn_txt.string' => __('The accept button text field must be string.'),
                'cookie_deny_btn_txt.required_if' => __('The deny button text field is required when the deny button is enabled.'),
                'cookie_deny_btn_txt.string' => __('The deny button text field must be string.')
            ]
        );
    }

    /**
     * @param Request $request
     * @return array
     * @version 1.0.0
     * @since 1.1.3
     */
    private function validateCountrySettings(Request $request)
    {
        $input = $request->validate([
            'country_restriction_type' => "required|string|in:disable,include,exclude",
            'countries' => "array"
        ]);

        if (empty($input['countries'])) {
            $input['countries'] = [];
        }

        if (Cache::has('filtered_countries')) {
            Cache::forget('filtered_countries');
        }

        return $input;
    }

    /**
     * @param Request $request
     * @return array
     * @version 1.0.0
     * @since 1.0
     */
    private function validateApplicationSettings(Request $request)
    {
        return $request->validate([
            'signup_allow' => 'required',
            'signup_form_fields' => 'nullable|array',
            'email_verification' => 'required',
            'referral_system' => 'required',
            'maintenance_mode' => 'nullable',
            'maintenance_notice' => 'nullable'
        ]);
    }


    /**
     * @param Request $request
     * @return array
     * @version 1.0.0
     * @since 1.0
     */
    private function validateEmailSettings(Request $request)
    {
        return $request->validate([
            'mail_recipient' => 'required|email|not_in:info@yourdomain.com',
            'mail_recipient_alter' => 'nullable|email|not_in:info@yourdomain.com',
            'mail_from_name' => 'required',
            'mail_from_email' => 'required|email|not_in:noreply@yourdomain.com',
            'mail_global_footer' => 'nullable',
            'mail_driver' => 'required',
            'mail_smtp_host' => 'nullable|required_if:mail_driver,smtp',
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
    private function validateCurrenciesSettings(Request $request)
    {
        $rules = [
            'base_currency' => 'required',
            'alter_currency' => 'required',
            'supported_currency' => 'required|array|min:1',
            'exchange_method' => 'required',
            'exchange_auto_update' => 'required_if:exchange_method,automatic|in:20,30,45,60,120',
            'manual_exchange_rate' => 'required_if:exchange_method,manual|array|min:1',
            'fiat_rounded' => 'required',
            'crypto_rounded' => 'required',
        ];

        $input = $request->validate($rules);

        $input['supported_currency'][$request->get('base_currency')] = "on";
        $input['supported_currency'][$request->get('alter_currency')] = "on";

        return $input;
    }


    /**
     * @param Request $request
     * @return array
     * @version 1.0.0
     * @since 1.0
     */
    private function validateWebsiteSetting(Request $request)
    {
        return $request->validate([
            'site_name' => 'required|min:3',
            'site_email' => 'required|email|not_in:info@yourdomain.com',
            'site_copyright' => 'nullable',
            'main_website' => 'nullable|url',
            'facebook_link' => 'nullable',
            'twitter_link' => 'nullable',
            'linkedin_link' => 'nullable',
            'youtube_link' => 'nullable',
            'medium_link' => 'nullable',
            'telegram_link' => 'nullable',
            'github_link' => 'nullable',
            'pinterest_link' => 'nullable',
            'instagram_link' => 'nullable',
            'whatsapp_link' => 'nullable',
            'reddit_link' => 'nullable',
        ]);
    }


    /**
     * @param Request $request
     * @return array
     * @version 1.0.0
     * @since 1.0
     */
    private function validateUserpanelSetting(Request $request)
    {
        return $request->validate([
            'main_nav' => 'nullable|array',
            'main_menu' => 'nullable|array',
            'main_menu_heading' => 'nullable|max:30',
            'footer_menu' => 'nullable|array',
            'page_terms' => 'nullable',
            'page_privacy' => 'nullable',
            'page_fee_deposit' => 'nullable',
            'page_fee_withdraw' => 'nullable',
            'page_contact' => 'nullable',
            'page_contact_form' => 'nullable',
        ]);
    }


    /**
     * @param Request $request
     * @return array
     * @version 1.0.0
     * @since 1.0
     */
    private function validatePublicPagesOption(Request $request)
    {
        return $request->validate([
            'site_disclaimer' => 'nullable',
            'front_page_enable' => 'nullable',
            'front_page_extra' => 'nullable',
            'front_page_title' => 'required',
            'invest_page_enable' => 'nullable',
            'top_iv_plan_x0' => 'nullable|required_if:front_page_enable,yes',
            'top_iv_plan_x1' => 'nullable|required_if:front_page_enable,yes',
            'top_iv_plan_x2' => 'nullable|required_if:front_page_enable,yes',
            'iv_show_plans' => 'required',
            'iv_plan_order' => 'nullable',
            'extra_step1_title' => 'nullable|required_if:front_page_extra,on',
            'extra_step1_text' => 'nullable|required_if:front_page_extra,on',
            'extra_step2_title' => 'nullable|required_if:front_page_extra,on',
            'extra_step2_text' => 'nullable|required_if:front_page_extra,on',
            'extra_step3_title' => 'nullable|required_if:front_page_extra,on',
            'extra_step3_text' => 'nullable|required_if:front_page_extra,on',
            'extra_step4_title' => 'nullable|required_if:front_page_extra,on',
            'extra_step4_text' => 'nullable|required_if:front_page_extra,on',
            'extra_step4_icons' => 'nullable|array|max:5',
        ], [
            'iv_show_plans.required' => __("Select plan display option for investment page."),
            'top_iv_plan_x0.required_if' => __("Please select highlight plan for home page."),
            'top_iv_plan_x1.required_if' => __("Please select first plan for home page."),
            'top_iv_plan_x2.required_if' => __("Please select second plan for home page."),
            'extra_step1_title.required_if' => __("Heading is required for box :num.", ['num' => '#1']),
            'extra_step1_text.required_if' => __("Short info is required for box :num.", ['num' => '#1']),
            'extra_step2_title.required_if' => __("Heading is required for box :num.", ['num' => '#2']),
            'extra_step2_text.required_if' => __("Short info is required for box :num.", ['num' => '#2']),
            'extra_step3_title.required_if' => __("Heading is required for box :num.", ['num' => '#3']),
            'extra_step3_text.required_if' => __("Short info is required for box :num.", ['num' => '#3']),
            'extra_step4_title.required_if' => __("Heading is required for box :num.", ['num' => '#4']),
            'extra_step4_text.required_if' => __("Short info is required for box :num.", ['num' => '#4']),
            'extra_step4_icons.max' => __("Max :num payment icons you can select.", ['num' => '5']),
            'extra_step4_icons.array' => __("Payment icons should be valid format."),
        ]);
    }


    /**
     * @param Request $request
     * @return array
     * @version 1.0.0
     * @since 1.0
     */
    private function validateThemeCustomize(Request $request)
    {
        return $request->validate([
            'page_skin' => 'required',
            'auth_skin' => 'required',
            'auth_layout' => 'required',
            'theme_mode' => 'required',
            'theme_skin' => 'required',
            'sidebar_user' => 'required',
            'sidebar_admin' => 'required',
            'theme_mode_admin' => 'required',
            'theme_skin_admin' => 'required',
        ]);
    }


    /**
     * @param Request $request
     * @return array
     * @version 1.0.0
     * @since 1.0
     */
    private function validateOtherOptions(Request $request)
    {
        return $request->validate([
            'alert_wd_account' => 'nullable',
            'alert_profile_basic' => 'nullable',
            'header_notice_show' => 'nullable',
            'header_notice_title' => 'nullable|required_if:header_notice_show,yes|max:75',
            'header_notice_text' => 'nullable|max:100',
            'header_notice_link' => 'nullable|url',
            'header_notice_date' => 'nullable|date_format:m/d/Y',
            'rates_ticker_display' => 'nullable',
            'rates_ticker_from' => 'nullable',
            'rates_ticker_fx' => 'nullable|in:all,only,custom',
            'rates_ticker_currencies' => 'nullable|required_if:rates_ticker_fx,custom|array|min:4',
            'support_card_show' => 'nullable',
            'support_card_title' => 'nullable|required_if:support_card_show,yes|max:75',
            'support_card_text' => 'nullable|required_if:support_card_show,yes|max:250',
            'support_card_link' => 'nullable|url',
        ], [
            'header_notice_date.date_format' => __("Enter date in this 'mm/dd/yyyy' format."),
            'rates_ticker_currencies.required_if' => __("Select at-least one or more currency for ticker."),
        ]);
    }


    /**
     * @param Request $request
     * @return array
     * @version 1.0.0
     * @since 1.0
     */
    private function validateRewardOptions(Request $request)
    {
        return $request->validate([
            'signup_bonus_allow' => 'nullable',
            'signup_bonus_amount' => 'nullable|required_if:signup_bonus_allow,yes|numeric|min:0',
            'deposit_bonus_allow' => 'nullable',
            'deposit_bonus_amount' => 'nullable|required_if:deposit_bonus_allow,yes',
            'deposit_bonus_type' => 'nullable|required_if:deposit_bonus_allow,yes',
        ]);
    }


    /**
     * @param Request $request
     * @return array
     * @version 1.0.0
     * @since 1.0
     */
    private function validateReferralSettings(Request $request)
    {
        $rules = [
            'system' => 'required',
            'invite_title' => 'nullable|required_if:system,on',
            'invite_text' => 'nullable|required_if:system,on',
            'invite_redirect' => 'required|in:home,invest,register',
            'show_referred_users' => 'required|in:yes,no',
            'user_table_opts' => 'array',
            'user_table_opts.*' => 'nullable|in:earning,compact',
            'signup_user' => 'nullable',
            'signup_user_bonus' => 'nullable|required_if:signup_user,yes|numeric|min:0',
            'signup_user_reward' => 'nullable',
            'deposit_user' => 'nullable',
            'deposit_user_allow' => 'nullable|required_if:deposit_user,yes',
            'deposit_user_max' => 'nullable|required_if:deposit_user_allow,number|numeric|integer|min:2',
            'deposit_user_bonus' => 'nullable|required_if:deposit_user,yes|numeric|min:0',
            'deposit_user_type' => 'nullable|required_if:deposit_user,yes',
            'signup_referer' => 'nullable',
            'signup_referer_bonus' => 'nullable|required_if:signup_referer,yes|numeric|min:0',
            'deposit_referer' => 'nullable',
            'deposit_referer_allow' => 'nullable',
            'deposit_referer_max' => 'nullable|required_if:deposit_referer_allow,number|numeric|integer|min:2',
            'deposit_referer_bonus' => 'nullable|required_if:deposit_referer,yes|numeric|min:0',
            'deposit_referer_type' => 'nullable|required_if:deposit_referer,yes',
        ];

        return $request->validate($rules);
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
            'logout_redirect' => 'nullable',
            'custom_stylesheet' => 'nullable',
            'google_track_id' => 'nullable',
            'seo_description' => 'nullable',
            'seo_keyword' => 'nullable',
            'login_seo_title' => 'nullable',
            'login_seo_description' => 'nullable',
            'registration_seo_title' => 'nullable',
            'registration_seo_description' => 'nullable',
            'seo_description_home' => 'nullable',
            'seo_keyword_home' => 'nullable',
            'header_code' => 'nullable',
            'footer_code' => 'nullable',
            'og_title' => 'nullable',
            'og_description' => 'nullable',
            'og_image' => 'nullable|url'
        ], [
            'og_image.url' => __("The image url should be a full url including 'https'."),
        ]);
    }


    /**
     * @param Request $request
     * @return array
     * @version 1.0.0
     * @since 1.0
     */
    private function validateApiCredential(Request $request)
    {
        return $request->validate([
            'tawk_api_key' => 'nullable',
            'recaptcha_score' => 'numeric|integer|min:2|max:10',
            'recaptcha_site_key' => 'nullable',
            'recaptcha_secret_key' => 'nullable',
            'exratesapi_access_key' => 'nullable',
        ]);
    }


    /**
     * @param Request $request
     * @return array
     * @version 1.0.0
     * @since 1.0
     */
    private function validateDepositSettings(Request $request)
    {
        return $request->validate([
            'limit_request' => 'nullable',
            'cancel_timeout' => 'nullable',
            'amount_base' => 'nullable',
            'fiat_minimum' => 'bail|required|numeric|min:0',
            'fiat_maximum' => ['bail', 'required', 'numeric', 'min:0', new Gtmin($request->fiat_minimum)],
            'crypto_minimum' => 'bail|required|numeric|min:0',
            'crypto_maximum' => ['bail', 'required', 'numeric', 'min:0', new Gtmin($request->crypto_minimum)],
            'disable_request' => 'nullable',
            'disable_title' => 'nullable',
            'disable_notice' => 'nullable',
        ]);
    }


    /**
     * @param Request $request
     * @return array
     * @version 1.0.0
     * @since 1.0
     */
    private function validateWithdrawSettings(Request $request)
    {
        return $request->validate([
            'limit_request' => 'nullable',
            'cancel_timeout' => 'nullable',
            'fiat_minimum' => 'bail|required|numeric|min:0',
            'fiat_maximum' => ['bail', 'required', 'numeric', 'min:0', new Gtmin($request->fiat_minimum)],
            'crypto_minimum' => 'bail|required|numeric|min:0',
            'crypto_maximum' => ['bail', 'required', 'numeric', 'min:0', new Gtmin($request->crypto_minimum)],
            'disable_request' => 'nullable',
            'disable_title' => 'nullable',
            'disable_notice' => 'nullable',
        ]);
    }

    /**
     * @param Request $request
     * @return array
     * @version 1.0.0
     * @since 1.1.2
     */
    private function validateSocialSettings(Request $request)
    {
        return $request->validate([
            'auth' => 'nullable',
            'facebook_id' => 'nullable',
            'facebook_secret' => 'nullable',
            'google_id' => 'nullable',
            'google_secret' => 'nullable'
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     * @version 1.0.0
     * @since 1.0
     */
    public function saveSettings(Request $request)
    {
        $whats      = ($request->get('form_type')) ? ucfirst(str_replace(['_', '-'], ' ', $request->get('form_type'))) : 'Settings';
        $prefix     = ($request->get('form_prefix')) ? str_replace('-', '_', strtolower($request->get('form_prefix'))) : '';
        $settings   = $this->validateSettings($request);
        $reload = false;


        if ($request->get('form_type') == 'userpanel-setting') {
            if (!isset($settings['footer_menu'])) {
                $settings['footer_menu'] = [];
            }

            if (!isset($settings['main_menu'])) {
                $settings['main_menu'] = [];
            }

            if (!isset($settings['main_nav'])) {
                $settings['main_nav'] = [];
            }
        }
        if ($request->get('form_type') == 'public-pages-option') {
            if (!isset($settings['extra_step4_icons'])) {
                $settings['extra_step4_icons'] = [];
            }
        }

        if ($request->get('form_type') == 'other-options') {
            if (!isset($settings['rates_ticker_currencies'])) {
                $settings['rates_ticker_currencies'] = [];
            }
        }

        if ($request->get('form_type') == 'language-settings' && !Schema::hasTable('languages')) {
            throw ValidationException::withMessages(['invalid' => __('Sorry, unable to find language system in apps.')]);
        }

        foreach ($settings as $key => $value) {
            $key = (Str::startsWith($key, ['app_', 'sys_'])) ? str_replace(['app_', 'sys_'], '', $key) : $key;

            if (!empty($prefix)) {
                $key = $prefix.'_'.$key;
            }

            if ($key == 'header_code' || $key == 'footer_code') {
                $value = (is_array($value)) ? json_encode($value) : $value;
            } else {
                $value = (is_array($value)) ? json_encode(array_map('strip_tags_map', $value)) : strip_tags($value);
            }

            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        if ($request->get('form_type') === 'currencies-settings' && $request->get('exchange_method') === 'automatic') {
            if (env('APP_ENV', 'production') != 'local') {
                $exchange = new ExchangeRateApi();
                $exchange->refreshCache(true);
            }
        }
        if($request->get('form_type') === 'theme-customize') {
            Cache::forget('dark_stylesheet');
            Cache::forget('dark_stylesheet_admin');
            $reload = 900;
        }
        if ($request->get('form_type') == 'other-options' && Cache::has('rates_ticker')) {
            Cache::forget('rates_ticker');
        }

        return response()->json(['msg' => __(':what successfully updated.', ['what' => $whats]), 'reload' => $reload]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     * @version 1.0.0
     * @since 1.0
     */
    public function updateMethodActivation(Request $request)
    {
        $type = $request->get('type') ?? false;
        $status = $request->get('status') ?? false;
        $state = ($status == 'active') ? __('Enabled') : __('Disabled');

        if (!empty($type)) {
            if ($type == PaymentProcessorType::PAYMENT) {
                $model  = PaymentMethod::query();
                $status = ($status=='active') ? PaymentMethodStatus::ACTIVE : PaymentMethodStatus::INACTIVE;
            } elseif ($type == PaymentProcessorType::WITHDRAW) {
                $model  = WithdrawMethod::query();
                $status = ($status=='active') ? WithdrawMethodStatus::ACTIVE : WithdrawMethodStatus::INACTIVE;
            } else {
                throw ValidationException::withMessages(['method' => __('Opps! We unable to process your request. Please reload the page and try again.')]);
            }

            $method = $model->where('slug', $request->get('slug'))->first();
            if (blank($method)) {
                throw ValidationException::withMessages(['method' => __('The :what method not found. Please reload the page and try again.', ['what' => ucfirst($type)])]);
            }

            $method->update(['status' => $status]);
            return response()->json(['msg' => __(':what method successfully :state.', ['what' => ucfirst($type), 'state' => strtolower($state)])]);
        }

        return response()->json(['msg' => __('Opps! Nothing to update!')]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     * @version 1.0.0
     * @since 1.0
     */
    public function sendTestEmail(Request $request)
    {
        $input = $request->validate([
            'send_to' => 'nullable|email'
        ], [
            'send_to.email' => __("Please provide a valid email address.")
        ]);

        try {
            $user = Auth::user();
            $sendTo = $input['send_to'] ?? $user->email;
            $slug = 'users-welcome-email';

            Mail::to($sendTo)->send(new SendTestEmail($user, $slug));
            return response()->json([ 'title' => 'Test Email Sent', 'msg' => __("Email (:address) sent to address, please check your email.", ['address' => $sendTo]) ]);
        } catch (\Exception $e) {
            save_mailer_log($e, 'system-email-test');
            throw ValidationException::withMessages(['invalid' => __('Unable to send test email. Please check your email configuration.'), 'trace' => $e->getMessage()]);
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     * @version 1.0.0
     * @since 1.0
     */

    public function storeBranding(Request $request)
    {
        $validPermissionCodes = ['0755', '0775', '0777'];

        if (Storage::has('brand')) {
            $brandPermission = substr(sprintf('%o', fileperms(Storage::path('brand'))), -4);
            if (!in_array($brandPermission, $validPermissionCodes)) {
                throw ValidationException::withMessages(['error'=>__("Please check permission for 'brand' folder in storage/app directory.")]);
            }
        } else {
            $storagePermission =  substr(sprintf('%o', fileperms(Storage::path(''))), -4);
            if (!in_array($storagePermission, $validPermissionCodes)) {
                throw ValidationException::withMessages(['error'=>__("Please check permission for 'app' folder in storage directory.")]);
            }

            try {
                Storage::makeDirectory('brand');
            } catch (\Exception $e) {
                throw ValidationException::withMessages(['error'=>__("Unable to create 'brand' folder. Please check permission for 'app' folder in storage directory.")]);
            }
        }

        $regular = 'nullable|bail|file|mimes:jpg,png,jpeg,svg|max:512|dimensions:max_width=400,max_height=200';
        $retina = 'nullable|bail|file|mimes:jpg,png,jpeg,svg|max:768|dimensions:max_width=800,max_height=400';

        $files = [
            'logo_mail' => $regular,
            'logo_light' => $regular,
            'logo_dark' => $regular,
            'logo_light2x' => $retina,
            'logo_dark2x' => $retina,
        ];

        $request->validate($files, [
            'logo_mail.file' => __('Please upload a valid file for email template logo.'),
            'logo_mail.mimes' => __('Sorry, invalid file extension in email template logo. You can upload jpeg, png or svg image.'),
            'logo_mail.max' => __('File size is too large as it limited to 500KB for email template logo.'),
            'logo_mail.dimensions' => __('The image dimensions should be under 400px width and 200px height for mail template logo.'),

            'logo_light.file' => __('Please upload a valid file for website light logo.'),
            'logo_light.mimes' => __('Sorry, invalid file extension in website light logo. You can upload jpeg, png or svg image.'),
            'logo_light.max' => __('File size is too large as it limited to 500KB for website light logo.'),
            'logo_light.dimensions' => __('The image dimensions should be under 400px width and 200px height for regular logo.'),

            'logo_dark.file' => __('Please upload a valid file for website dark logo.'),
            'logo_dark.mimes' => __('Sorry, invalid file extension in website dark logo. You can upload jpeg, png or svg image.'),
            'logo_dark.max' => __('File size is too large as it limited to 500KB for website dark logo.'),
            'logo_dark.dimensions' => __('The image dimensions should be under 400px width and 200px height for regular logo.'),

            'logo_light2x.file' => __('Please upload a valid file for retina light logo.'),
            'logo_light2x.mimes' => __('Sorry, invalid file extension in retina light logo. You can upload jpeg, png or svg image.'),
            'logo_light2x.max' => __('File size is too large as it limited to 750KB for retina light logo.'),
            'logo_light2x.dimensions' => __('The image dimensions should be under 800px width and 400px height for retina logo.'),

            'logo_dark2x.file' => __('Please upload a valid file for retina dark logo.'),
            'logo_dark2x.mimes' => __('Sorry, invalid file extension in retina dark logo. You can upload jpeg, png or svg image.'),
            'logo_dark2x.max' => __('File size is too large as it limited to 750KB for retina dark logo.'),
            'logo_dark2x.dimensions' => __('The image dimensions should be under 800px width and 400px height for retina logo.'),
        ]);

        $requestedFiles = $request->only(array_keys($files));

        if (empty($requestedFiles)) {
            return response()->json(['error' => __("Sorry, something went wrong while uploading.")]);
        }

        $errors = false;

        foreach ($requestedFiles as $key => $file) {
            $prevPath = gss('website_'.$key);
            try {
                $path = $file->store('brand');
                upss('website_'.$key, $path);

                if ($key == "logo_mail") {
                    $file->move(public_dir('images'), 'logo-mailer.'.$file->extension());
                }
            } catch (\Exception $e) {
                $errors = true;
            }

            if ($errors === false) {
                if (!empty($prevPath) && Storage::has($prevPath)) {
                    Storage::delete($prevPath);
                }
                Cache::forget('website_'.$key);
            }
        }

        if ($errors) {
            return response()->json(['reload' => true, 'error' => __("Something went wrong while uploading. Please check your storage folder permission.")]);
        } else {
            return response()->json(['reload' => true, 'success' => __("The website logo successfully uploaded.")]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @version 1.0.0
     * @since 1.0
     */
    public function cacheClear(Request $request)
    {
        try {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');

            upss('cache_version', time());
            upss('payout_locked_profit', null);
            upss('payout_locked_plan', null);

            $message = __("Application cache has been cleared successfully.");
        } catch (\Exception $e) {
            save_msg_log($e->getMessage(), 'info');
            $message = __("Sorry, we are unable to clear application cache.");
        }

        if ($request->ajax()) {
            return response()->json(['msg' => $message]);
        }

        return back()->with(['notice' => $message]);
    }
}
