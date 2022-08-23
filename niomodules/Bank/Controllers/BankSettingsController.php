<?php

namespace NioModules\Bank\Controllers;

use NioModules\Bank\BankModule;
use App\Enums\PaymentMethodStatus;
use App\Models\PaymentMethod;
use App\Rules\Gtmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        $config = config('modules.'.BankModule::SLUG);
        $currencies = data_get($config, 'supported_currency');

        if (module_exist('ExtCurrency', 'addon')) {
            $extcur = app()->get('extcurrency')->currencies('bank');
            if (!blank($extcur)) {
                $currencies = array_merge($currencies, $extcur);
            }
        }
        $currencies = array_unique($currencies);

        $supportedCurrencies = array_filter(get_currency_details(), function ($key) use ($currencies) {
            return (in_array($key, $currencies));
        }, ARRAY_FILTER_USE_KEY);

        $settings = PaymentMethod::where('slug', BankModule::SLUG)->first();
        return view("Bank::settings", compact('config', 'settings', 'supportedCurrencies'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @version 1.0.1
     * @since 1.0
     */
    public function saveBankSettings(Request $request)
    {
        if(empty($request->slug) || $request->slug !== BankModule::SLUG) {
            return response()->json([ 'type' => 'error', 'msg' => __('Sorry, something wrong with payment method.') ]);
        }

        $input = $request->validate([
            'slug' => 'required',
            'name' => 'required|string|max:190',
            'desc' => 'required|string|max:190',
            'currencies' => 'required',
            'status' => 'nullable',
            'min_amount' => 'bail|required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'max_amount' => ['bail', 'required', 'numeric', 'min:0', new Gtmin($request->min_amount), 'regex:/^\d+(\.\d{1,2})?$/'],
            'config' => 'array',
            'config.ac.account_name' => 'required|string',
            'config.ac.account_number' => 'required|string',
            'config.ac.bank_name' => 'required',
            'config.ac.bank_short' => 'required',
            'config.meta.min' => 'bail|required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'config.meta.max' => ['bail', 'required', 'numeric', 'min:0', new Gtmin(data_get($request, 'config.meta.min')), 'regex:/^\d+(\.\d{1,2})?$/'],
        ], [
            'slug.required' => __('Sorry, your payment method is invalid.'),
            'name.required' => __('Payment method title is required.'),
            'desc.required' => __('Payment method short description is required.'),
            'currencies.required' => __('You must select your local currency.'),

            'min_amount.required' => __(':Label is required.', ['label' => __("Minimum Amount")]),
            'min_amount.numeric' => __(':Label must be numeric.', ['label' => __("Minimum Amount")]),
            'min_amount.min' => __(':Label must be at least :num.', ['label' => __("Minimum Amount"), 'num' => '0']),
            'min_amount.regex' => __('Allow only :num digit after decimal point in :label.', ['label' => __("Minimum Amount"), 'num' => '2']),

            'max_amount.required' => __(':Label is required.', ['label' => __("Maximum Amount")]),
            'max_amount.numeric' => __(':Label must be numeric.', ['label' => __("Maximum Amount")]),
            'max_amount.min' => __(':Label must be at least :num.', ['label' => __("Maximum Amount"), 'num' => '0']),
            'max_amount.regex' => __('Allow only :num digit after decimal point in :label.', ['label' => __("Maximum Amount"), 'num' => '2']),

            'config.ac.account_name.required' => __('Account name is required on bank details.'),
            'config.ac.account_number.required' => __('Account number is required on bank details.'),
            'config.ac.bank_name.required' => __('The bank name is required.'),
            'config.ac.bank_short.required' => __('The bank short name is required.'),

            'config.meta.min.required' => __(':Label is required.', ['label' => __("Fixed Amount") . ' ('.__("Min") . ')']),
            'config.meta.min.numeric' => __(':Label must be numeric.', ['label' => __("Fixed Amount") . ' ('.__("Min") . ')']),
            'config.meta.min.min' => __(':Label must be at least :num.', ['label' => __("Fixed Amount") . ' ('.__("Min") . ')', 'num' => '0']),
            'config.meta.min.regex' => __('Allow only :num digit after decimal point in :label.', ['label' => __("Fixed Amount") . ' ('.__("Min") . ')', 'num' => '2']),

            'config.meta.max.required' => __(':Label is required.', ['label' => __("Fixed Amount") . ' ('.__("Max") . ')']),
            'config.meta.max.numeric' => __(':Label must be numeric.', ['label' => __("Fixed Amount") . ' ('.__("Max") . ')']),
            'config.meta.max.min' => __(':Label must be at least :num.', ['label' => __("Fixed Amount") . ' ('.__("Max") . ')', 'num' => '0']),
            'config.meta.max.regex' => __('Allow only :num digit after decimal point in :label.', ['label' => __("Fixed Amount") . ' ('.__("Max") . ')', 'num' => '2']),
        ]);

        $input['countries'] = array();
        $input['status'] = ($input['status'] == 'active') ? PaymentMethodStatus::ACTIVE : PaymentMethodStatus::INACTIVE;
        $input['config']['meta'] = array_map('strip_tags_map', $input['config']['meta']);
        $input['config']['ac'] = array_map('strip_tags_map', $input['config']['ac']);

        try {
            return $this->wrapInTransaction(function ($input) {
                PaymentMethod::updateOrCreate(['slug' => $input['slug']], array_map('strip_tags_map', $input));

                return response()->json([ 'msg' => __('Payment method successfully updated.') ]);
        }, $input);
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            save_error_log($e);
            throw ValidationException::withMessages(['error' => __('Failed to update payment method. Please try again.')]);
        }
    }
}
