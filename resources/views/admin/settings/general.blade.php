@extends('admin.layouts.master')
@section('title', __('General Settings'))

@section('has-content-sidebar', 'has-content-sidebar')

@section('content-sidebar')
    @include('admin.settings.content-sidebar')
@endsection

@php

$regFieldsMap = [
    [
        'label' => __('Phone Number'),
        'name' => 'profile_phone',
        'default' => 'no',
    ],
    [
        'label' => __('Date of Birth'),
        'name' => 'profile_dob',
        'default' => 'no',
    ],
    [
        'label' => __('Country'),
        'name' => 'profile_country',
        'default' => 'no',
    ]
];

@endphp

@section('content')
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">{{ __('General Settings') }}</h3>
                    <p>{{ __('Global settings of the application that you can manage easily.') }}</p>
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
                    <h5 class="title">{{ __('Timezone and Format') }}</h5>
                    <div class="form-sets gy-3 wide-md">
                        <div class="row g-3 align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Time Zone') }}</label>
                                    <span class="form-note">{{ __('Set timezone on application.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="form-control-wrap w-max-250px">
                                        <select name="time_zone" class="form-select">
                                            @foreach (config('investorm.timezones') as $key => $item)
                                                <option value="{{ $key }}"
                                                    {{ sys_settings('time_zone') == $key ? ' selected' : '' }}>
                                                    {{ __($item) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Date Format') }}</label>
                                    <span class="form-note">{{ __('Set date format to display date.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="form-control-wrap w-max-250px">
                                        <select name="date_format" class="form-select">
                                            @foreach (config('investorm.date_formats') as $key => $item)
                                                <option value="{{ $key }}"
                                                    {{ sys_settings('date_format') == $key ? ' selected' : '' }}>
                                                    {{ __($item) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Time Format') }}</label>
                                    <span class="form-note">{{ __('Set time format to display time.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="form-control-wrap w-max-250px">
                                        <select name="time_format" class="form-select">
                                            @foreach (config('investorm.time_formats') as $key => $item)
                                                <option value="{{ $key }}"
                                                    {{ sys_settings('time_format') == $key ? ' selected' : '' }}>
                                                    {{ __($item) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <h5 class="title">{{ __('Decimal Option') }}</h5>
                    <div class="form-sets gy-3 wide-md">
                        <div class="row g-3 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Maximum Decimal') }} <span class="small"> -
                                            {{ __('Application') }}</span></label>
                                    <span
                                        class="form-note">{{ __('Number of decimal maintain in system calculation.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <input type="number" class="form-control" min="2" max="6"
                                                    name="decimal_fiat_calc"
                                                    value="{{ sys_settings('decimal_fiat_calc', '2') }}">
                                            </div>
                                            <div class="form-note">
                                                <strong>{{ __('Fiat Currency') }}</strong>
                                                {{ __('(2 to 6 accepted; default: 2)') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <input type="number" class="form-control" min="4" max="12"
                                                    name="decimal_crypto_calc"
                                                    value="{{ sys_settings('decimal_crypto_calc', '6') }}">
                                            </div>
                                            <div class="form-note">
                                                <strong>{{ __('Crypto Currency') }}</strong>
                                                {{ __('(4 to 12 accepted; default: 6)') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Decimal Display') }} <span class="small"> -
                                            {{ __('Optional / Alternate') }}</span></label>
                                    <span class="form-note">{{ __('Usually use to display account balance.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <input type="number" class="form-control" min="1" max="4"
                                                    name="decimal_fiat_display"
                                                    value="{{ sys_settings('decimal_fiat_display', '2') }}">
                                            </div>
                                            <div class="form-note">
                                                <strong>{{ __('Fiat Currency') }}</strong>
                                                {{ __('(1 to 4 accepted; default: 2)') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <input type="number" class="form-control" min="4" max="8"
                                                    name="decimal_crypto_display"
                                                    value="{{ sys_settings('decimal_crypto_display', '4') }}">
                                            </div>
                                            <div class="form-note">
                                                <strong>{{ __('Crypto Currency') }}</strong>
                                                {{ __('(4 to 8 accepted; default: 4)') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-sets gy-3 wide-md">
                        <div class="row g-3">
                            <div class="col-md-7 offset-lg-5">
                                <div class="form-group mt-2">
                                    @csrf
                                    <input type="hidden" name="form_type" value="general-settings">
                                    <button type="button" class="btn btn-primary submit-settings" disabled="">
                                        <span class="spinner-border spinner-border-sm hide" role="status"
                                            aria-hidden="true"></span>
                                        <span>{{ __('Update') }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="nk-block card card-bordered" id="language-settings">
            <div class="card-inner">
                <h5 class="title">{{ __('Language Settings') }}</h5>
                <form action="{{ route('admin.save.app.settings') }}" class="form-settings" method="POST" autocomplete="off">
                    <div class="form-sets gy-3 wide-md">
                        <div class="row g-3 align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label" for="lang-supported">{{ __('Default Language') }}</label>
                                    <span class="form-note">{{ __('Set default language on application.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="row gy-2">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <select class="form-select" id="default-lang-adminend" name="default_system">
                                                    <option value="0"{{ (gss('language_default_system') == 0) ? ' selected' : '' }}>{{ __('System') }}</option>
                                                    @foreach ($languages as $lang)
                                                        <option value="{{ $lang->code }}"{{ (gss('language_default_system') === $lang->code) ? ' selected' : '' }}{{ ($lang->status == 0) ? ' disabled' : '' }}>
                                                            {{ $lang->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-note mt-1 fw-bold text-secondary">{{ __("Admin Panel") }}</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <select class="form-select" id="default-lang-userpanel" name="default_public">
                                                    <option value="0"{{ (gss('language_default_public') == 0) ? ' selected' : '' }}>{{ __('System') }}</option>
                                                    @foreach ($languages as $lang)
                                                        <option value="{{ $lang->code }}"{{ (gss('language_default_public') === $lang->code) ? ' selected' : '' }}{{ ($lang->status == 0) ? ' disabled' : '' }}>
                                                            {{ $lang->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-note mt-1 fw-bold text-secondary">{{ __("User Panel") }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Language Switcher') }}</label>
                                    <span class="form-note">{{ __('Display a switcher to quick switch language.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="row gy-2">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <select class="form-select" id="language-switcher-label" name="show_as">
                                                    <option value="default"{{ (gss('language_show_as') == 'default') ? ' selected' : '' }}>{{ __("Use System Default") }}</option>
                                                    <option value="label"{{ (gss('language_show_as') == 'label') ? ' selected' : '' }}>{{ __("Use Own Label") }}</option>
                                                    <option value="short"{{ (gss('language_show_as') == 'short') ? ' selected' : '' }}>{{ __("Use Short Name") }}</option>
                                                    <option value="code"{{ (gss('language_show_as') == 'code') ? ' selected' : '' }}>{{ __("Use Code Name") }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="custom-control custom-switch custom-control-labeled">
                                                <input class="switch-option-value" type="hidden" name="switcher" value="{{ sys_settings('language_switcher') ?? 'off' }}">
                                                <input id="lang-switch" type="checkbox" class="custom-control-input switch-option" data-switch="on" {{ sys_settings('language_switcher', 'off') == 'on' ? ' checked=""' : '' }}>
                                                <label for="lang-switch" class="custom-control-label">{{ __('Enable') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-sets gy-3 wide-md">
                        <div class="row g-3">
                            <div class="col-md-7 offset-lg-5">
                                <div class="form-group mt-2">
                                    @csrf
                                    <input type="hidden" name="form_prefix" value="language">
                                    <input type="hidden" name="form_type" value="language-settings">
                                    <button type="button" class="btn btn-primary submit-settings" disabled="">
                                        <span class="spinner-border spinner-border-sm hide" role="status"
                                            aria-hidden="true"></span>
                                        <span>{{ __('Update') }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="nk-block card card-bordered" id="country-settings">
            <div class="card-inner">
                <h5 class="title">{{ __('Country Settings') }}</h5>
                <form action="{{ route('admin.save.app.settings') }}" class="form-settings" method="POST" autocomplete="off">
                    <div class="form-sets gy-3 wide-md">
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
                                        <select class="form-select" id="country-restriction-type" name="country_restriction_type">
                                            <option value="disable"{{ (gss('country_restriction_type') == 'disable') ? ' selected' : '' }}>{{ __("Allow All Countries") }}</option>
                                            <option value="exclude"{{ (gss('country_restriction_type') == 'exclude') ? ' selected' : '' }}>{{ __("Restrict Selected Countries") }}</option>
                                            <option value="include"{{ (gss('country_restriction_type') == 'include') ? ' selected' : '' }}>{{ __("Allow Selected Countries") }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                            <option></option>
                                            @foreach(config('countries') as $code => $country)
                                                <option value="{{ $code }}" @if (sys_settings('countries')) {{ in_array($code, sys_settings('countries', [])) ? ' selected' : '' }} @endif>{{ $country }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-sets gy-3 wide-md">
                        <div class="row g-3">
                            <div class="col-md-7 offset-lg-5">
                                <div class="form-group mt-2">
                                    @csrf
                                    <input type="hidden" name="form_type" value="country-settings">
                                    <button type="button" class="btn btn-primary submit-settings" disabled="">
                                        <span class="spinner-border spinner-border-sm hide" role="status"
                                            aria-hidden="true"></span>
                                        <span>{{ __('Update') }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="nk-block card card-bordered">
            <div class="card-inner">
                <form action="{{ route('admin.save.app.settings') }}" class="form-settings" method="POST">
                    <h5 class="title">{{ __('Registration Option') }}</h5>
                    <div class="form-sets gy-3 wide-md">
                        <div class="row g-3 align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Allow Registration') }}</label>
                                    <span class="form-note">{{ __('Enable or disable registration from site.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <ul class="custom-control-group g-3 align-center flex-wrap">
                                    <li>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" id="reg-enable" value="enable"
                                                name="signup_allow"
                                                {{ sys_settings('signup_allow', 'enable') == 'enable' ? ' checked=""' : '' }}>
                                            <label class="custom-control-label"
                                                for="reg-enable">{{ __('Enable') }}</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" name="signup_allow"
                                                value="disable" id="reg-disable"
                                                {{ sys_settings('signup_allow', 'enable') == 'disable' ? ' checked=""' : '' }}>
                                            <label class="custom-control-label"
                                                for="reg-disable">{{ __('Disable') }}</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row g-3 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
									<label class="form-label">{{ __("Additional Fields for Registration") }}</label>
									<span class="form-note">{{ __("Select whatever you need for user registration.") }}</span>
								</div>
                            </div>
                            <div class="col-md-7">
								<ul class="custom-control-group gx-1 gy-2 flex-wrap flex-column">
									@foreach($regFieldsMap as $field)
									<li class="input-group w-max-350px flex-wrap flex-sm-nowrap justify-between">
										<div class="input-label">{{ $field['label'] }}</div>
										<div class="input-fields">
											<ul class="gx-gs justify-between">
												<li>
													<div class="custom-control custom-control-sm custom-switch">
														<input class="switch-option-value" type="hidden" name="signup_form_fields[{{ $field['name'] }}][show]" value="{{ data_get(sys_settings('signup_form_fields'), $field['name'].'.show') ?? data_get($field, 'default') }}">
                                                        <input type="checkbox" class="custom-control-input switch-option" {{ data_get($field, 'show') }} data-switch="yes"{{ (data_get(sys_settings('signup_form_fields'), $field['name'].'.show', data_get($field, 'default')) == 'yes') ? ' checked' : ''}} id="reg-form-field-{{ $field['name'] }}">
                                                        <label class="custom-control-label" for="reg-form-field-{{ $field['name'] }}"><span class="over"></span><span>{{ __('Show') }}</span></label>
													</div>
												</li>
												<li>
													<div class="custom-control custom-control-sm custom-checkbox">
														<input class="switch-option-value" type="hidden" name="signup_form_fields[{{ $field['name'] }}][req]" value="{{ data_get(sys_settings('signup_form_fields'), $field['name'].'.req') ?? data_get($field, 'default') }}">
                                                        <input type="checkbox" class="custom-control-input switch-option" {{ data_get($field, 'required') }} data-switch="yes"{{ (data_get(sys_settings('signup_form_fields'), $field['name'].'.req', data_get($field, 'default')) == 'yes') ? ' checked' : ''}}  id="reg-form-field-{{ $field['name'] }}-req">
                                                        <label class="custom-control-label" for="reg-form-field-{{ $field['name'] }}-req"><span class="over"></span><span>{{ __('Required') }}</span></label>
													</div>
												</li>
											</ul>
										</div>
									</li>
									@endforeach
								</ul>
							</div>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Verification Required') }}</label>
                                    <span
                                        class="form-note">{{ __('Required email verification after registration.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input class="switch-option-value" type="hidden" name="email_verification"
                                            value="{{ sys_settings('email_verification') ?? 'on' }}">
                                        <input id="email-verify-option" type="checkbox"
                                            class="custom-control-input switch-option" data-switch="on"
                                            {{ sys_settings('email_verification', 'on') == 'on' ? ' checked=""' : '' }}>
                                        <label for="email-verify-option"
                                            class="custom-control-label">{{ __('Enable') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label"
                                        for="enable-referral-system">{{ __('Referral System') }}</label>
                                    <span
                                        class="form-note w-max-350px">{{ __('Users able to invite people using their referral id.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input class="switch-option-value" type="hidden" name="referral_system"
                                            value="{{ sys_settings('referral_system') ?? 'no' }}">
                                        <input id="enable-referral-system" type="checkbox"
                                            class="custom-control-input switch-option" data-switch="yes"
                                            {{ sys_settings('referral_system', 'no') == 'yes' ? ' checked=""' : '' }}>
                                        <label for="enable-referral-system"
                                            class="custom-control-label">{{ __('Enable') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <h5 class="title">{{ __('Maintanance') }}</h5>
                    <div class="form-sets gy-3 wide-md">
                        <div class="row g-3 align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Maintanance Mode') }}</label>
                                    <span class="form-note">{{ __('Enable to make website make offline.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input class="switch-option-value" type="hidden" name="maintenance_mode"
                                            value="{{ sys_settings('maintenance_mode') ?? 'off' }}">
                                        <input id="maintenance-option" type="checkbox"
                                            class="custom-control-input switch-option" data-switch="on"
                                            {{ sys_settings('maintenance_mode', 'off') == 'on' ? ' checked=""' : '' }}>
                                        <label for="maintenance-option"
                                            class="custom-control-label">{{ __('Offline') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Maintenance Notice') }}</label>
                                    <span class="form-note">{{ __('Specify the email address of your website.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <textarea class="form-control"
                                            name="maintenance_notice">{{ sys_settings('maintenance_notice') }}</textarea>
                                    </div>
                                    <div class="form-note">
                                        <span>{{ __('Admin Login on maintenance mode:') }} <strong
                                                class="text-primary">{{ url('/admin/login') }}</strong></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-sets gy-3 wide-md">
                        <div class="row g-3">
                            <div class="col-md-7 offset-lg-5">
                                <div class="form-group mt-2">
                                    @csrf
                                    <input type="hidden" name="form_type" value="application-settings">
                                    <button type="button" class="btn btn-primary submit-settings" disabled="">
                                        <span class="spinner-border spinner-border-sm hide" role="status"
                                            aria-hidden="true"></span>
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
