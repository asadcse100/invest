<?php

namespace NioModules\WdBank\Controllers;

use NioModules\WdBank\WdBankModule;
use App\Enums\WithdrawMethodStatus;
use App\Models\WithdrawMethod;
use App\Rules\Gtmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class BankSettingsController extends Controller
{

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @version 1.0.1
     * @since 1.0
     */
    public function settingsView()
    {
        $config = config('modules.'.WdBankModule::SLUG);
        $currencies = $this->support_currencies();
        $settings = WithdrawMethod::where('slug', WdBankModule::SLUG)->first();
        return view("WdBank::settings", compact('config', 'settings','currencies'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @version 1.1.0
     * @since 1.0
     */
    public function saveBankSettings(Request $request)
    {
        if(empty($request->slug) || $request->slug !== WdBankModule::SLUG) {
            return response()->json([ 'type' => 'error', 'msg' => __('Sorry, something wrong with withdraw method.') ]);
        }

        $input = $request->validate([
            'slug' => 'required',
            'name' => 'required|string|max:190',
            'desc' => 'required|string|max:190',
            'currencies' => 'required|array|min:1',
            'min_amount' => 'bail|required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'max_amount' => ['bail', 'required', 'numeric', 'min:0', new Gtmin($request->min_amount), 'regex:/^\d+(\.\d{1,2})?$/'],
            'form-fields' => 'required|array',
            'countries' => 'array',
            'status' => 'nullable',
            'config' => 'array',
            'config.country_restriction_type' => 'required|string|in:include,exclude,disable,global',

            'config.meta.min' => 'bail|required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'config.meta.max' => ['bail', 'required', 'numeric', 'min:0', new Gtmin(data_get($request, 'config.meta.min')), 'regex:/^\d+(\.\d{1,2})?$/'],
            'config.currencies.*.min' => 'bail|required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'config.currencies.*.max' => ['bail', 'required', 'numeric', 'min:0', 'regex:/^\d+(\.\d{1,2})?$/', new Gtmin(data_get($request, 'config.meta.min'))]
        ], [
            'slug.required' => __('Sorry, your withdraw method is invalid.'),
            'name.required' => __('Withdraw method title is required.'),
            'desc.required' => __('Withdraw method short description is required.'),
            'currencies.*' => __('Select at-least one currency from supported currencies.'),
            'bank-fields' => __('Invalid display form field value.'),

            'min_amount.required' => __(':Label is required.', ['label' => __("Minimum Amount")]),
            'min_amount.numeric' => __(':Label must be numeric.', ['label' => __("Minimum Amount")]),
            'min_amount.min' => __(':Label must be at least :num.', ['label' => __("Minimum Amount"), 'num' => '0']),
            'min_amount.regex' => __('Allow only :num digit after decimal point in :label.', ['label' => __("Minimum Amount"), 'num' => '2']),

            'max_amount.required' => __(':Label is required.', ['label' => __("Maximum Amount")]),
            'max_amount.numeric' => __(':Label must be numeric.', ['label' => __("Maximum Amount")]),
            'max_amount.min' => __(':Label must be at least :num.', ['label' => __("Maximum Amount"), 'num' => '0']),
            'max_amount.regex' => __('Allow only :num digit after decimal point in :label.', ['label' => __("Maximum Amount"), 'num' => '2']),

            'config.meta.min.required' => __(':Label is required.', ['label' => __("Fixed Amount") . ' ('.__("Min") . ')']),
            'config.meta.min.numeric' => __(':Label must be numeric.', ['label' => __("Fixed Amount") . ' ('.__("Min") . ')']),
            'config.meta.min.min' => __(':Label must be at least :num.', ['label' => __("Fixed Amount") . ' ('.__("Min") . ')', 'num' => '0']),
            'config.meta.min.regex' => __('Allow only :num digit after decimal point in :label.', ['label' => __("Fixed Amount") . ' ('.__("Min") . ')', 'num' => '2']),

            'config.meta.max.required' => __(':Label is required.', ['label' => __("Fixed Amount") . ' ('.__("Max") . ')']),
            'config.meta.max.numeric' => __(':Label must be numeric.', ['label' => __("Fixed Amount") . ' ('.__("Max") . ')']),
            'config.meta.max.min' => __(':Label must be at least :num.', ['label' => __("Fixed Amount") . ' ('.__("Max") . ')', 'num' => '0']),
            'config.meta.max.regex' => __('Allow only :num digit after decimal point in :label.', ['label' => __("Fixed Amount") . ' ('.__("Max") . ')', 'num' => '2']),

            'config.currencies.*.min.required' => __(':Label is required.', ['label' => __("Amount to Withdraw") . ' ('.__("Min") . ')']),
            'config.currencies.*.min.numeric' => __(':Label must be numeric.', ['label' => __("Amount to Withdraw") . ' ('.__("Min") . ')']),
            'config.currencies.*.min.min' => __(':Label must be at least :num.', ['label' => __("Amount to Withdraw") . ' ('.__("Min") . ')', 'num' => '0']),
            'config.currencies.*.min.regex' => __('Allow only :num digit after decimal point in :label.', ['label' => __("Amount to Withdraw") . ' ('.__("Min") . ')', 'num' => '2']),

            'config.currencies.*.max.required' => __(':Label is required.', ['label' => __("Amount to Withdraw") . ' ('.__("Max") . ')']),
            'config.currencies.*.max.numeric' => __(':Label must be numeric.', ['label' => __("Amount to Withdraw") . ' ('.__("Max") . ')']),
            'config.currencies.*.max.min' => __(':Label must be at least :num.', ['label' => __("Amount to Withdraw") . ' ('.__("Max") . ')', 'num' => '0']),
            'config.currencies.*.max.regex' => __('Allow only :num digit after decimal point in :label.', ['label' => __("Amount to Withdraw") . ' ('.__("Max") . ')', 'num' => '2']),
        ]);

        $currencies = $this->support_currencies();

        foreach ($currencies as $currency) {
            if(in_array($currency['code'], $input['currencies'])) {
                if(($input['config']['currencies'][$currency['code']]['max'] != 0) && ($input['config']['currencies'][$currency['code']]['max'] <= $input['config']['currencies'][$currency['code']]['min'])){
                    throw ValidationException::withMessages(['config.currencies.'.$currency['code'].'.max' => __('The maximum amount must be greater than minimum amount for :currency', ['currency' => $currency['code']])]);
                }
                $input['config']['currencies'][$currency['code']] = array_map('strip_tags_map', $input['config']['currencies'][$currency['code']]);
            }
        }

        if (empty($input['countries'])) {
            $input['countries'] = [];
        }

        Cache::forget($input['slug'] . '_filtered_countries');

        if(isset($input['config']['meta']['min']) && $input['config']['meta']['min']) {
            $input['config']['meta']['min'] = abs($input['config']['meta']['min']);
        }
        if(isset($input['config']['meta']['currency']) && $input['config']['meta']['currency']) {
            $input['currencies'] = array_unique(array_merge($input['currencies'], [$input['config']['meta']['currency']]));
        }
        $input['config']['form'] = $input['form-fields'];
        $input['status'] = ($input['status'] == 'active') ? WithdrawMethodStatus::ACTIVE : WithdrawMethodStatus::INACTIVE;
        $input['config']['meta'] = array_map('strip_tags_map', $input['config']['meta']);

        try {
            return $this->wrapInTransaction(function ($input) {
                WithdrawMethod::updateOrCreate(['slug' => $input['slug']], array_map('strip_tags_map', $input));
                return response()->json([ 'msg' => __('Withdraw method successfully updated.') ]);
            }, $input);
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            save_error_log($e);
            throw ValidationException::withMessages(['error' => __('Failed to update withdraw method. Please try again.')]);
        }
    }

    /**
     * @return array
     * @version 1.0.1
     * @since 1.1.0
     */
    public function support_currencies()
    {
        $config = data_get(config('modules.'.WdBankModule::SLUG), 'supported_currency');

        if (module_exist('ExtCurrency', 'addon')) {
            $extcur = app()->get('extcurrency')->currencies('bank');
            if (!blank($extcur)) {
                $config = array_merge($config, $extcur);
            }
        }
        $config = array_unique($config);

        $currencies = array_filter(get_currency_details(), function ($key) use ($config) {
            return (in_array($key, $config));
        }, ARRAY_FILTER_USE_KEY);

        return $currencies;
    }
}
