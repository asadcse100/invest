<?php

namespace App\Http\Controllers\Invest\Admin;

use App\Models\Setting;
use App\Services\InvestormService;
use App\Services\Apis\ExchangeRate\ExchangeRateApi;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
	/**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @version 1.0.0
     * @since 1.0
     */
    public function appsSettings()
    {
    	$config = config('investment');
    	$supportedCurrencies = get_currency_details();
        $currencies = array_filter($supportedCurrencies, function ($key) use ($config) {
            return ( in_array($key, data_get($config, 'currency_switch')) );
        }, ARRAY_FILTER_USE_KEY);

        return view('investment.admin.settings.general', compact('currencies', 'supportedCurrencies'));
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

    /**
     * @param Request $request
     * @return array
     * @version 1.0.0
     * @since 1.0
     */
    private function validateIvOption(Request $request)
    {
        $input = $request->validate([
            'plan_order' => 'nullable',
            'plan_desc_show' => 'nullable',
            'plan_capital_show' => 'nullable',
            'plan_payout_show' => 'nullable',
            'plan_terms_show' => 'nullable',
            'plan_total_percent' => 'nullable',
            'plan_alter_amount' => 'nullable',
            'plan_pg_heading' => 'required',
            'plan_pg_title' => 'nullable',
            'plan_pg_text' => 'nullable',
            'calc_currency' => 'nullable',
            'calc_fx_currencies' => 'nullable|array|max:5',
            'launched_date' => 'nullable',
            'weekend_days' => 'nullable|array|min:1|max:3',
            'admin_confirmtion' => 'nullable',
            'cancel_timeout' => 'nullable',
            'disable_purchase' => 'nullable',
            'disable_title' => 'nullable',
            'disable_notice' => 'nullable',
            'profit_payout' => 'required',
            'profit_payout_amount' => 'nullable|required_if:profit_payout,threshold',
            'auto_transfer' => 'nullable',
            'transfer_mode' => 'nullable',
            'min_transfer' => 'nullable|required_if:auto_transfer,yes|numeric|gt:0'
        ], [
        	'plan_fx_currencies.max' => __('You can select upto 5 currencies for switcher.'),
            'plan_pg_heading.required' => __('Investment page heading is required.'),
            'min_transfer.required_if' => __('Threshold minimum amount is required.'),
            'min_transfer.numeric' => __('Threshold minimum amount should valid number.'),
            'min_transfer.gt' => __('Minimum amount must be greater than zero.'),
        ]);

        if(!isset($input['plan_fx_currencies'])) {
        	$input['plan_fx_currencies'] = [];
        }

        return $input;
    }

    public function saveSettings(Request $request)
    {
        $whats      = ($request->get('form_type')) ? ucfirst(str_replace(['_', '-'], ' ', $request->get('form_type'))) : 'Settings';
        $prefix     = ($request->get('form_prefix')) ? str_replace('-', '_', strtolower($request->get('form_prefix'))) : '';
        $settings   = $this->validateSettings($request);

        if(!isset($settings['weekend_days'])) {
            $settings['weekend_days'] = [];
        }

        foreach ($settings as $key => $value) {
            $key = (Str::startsWith($key, ['app_', 'sys_'])) ? str_replace(['app_', 'sys_'], '', $key) : $key;

            if(!empty($prefix)) {
                $key = $prefix.'_'.$key;
            }

            $value = (is_array($value)) ? json_encode(array_map('strip_tags_map', $value)) : strip_tags($value);

            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return response()->json([ 'msg' => __(':what successfully updated.', ['what' => 'Investment settings']) ]);
    }
}
