@extends('admin.layouts.modules')
@section('title', __('Bank Transfer - Withdraw Method'))

@php
$isExtend = module_exist('NioExtend', 'addon');
$formFieldMap = [
    [
        'label' => __('Account Type'),
        'name' => 'acc_type',
        'show' => 'disabled',
        'required' => 'disabled',
        'default' => 'yes',
    ],
    [
        'label' => __('Account Holder Name'),
        'name' => 'acc_name',
        'show' => 'disabled',
        'default' => 'yes',
    ],
    [
        'label' => __('Account Number'),
        'name' => 'acc_no',
        'show' => 'disabled',
        'required' => 'disabled',
        'default' => 'yes',
    ],
    [
        'label' => __('Name of Bank'),
        'name' => 'bank_name',
        'show' => 'disabled',
        'required' => 'disabled',
        'default' => 'yes',
    ],
    [
        'label' => __('Branch Name'),
        'name' => 'bank_branch',
        'default' => 'yes',
    ],
    [
        'label' => __('Bank Address'),
        'name' => 'bank_address',
        'default' => 'no',
    ],
    [
        'label' => __('Bank Currency'),
        'name' => 'currency',
        'show' => 'disabled',
        'required' => 'disabled',
        'default' => 'yes',
    ],
    [
        'label' => __('Bank Country'),
        'name' => 'country',
        'default' => 'yes',
    ],
    [
        'label' => __('Sort code'),
        'name' => 'sortcode',
        'default' => 'no',
    ],
    [
        'label' => __('IBAN Number'),
        'name' => 'iban',
        'default' => 'no',
    ],
    [
        'label' => __('Routing Number'),
        'name' => 'routing',
        'default' => 'no',
    ],
    [
        'label' => __('Swift / BIC'),
        'name' => 'swift',
        'default' => 'yes',
    ]
];
@endphp

@section('content')
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">{{ __('Withdraw Methods') }}</h3>
                    <p>{{ __('Manage withdraw methods for user.') }}</p>
                </div>
                <div class="nk-block-head-content">
                    <ul class="nk-block-tools gx-1">
                        <li class="d-lg-none">
                            <a href="javascript:void(0)" class="btn btn-icon btn-trigger toggle" data-target="pageSidebar"><em class="icon ni ni-menu-right"></em></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="nk-block card card-bordered nk-block-mh">
            <div class="card-inner">
                <div class="justify-between">
                    <h5 class="title">{{ __('Bank Transfer - Withdraw') }} <span class="meta ml-1"><span class="badge badge-pill badge-xs badge-light">{{ __('Core') }}</span></span></h5>
                    <div class="go-back"><a class="back-to" href="{{ route('admin.settings.gateway.withdraw.list') }}"><em class="icon ni ni-arrow-left"> </em> {{ __('Back') }}</a></div>
                </div>
                <p>{{ __('Get the bank details from user for withdrawal funds.') }}</p>
                <div class="divider"></div>
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('admin.settings.gateway.withdraw.wd-bank-transfer.save') }}" class="form-settings" method="POST" autocomplete="off">
                            <div class="form-set wide-md">
                                <h6 class="title mb-3">{{ __('Method Setting') }}</h6>
                                <div class="row gy-3">
                                    <div class="col-12 col-xxl-6">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('Method Title') }}</label>
                                            <div class="form-control-wrap">
                                                <input type="text" name="name" class="form-control" value="{{ data_get($settings, 'name', __('Wire Transfer')) }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-xxl-6">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('Description') }}</label>
                                            <div class="form-control-group">
                                                <input type="text" name="desc" value="{{ data_get($settings, 'desc', __('Withdraw your funds directly on your bank.')) }}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('Method Name') }} <span class="small">{{ __('Alternet') }}</span></label>
                                            <div class="form-control-wrap">
                                                <input type="text" name="config[meta][title]" class="form-control" value="{{ data_get($settings, 'config.meta.title') }}">
                                            </div>
                                            <div class="form-note">{{ __('Method title will use if leave blank. Use as short name in transaction record.') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label" for="default-currency">{{ __('Default Currency') }}</label>
                                            <div class="form-control-wrap w-max-250px">
                                                <select name="config[meta][currency]" class="form-select" id="default-currency">
                                                    @foreach($currencies as $currency)
                                                        <option value="{{ data_get($currency, 'code') }}"{{ (data_get($currency, 'code')==data_get($settings, 'config.meta.currency')) ? ' selected' : '' }}{{ !is_active_currency(data_get($currency, 'code')) ? ' disabled' : '' }}>
                                                            {{ __(':name (:code)', ["name" => data_get($currency, 'name'), "code" => data_get($currency, 'code')]) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-note">{{ __('Default currency will be selected by default when user add account for withdraw.') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="divider"></div>
                            <div class="form-set wide-md">
                                <div class="row gy-3">
                                    <div class="col-sm-12 col-xxl-6">
                                        <div class="form-group">
                                            <div class="row gx-gs gy-2">
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label class="form-label">{{ __('Minimum Amount') }}</span><small><sup> 1</sup></small></label>
                                                        <div class="form-control-wrap">
                                                            <div class="form-text-hint"><span>{{ base_currency() }}</span></div>
                                                            <input type="number" class="form-control" name="min_amount" value="{{ data_get($settings, 'min_amount', '0') }}" min="0">
                                                        </div>
                                                        <div class="form-note">{{ __('Amount will be convert') }}</div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label class="form-label">{{ __('Maximum Amount') }}</span><small><sup> 1</sup></small></label>
                                                        <div class="form-control-wrap">
                                                            <div class="form-text-hint"><span>{{ base_currency() }}</span></div>
                                                            <input type="number" class="form-control" name="max_amount" value="{{ data_get($settings, 'max_amount', '0') }}" min="0">
                                                        </div>
                                                        <div class="form-note">{{ __('Amount will be convert') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-xxl-6">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('Fixed Amount') }} <small><sup>2</sup></small></label>
                                            <div class="row gx-gs gy-2">
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-control-wrap">
                                                        <input type="number" class="form-control" name="config[meta][min]" value="{{ data_get($settings, 'config.meta.min', '0') }}" min="0">
                                                    </div>
                                                    <div class="form-note mt-1">{{ __('Minimum Amount') }}</div>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-control-wrap">
                                                        <input type="number" class="form-control" name="config[meta][max]" value="{{ data_get($settings, 'config.meta.max', '0') }}" min="0">
                                                    </div>
                                                    <div class="form-note mt-1">{{ __('Maximum Amount') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($isExtend || is_demo())
                                    <div class="col-12">
                                        @if($isExtend)
                                        <div class="form-group">
                                            <label class="form-label">{{ __('Enable :Type Fees', ['type' => __("Withdraw")]) }}</label>
                                            <div class="form-control-wrap">
                                                <div class="custom-control custom-switch custom-control-labeled">
                                                    <input class="switch-option-value" type="hidden" name="fees[service]" value="{{ data_get($settings, 'fees.service') ?? 'no' }}">
                                                    <input type="checkbox" class="custom-control-input switch-option" data-switch="yes"{{ (data_get($settings, 'fees.service', 'no') == 'yes') ? ' checked=""' : ''}} id="fee-service">
                                                    <label class="custom-control-label" for="fee-service"><span>{{ __('Enable') }}</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="form-group"{!! is_demo() ? ' title="The fees only works with NioExtend Addons and required Extended License." data-toggle="tooltip"' : '' !!}>
                                            <label class="form-label" for="gateway-fee">{{ __(':Type Fees', ['type' => __("Withdraw")]) }} <span>({{ __('per transaction') }})</span> <small><sup>3</sup></small></label>
                                            <div class="row gx-gs gy-3">
                                                <div class="col-12 col-sm-6">
                                                    <div class="row gx-gs gy-2">
                                                        <div class="col-6">
                                                            <div class="form-control-wrap">
                                                                <input type="number" name="fees[percent]" value="{{ data_get($settings, 'fees.percent', 0) }}" class="form-control" id="gateway-fee-percent" placeholder="0" min="0"{{ ($isExtend == false || has_restriction()) ? ' disabled' : '' }}>
                                                            </div>
                                                            <div class="form-note">{{ __('Percent Fee') }}</div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-control-wrap">
                                                                <input type="number" name="fees[flat]" value="{{ data_get($settings, 'fees.flat', 0) }}" class="form-control" id="gateway-fee-flat" placeholder="0" min="0"{{ ($isExtend == false || has_restriction()) ? ' disabled' : '' }}>
                                                            </div>
                                                            <div class="form-note">{{ __('Flat Fee') }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <div class="row gx-gs gy-2">
                                                        <div class="col-6">
                                                            <div class="form-control-wrap">
                                                                <input type="number" name="fees[min]" value="{{ data_get($settings, 'fees.min', 0) }}" class="form-control" id="gateway-fee-fixed-percent" placeholder="0" min="0"{{ ($isExtend == false || has_restriction()) ? ' disabled' : '' }}>
                                                            </div>
                                                            <div class="form-note">{{ __("Minimum Fee") }} <em class="ni ni-info nk-tooltip" title="{{ __("Fee will add if percent calculated amount less than compare amount.") }}"></em></div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-control-wrap">
                                                                <input type="number" name="fees[compare]" value="{{ data_get($settings, 'fees.compare', 0.01) }}" class="form-control" id="gateway-fee-fixed-percent" placeholder="0.01" min="0.01"{{ ($isExtend == false || has_restriction()) ? ' disabled' : '' }}>
                                                            </div>
                                                            <div class="form-note">{{ __("Compare") }} <em class="ni ni-info nk-tooltip" title="{{ __("If percent calculated amount less than defined amount.") }}"></em></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    @endif

                                    <div class="col-12">
                                        <div class="note pl-2 border-left border-primary mt-3">
                                            <p><strong>{{ __('Please Note:') }}</strong><br>
                                                <small><sup>1</sup></small>
                                                {{ __("The amount will apply only if its more than the base minimum / maximum withdraw amount.") }}<br>
                                                <small><sup>2</sup></small> 
                                                {{ __("The fixed minimum / maximum amount will be set same for each currency & override others.") }}<br>
                                                @if ($isExtend)
                                                <small><sup>3</sup></small>
                                                {{ __("Fee will apply on withdraw currency and same for all currencies. Both percent & flat fee will applied if present.") }}<br>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="divider"></div>
                            <div class="form-set">
                                <h6 class="title">{{ __('Supported Currency') }}</h6>
                                <p>{{ __('Specify currency wise minimum / maximum amount for :type.', ['type' => __("Withdraw")]) }}</p>
                                <div class="row g-3">
                                    @foreach($currencies as $currency)
                                    <div class="col-sm-6 col-xxl-4">
                                        <div class="card bg-lighter p-3">
                                            <div class="form-group">
                                                <div class="form-control-wrap">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input" name="currencies[]" value="{{ data_get($currency, 'code') }}" id="cur-{{ data_get($currency, 'code') }}" {{ !is_active_currency(data_get($currency, 'code')) ? ' disabled' : '' }} @if(in_array(data_get($currency, 'code'), data_get($settings, 'currencies', []))) checked @endif>
                                                        <label class="custom-control-label" for="cur-{{ data_get($currency, 'code') }}">{{ __(':name (:code)', ["name" => data_get($currency, 'name'), "code" => data_get($currency, 'code')]) }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="">{{ __('Amount to Withdraw') }}</label>
                                                <div class="row gx-1">
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <div class="form-control-wrap">
                                                                <input type="number" class="form-control form-control-sm" name="config[currencies][{{ data_get($currency, 'code') }}][min]" value="{{ data_get($settings, 'config.currencies.' . data_get($currency, 'code') . '.min', 0) }}" placeholder="0" min="0">
                                                            </div>
                                                            <div class="form-note">{{ __('Minimum') }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <div class="form-control-wrap">
                                                                <input type="number" class="form-control form-control-sm" name="config[currencies][{{ data_get($currency, 'code') }}][max]" value="{{ data_get($settings, 'config.currencies.' . data_get($currency, 'code') . '.max', 0) }}" placeholder="0" min="0">
                                                            </div>
                                                            <div class="form-note">{{ __('Maximum') }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($isExtend || is_demo())
                                            <div class="form-group mt-n2"{!! is_demo() ? ' title="The fees only works with NioExtend Addons and required Extended License." data-toggle="tooltip"' : '' !!}>
                                                <label class="form-label" for="gateway-fee">{{ __(':Type Fees', ['type' => __("Withdraw")]) }}</label>
                                                <div class="row gx-1">
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <div class="form-control-wrap">
                                                                <input type="number" class="form-control form-control-sm" name="fees[currencies][{{ data_get($currency, 'code') }}][percent]" value="{{ data_get($settings, 'fees.currencies.' . data_get($currency, 'code') . '.percent', 0) }}" placeholder="0" min="0"{{ ($isExtend == false || has_restriction()) ? ' disabled' : '' }}>
                                                            </div>
                                                            <div class="form-note">{{ __('Percent Fee') }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <div class="form-control-wrap">
                                                                <input type="number" class="form-control form-control-sm" name="fees[currencies][{{ data_get($currency, 'code') }}][flat]" value="{{ data_get($settings, 'fees.currencies.' . data_get($currency, 'code') . '.flat', 0) }}" placeholder="0" min="0"{{ ($isExtend == false || has_restriction()) ? ' disabled' : '' }}>
                                                            </div>
                                                            <div class="form-note">{{ __('Flat Fee') }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="divider"></div>
                            <div class="form-set wide-md">
                                <div class="row gy-3">
                                    <div class="col-12">
                                        <div class="row g-3 align-center">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label class="form-label">{{ __('Country Restriction') }}</label>
                                                    <span class="form-note">{{ __('Allow or disallowed the countries into application.') }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="form-group">
                                                    <div class="form-control-wrap w-max-250px">
                                                        <select class="form-select" id="country-restriction-type" name="config[country_restriction_type]">
                                                            <option value="disable"{{ data_get($settings, 'config.country_restriction_type') == 'disable' ? ' selected' : '' }}>{{ __("Allow All Countries") }}</option>
                                                            <option value="global"{{ data_get($settings, 'config.country_restriction_type') == 'global' ? ' selected' : '' }}>{{ __("Same As Global") }}</option>
                                                            <option value="exclude"{{ data_get($settings, 'config.country_restriction_type') == 'exclude' ? ' selected' : '' }}>{{ __("Restrict Selected Countries") }}</option>
                                                            <option value="include"{{ data_get($settings, 'config.country_restriction_type') == 'include' ? ' selected' : '' }}>{{ __("Allow Selected Countries") }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="row g-3 align-top">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label class="form-label">{{ __('Choose Countries') }}</label>
                                                    <span class="form-note">{{ __('Specify the country do you want to display or hide from the list.') }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="form-group">
                                                    <div class="form-control-wrap">
                                                        <select name="countries[]" class="form-select" multiple="" data-placeholder="{{ __("Choose one or more countries") }}">
                                                            @foreach(config('countries') as $code => $country)
                                                                <option value="{{ $code }}" {{ in_array($code, data_get($settings, 'countries', [])) ? ' selected' : '' }}>{{ $country }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="divider"></div>
                            <div class="form-set wide-sm">
                                <h6 class="title mb-3">{{ __('Display Form Fields') }}</h6>
                                <div class="row gy-2">
                                    @foreach($formFieldMap as $field)
                                    <div class="col-12">
                                        <div class="row align-center">
                                            <div class="col-12 col-sm-6">
                                                <p class="title">{{ $field['label'] }}</p>
                                            </div>
                                            <div class="col-6 col-sm-3">
                                                <div class="custom-control custom-control-sm custom-switch">
                                                    <input class="switch-option-value" type="hidden" name="form-fields[{{ $field['name'] }}][show]" value="{{ data_get($settings, 'config.form.'.$field['name'].'.show') ?? data_get($field, 'default') }}">
                                                    <input type="checkbox" class="custom-control-input switch-option" {{ data_get($field, 'show') }} data-switch="yes"{{ (data_get($settings, 'config.form.'.$field['name'].'.show', data_get($field, 'default')) == 'yes') ? ' checked' : ''}} id="bank-{{ $field['name'] }}">
                                                    <label class="custom-control-label" for="bank-{{ $field['name'] }}"><span class="over"></span><span>{{ __('Show') }}</span></label>
                                                </div>
                                            </div>
                                            <div class="col-6 col-sm-3">
                                                <div class="custom-control custom-control-sm custom-checkbox">
                                                    <input class="switch-option-value" type="hidden" name="form-fields[{{ $field['name'] }}][req]" value="{{ data_get($settings, 'config.form.'.$field['name'].'.req') ?? data_get($field, 'default') }}">
                                                    <input type="checkbox" class="custom-control-input switch-option" {{ data_get($field, 'required') }} data-switch="yes"{{ (data_get($settings, 'config.form.'.$field['name'].'.req', data_get($field, 'default')) == 'yes') ? ' checked' : ''}}  id="bank-{{ $field['name'] }}-req">
                                                    <label class="custom-control-label" for="bank-{{ $field['name'] }}-req"><span class="over"></span><span>{{ __('Required') }}</span></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    <div class="col-12">
                                        <div class="notes mt-4">
                                            <ul>
                                                <li class="alert-note is-plain text-danger">
                                                    <em class="icon ni ni-alert-circle"></em>
                                                    <p>{{ __("Changes any fields does not affect on existing account as it only applicable for new account.") }}</p>
                                                </li>
                                                <li class="alert-note is-plain">
                                                    <em class="icon ni ni-info"></em>
                                                    <p>{{ __('These form fields will show to user when they are adding an account for withdraw.') }}</p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="divider"></div>
                            <div class="d-flex justify-between">
                                @csrf
                                <input type="hidden" name="slug" value="{{ data_get($config, 'slug') }}">
                                <div class="custom-control custom-switch">
                                    <input class="switch-option-value" type="hidden" name="status" value="{{ data_get($settings, 'status') ?? 'inactive' }}">
                                    <input type="checkbox" class="custom-control-input switch-option" data-switch="active"{{ (data_get($settings, 'status', 'inactive') == 'active') ? ' checked' : ''}}  id="enable-method">
                                    <label class="custom-control-label" for="enable-method"><span class="over"></span><span>{{ __('Enable Method') }}</span></label>
                                </div>
                                <button type="button" class="btn btn-primary submit-settings" disabled="">
                                    <span class="spinner-border spinner-border-sm hide" role="status" aria-hidden="true"></span>
                                    <span>{{ __('Update') }}</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection