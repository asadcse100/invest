@extends('admin.layouts.master')
@section('title', __('Manage Currencies'))

@section('has-content-sidebar', 'has-content-sidebar')

@section('content-sidebar')
    @include('admin.settings.content-sidebar')
@endsection

@php

$supported_currency = array_keys(sys_settings('supported_currency', '{}'));
$automatic_ex_rates = $exchange_rates[$exchange_methods->AUTOMATIC];
$manual_ex_rates = $exchange_rates[$exchange_methods->MANUAL];

@endphp

@section('content')
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">{{ __('Manage Currencies') }}</h3>
                    <p>{{ __('You can manage currency what you want to use in application.') }}</p>
                </div>
                <div class="nk-block-head-content">
                    <ul class="nk-block-tools gx-1">
                        <li class="d-lg-none">
                            <a href="#" class="btn btn-icon btn-trigger toggle" data-target="pageSidebar"><em class="icon ni ni-menu-right"></em></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="nk-block card card-bordered">
            <div class="card-inner">
                <form action="{{ route('admin.save.app.settings') }}" class="form-settings" method="POST">
                    <h5 class="title">{{ __('Currencies') }}</h5>
                    <div class="form-sets gy-3 wide-md">
                        <div class="row g-3 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label" for="currency-based">{{ __('Base Currency') }}</label>
                                    <span class="form-note">{{ __('Set the main / base currency on system.') }} <br>{{ __('It also applied on personal account management.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="form-control-wrap w-max-250px">
                                        <select class="form-select" name="base_currency" id="currency-based">
                                            @foreach($currencies as $code => $name)
                                                <option value="{{ $code }}"{{ ($code==sys_settings('base_currency')) ? ' selected' : '' }}>{{ $name.' ('.$code.')' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-note">{{ __('System Default Currency') }}</div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label" for="currency-alter">{{ __('User Secondary Currency') }}</label>
                                    <span class="form-note">{{ __('Set secondary currency to display alternal balance.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="form-control-wrap w-max-250px">
                                        <select class="form-select" name="alter_currency" id="currency-alter">
                                            @foreach($currenciesAll as $code => $name)
                                                <option value="{{ $code }}"{{ ($code == sys_settings('alter_currency')) ? ' selected' : '' }}>{{ $name.' ('.$code.')' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-note">{{ __('Alternet Display Currency') }}</div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <span class="text-danger">
                                    <strong>{{ __('Important Note:') }}</strong><br>
                                    {{ __('If change the Base Currency after any transaction made, it will occurred in whole calculation.') }}
                                    <br>
                                    {{ __('Must update exchange rate accordingly after currency change.') }}
                                </span>
                            </div>
                        </div>
                        <div class="row g-3 align-top">
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <label class="form-label"
                                           for="currency-supported">{{ __('Supported Currency') }}</label>
                                    <span class="form-note">
                                        {{ __('Enable or disable one or multiple currencies') }} <br>{{ __('on systems from available list.') }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <ul class="custom-control-group g-2 align-center flex-wrap li-col3x">
                                    @foreach($currenciesAll as $code => $name)
                                        <li>
                                            <div class="custom-control custom-control-sm custom-checkbox">
                                                <input type="checkbox" class="custom-control-input supported-cur-cb" name="supported_currency[{{ $code }}]"
                                                       id="supported-cur-{{ strtolower(str_char($code)) }}"{{ (in_array($code, $supported_currency)) ? ' checked' : '' }}>
                                                <label class="custom-control-label" for="supported-cur-{{ strtolower(str_char($code)) }}">{{ $name.' ('.$code.')' }}</label>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <h5 class="title">{{ __('Currency Exchange') }}</h5>
                    <div class="form-sets wide-md">
                        <div class="row g-3 align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label" for="exchange-method">{{ __('Exchange Rate') }}</label>
                                    <span class="form-note">{{ __('Set how exchange rate calculate.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="form-control-wrap w-max-250px">
                                        <select name="exchange_method" class="form-select" id="exchange-method">
                                            @foreach($exchange_methods as $key => $name)
                                                <option value="{{ $name }}"{{ (sys_settings('exchange_method') == $name) ? ' selected' : '' }}>{{ ucfirst($name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="exchange-rates g-3" id="exchange-method-manual"{!! (sys_settings('exchange_method', $exchange_methods->AUTOMATIC) == $exchange_methods->AUTOMATIC) ? ' style="display:none"' : '' !!}>
                            <div class="row g-3 align-start">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="form-label" for="exchange-update">{{ __('Manual Exchange Rate') }}</label>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="row gy-2">
                                        @foreach($currencies as $code => $name)
                                            @if(!in_array($code, $supported_currency))
                                                @continue
                                            @endif
                                            <div class="col-md-4 col-sm-6">
                                                <div class="form-group">
                                                    <div class="form-control-wrap">
                                                        <span class="form-text-hint"><span>{{ $code }}</span></span>
                                                        <input name="manual_exchange_rate[{{ $code }}]" type="text" class="form-control" value="{{ to_num(data_get($manual_ex_rates, $code, 1)) }}">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="exchange-rates g-3" id="exchange-method-auto"{!! (sys_settings('exchange_method', $exchange_methods->AUTOMATIC) == $exchange_methods->MANUAL) ? ' style="display:none"' : '' !!}>
                            <div class="row g-3 align-center">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="form-label" for="exchange-auto-update">{{ __('Update Rate Automatically') }}</label>
                                        <span class="form-note">{{ __('Get exchange rate automatially after selected time.') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <div class="form-control-wrap w-max-250px">
                                            <select name="exchange_auto_update" class="form-select" id="exchange-auto-update">
                                                @foreach([20, 30, 45, 60, 120] as $key)
                                                    <option value="{{ $key }}"{{ (sys_settings('exchange_auto_update', '30')==$key) ? ' selected' : '' }}>{{ __(':timeout Minutes', ['timeout' => $key]) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3 align-start">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="form-label" for="exchange-auto-rate">{{ __('Automatic Exchange Rate') }}</label>
                                        <span class="form-note">{{  __('(Read only)') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="row gy-2">
                                        @foreach($currencies as $code => $name)
                                            @if(!in_array($code, $supported_currency))
                                                @continue
                                            @endif
                                            <div class="col-md-4 col-sm-6">
                                                <div class="form-group">
                                                    <div class="form-control-wrap">
                                                        <span class="form-text-hint"><span>{{ $code }}</span></span>
                                                        <input name="" type="text" class="form-control" value="{{ to_num(data_get($automatic_ex_rates, $code, 1)) }}" readonly="readonly">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="form-note mt-1">
                                        <strong>{{ __('Last Update:') }}</strong> {{ sys_settings('exchange_last_update') ? show_date(sys_settings('exchange_last_update'), true) : __('Waiting for update.') }}
                                        @if (sys_settings('exratesapi_error_msg'))
                                            / <span class="text-danger">{{ __("Last Failed Note: :msg", ['msg' => sys_settings('exratesapi_error_msg')]) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Rounded conversion amount') }}</label>
                                    <span class="form-note">{{ __('Round the amount while currency conversion.') }}<br>{{ __("Up as 1.55 => 2 | Down as 1.55 => 1") }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-6 col-md-5">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <select class="form-select" name="fiat_rounded">
                                                    <option value="none"{{ (sys_settings('fiat_rounded', 'up')=='none') ? ' selected' : '' }}>{{ __('None') }}</option>
                                                    <option value="up"{{ (sys_settings('fiat_rounded', 'up')=='up') ? ' selected' : '' }}>{{ __('Up') }}</option>
                                                    <option value="down"{{ (sys_settings('fiat_rounded', 'up')=='down') ? ' selected' : '' }}>{{ __('Down') }}</option>
                                                </select>
                                            </div>
                                            <div class="form-note"><strong class="text-base">{{ __('Fiat Currency') }}</strong></div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-5">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <select class="form-select" name="crypto_rounded">
                                                    <option value="none"{{ (sys_settings('crypto_rounded', 'none')=='none') ? ' selected' : '' }}>{{ __('None') }}</option>
                                                    <option value="up"{{ (sys_settings('crypto_rounded', 'none')=='up') ? ' selected' : '' }}>{{ __('Up') }}</option>
                                                    <option value="down"{{ (sys_settings('crypto_rounded', 'none')=='down') ? ' selected' : '' }}>{{ __('Down') }}</option>
                                                </select>
                                            </div>
                                            <div class="form-note"><strong class="text-base">{{ __('Crypto Currency') }}</strong></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-sets gy-3 wide-md">
                        <div class="row g-3">
                            <div class="col-lg-7 offset-lg-5">
                                <div class="form-group mt-2">
                                    @csrf
                                    <input type="hidden" name="form_type" value="currencies-settings">
                                    <button type="button" class="btn btn-primary submit-settings" disabled="">
                                        <span class="spinner-border spinner-border-sm hide" role="status" aria-hidden="true"></span>
                                        <span>{{ __('Update') }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
