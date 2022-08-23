<?php

namespace NioModules\CryptoWallet\Controllers;

use NioModules\CryptoWallet\CryptoWalletModule;
use App\Enums\PaymentMethodStatus;
use App\Models\PaymentMethod;
use App\Rules\Gtmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class WalletSettingsController extends Controller
{

    /**
     * @return array
     * @version 1.0.0
     * @since 1.0
     */
    public function support_currencies()
    {
        $config = data_get(config('modules.'.CryptoWalletModule::SLUG), 'supported_currency', []);
        $custom = array_column(gss('custom_currency', []), 'code');
        $merged = array_merge($config, $custom);

        if (module_exist('ExtCurrency', 'addon')) {
            $extcur = app()->get('extcurrency')->currencies('crypto');
            if (!blank($extcur)) {
                $merged = array_merge($merged, $extcur);
            }
        }

        $currencies = array_filter(get_currency_details(), function ($key) use ($merged) {
            return ( in_array($key, $merged) );
        }, ARRAY_FILTER_USE_KEY);

        return $currencies;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @version 1.0.0
     * @since 1.0
     */
    public function settingsView()
    {
        $config = config('modules.'.CryptoWalletModule::SLUG);
        $currencies = $this->support_currencies();
        $fiat_currencies = get_currencies('list', 'fiat');
        $networks = CryptoWalletModule::networks();

        $settings = PaymentMethod::where('slug', CryptoWalletModule::SLUG)->first();
        return view("CryptoWallet::settings", compact('config', 'settings', 'currencies', 'fiat_currencies', 'networks'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @version 1.1.0
     * @since 1.0
     */
    public function saveWalletSettings(Request $request)
    {
        if(empty($request->slug) || $request->slug !== CryptoWalletModule::SLUG) {
            return response()->json([ 'type' => 'error', 'msg' => __('Sorry, something wrong with payment method.') ]);
        }

        $currencies = $this->support_currencies();

        $input = $request->validate([
            'slug' => 'required',
            'name' => 'required|string|max:190',
            'desc' => 'required|string|max:190',
            'currencies' => 'required|array|min:1',
            'status' => 'nullable',
            'min_amount' => 'bail|required|numeric|min:0|regex:/^\d+(\.\d{1,5})?$/',
            'max_amount' => ['bail', 'required', 'numeric', 'min:0', new Gtmin($request->min_amount), 'regex:/^\d+(\.\d{1,5})?$/'],
            'config' => 'array',
            'config.wallet.*.note' => 'nullable|string',
            'config.wallet.*.min' => 'bail|required|numeric|min:0|regex:/^\d+(\.\d{1,5})?$/',
            'config.wallet.*.max' => 'bail|required|numeric|min:0|regex:/^\d+(\.\d{1,5})?$/'
        ], [
            'slug.required' => __('Sorry, your payment method is invalid.'),
            'name.required' => __('Payment method title is required.'),
            'desc.required' => __('Payment method short description is required.'),
            'currencies.*' => __('Select at-least one wallet (currency) from supported wallet.'),

            'min_amount.required' => __(':Label is required.', ['label' => __("Minimum Deposit")]),
            'min_amount.numeric' => __(':Label must be numeric.', ['label' => __("Minimum Deposit")]),
            'min_amount.min' => __(':Label must be at least :num.', ['label' => __("Minimum Deposit"), 'num' => '0']),
            'min_amount.regex' => __('Allow only :num digit after decimal point in :label.', ['label' => __("Minimum Deposit"), 'num' => '5']),

            'max_amount.required' => __(':Label is required.', ['label' => __("Maximum Deposit")]),
            'max_amount.numeric' => __(':Label must be numeric.', ['label' => __("Maximum Deposit")]),
            'max_amount.min' => __(':Label must be at least :num.', ['label' => __("Maximum Deposit"), 'num' => '0']),
            'max_amount.regex' => __('Allow only :num digit after decimal point in :label.', ['label' => __("Maximum Deposit"), 'num' => '5']),

            'config.wallet.*.min.required' => __(':Label is required.', ['label' => __("Minimum Amount")]),
            'config.wallet.*.min.numeric' => __(':Label must be numeric.', ['label' => __("Minimum Amount")]),
            'config.wallet.*.min.min' => __(':Label must be at least :num.', ['label' => __("Minimum Amount"), 'num' => '0']),
            'config.wallet.*.min.regex' => __('Allow only :num digit after decimal point in :label.', ['label' => __("Minimum Amount"), 'num' => '5']),

            'config.wallet.*.max.required' => __(':Label is required.', ['label' => __("Maximum Amount")]),
            'config.wallet.*.max.numeric' => __(':Label must be numeric.', ['label' => __("Maximum Amount")]),
            'config.wallet.*.max.min' => __(':Label must be at least :num.', ['label' => __("Maximum Amount"), 'num' => '0']),
            'config.wallet.*.max.regex' => __('Allow only :num digit after decimal point in :label.', ['label' => __("Maximum Amount"), 'num' => '5']),
        ]);

        foreach ($currencies as $currency) {
            if(in_array($currency['code'], $input['currencies'])) {
                if(empty($input['config']['wallet'][$currency['code']]['address'])){
                    throw ValidationException::withMessages(['config.wallet.'.$currency['code'].'.address' => __('The address is required as you have enable :currency wallet.', ['currency' => $currency['code']]) ]);
                }

                if(($input['config']['wallet'][$currency['code']]['max'] != 0) && ($input['config']['wallet'][$currency['code']]['max'] <= $input['config']['wallet'][$currency['code']]['min'])){
                    throw ValidationException::withMessages(['config.wallet.'.$currency['code'].'.max' => __('The maximum amount must be greater than minimum amount for :currency', ['currency' => $currency['code']])]);
                }
                $input['config']['wallet'][$currency['code']] = array_map('strip_tags_map', $input['config']['wallet'][$currency['code']]);
            }
        }

        $input['countries'] = array();
        $input['status'] = ($input['status'] == 'active') ? PaymentMethodStatus::ACTIVE : PaymentMethodStatus::INACTIVE;
        $input['config']['meta'] = array_map('strip_tags_map', $input['config']['meta']);

        if(empty($input['currencies'])) {
            return response()->json([ 'type' => 'warning', 'msg' => __('Select at-least one wallet (currency) from supported wallet.') ]);
        }

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
