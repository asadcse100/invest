@extends('admin.layouts.master')
@section('title', __('Deposit & Withdraw Setting'))

@section('has-content-sidebar', 'has-content-sidebar')

@section('content-sidebar')
    @include('admin.settings.content-sidebar')
@endsection

@section('content')
    <div class="nk-content-body">

        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">{{ __('Deposit & Withdraw') }}</h3>
                    <p>{{ __('Manage your deposit and withdraw options.') }}</p>
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
                    <h5 class="title">{{ __('Deposit Settings') }}</h5>
                    <div class="form-sets gy-3 wide-md">
                        <div class="row g-3 align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Limit Deposit Request') }}</label>
                                    <span class="form-note">{{ __('The maximum pending deposit request at a time.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="form-control-wrap w-150px">
                                        <select class="form-select" name="limit_request">
                                            @for($i=0; $i <=10; $i++)
                                                <option value="{{ $i }}"{{ (sys_settings('deposit_limit_request', 0)==$i) ? ' selected' : '' }}>{{ ($i==0) ? __("No") : sprintf('%02s', $i) }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Cancel Deposit Request') }}</label>
                                    <span class="form-note">{{ __('User allow to cancel deposit request within time.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="form-control-wrap w-150px">
                                        <select class="form-select" name="cancel_timeout">
                                            <option value="yes"{{ (sys_settings('deposit_cancel_timeout', 15)==='yes') ? ' selected' : '' }}>{{ __("Yes") }}</option>
                                            @for($i=0; $i <=12; $i++)
                                            <option value="{{ ($i * 5) }}"{{ (sys_settings('deposit_cancel_timeout', 15)===($i * 5)) ? ' selected' : '' }}>{{ ($i==0) ? __("No") : __(":num Min", ['num' => ($i * 5)]) }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Show Amount in :Currency', ['currency' => base_currency()]) }}</label>
                                    <span class="form-note">{{ __('Show input field for base currency amount.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input class="switch-option-value" type="hidden" name="amount_base" value="{{ sys_settings('deposit_amount_base') ?? 'yes' }}">
                                        <input id="deposit-amount-base" type="checkbox" class="custom-control-input switch-option" 
                                               data-switch="yes"{{ (sys_settings('deposit_amount_base', 'yes') == 'yes') ? ' checked=""' : ''}}>
                                        <label for="deposit-amount-base" class="custom-control-label">{{ __('Enable') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Minimum Deposit') }}</label>
                                    <span class="form-note">{{ __('The minimum amount of deposit per transaction.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <div class="form-text-hint"><span>{{ base_currency() }}</span></div>
                                                <input type="number" class="form-control" name="fiat_minimum" value="{{ sys_settings('deposit_fiat_minimum', '1') }}" min="0">
                                            </div>
                                            <div class="form-note"><strong class="text-base">{{ __('Fiat Currency') }}</strong> {{ __("('0' consider as 0.1)") }}</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <div class="form-text-hint"><span>{{ base_currency() }}</span></div>
                                                <input type="number" class="form-control" name="crypto_minimum" value="{{ sys_settings('deposit_crypto_minimum', '0') }}" min="0">
                                            </div>
                                            <div class="form-note"><strong class="text-base">{{ __('Crypto Currency') }}</strong> {{ __("(same as fiat if '0')") }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Maximum Deposit') }}</label>
                                    <span class="form-note">{{ __('The maximum amount of deposit per transaction.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <div class="form-text-hint"><span>{{ base_currency() }}</span></div>
                                                <input type="number" class="form-control" name="fiat_maximum" value="{{ sys_settings('deposit_fiat_maximum', '1') }}" min="0">
                                            </div>
                                            <div class="form-note"><strong class="text-base">{{ __('Fiat Currency') }}</strong> {{ __("('0' for unlimited)") }}</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <div class="form-text-hint"><span>{{ base_currency() }}</span></div>
                                                <input type="number" class="form-control" name="crypto_maximum" value="{{ sys_settings('deposit_crypto_maximum', '0') }}" min="0">
                                            </div>
                                            <div class="form-note"><strong class="text-base">{{ __('Crypto Currency') }}</strong> {{ __("(same as fiat if '0')") }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Disable New Deposit Request') }}</label>
                                    <span class="form-note">{{ __('Temporarily disable deposit system.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input class="switch-option-value" type="hidden" name="disable_request" value="{{ sys_settings('deposit_disable_request') ?? 'no' }}">
                                        <input id="deposit-disable" type="checkbox" class="custom-control-input switch-option" 
                                               data-switch="yes"{{ (sys_settings('deposit_disable_request', 'no') == 'yes') ? ' checked=""' : ''}}>
                                        <label for="deposit-disable" class="custom-control-label">{{ __('Disable') }}</label>
                                    </div>
                                    <span class="form-note mt-1"><em class="text-danger">{{ __('Users unable to send new deposit request if disable.') }}</em></span>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Display Notice to User') }}</label>
                                    <span class="form-note">{{ __('Add custom message to show on user-end.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" name="disable_title" value="{{ sys_settings('deposit_disable_title', 'Temporarily unavailable!') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <textarea class="form-control textarea-sm" name="disable_notice">{{ sys_settings('deposit_disable_notice') }}</textarea>
                                    </div>
                                    <div class="form-note">
                                        <span>{{ __('This message will display when user going to deposit their funds.') }}</span>
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
                                    <input type="hidden" name="form_prefix" value="deposit">
                                    <input type="hidden" name="form_type" value="deposit-settings">
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

        <div class="nk-block card card-bordered">
            <div class="card-inner">
                <form action="{{ route('admin.save.app.settings') }}" class="form-settings" method="POST">
                    <h5 class="title">{{ __('Withdraw Settings') }}</h5>
                    <div class="form-sets gy-3 wide-md">
                        <div class="row g-3 align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Limit Withdraw Request') }}</label>
                                    <span class="form-note">{{ __('The maximum pending withdraw request at a time.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="form-control-wrap w-150px">
                                        <select class="form-select" name="limit_request">
                                            @for($i=0; $i <=10; $i++)
                                                <option value="{{ $i }}"{{ (sys_settings('withdraw_limit_request', 0)==$i) ? ' selected' : '' }}>{{ ($i==0) ? __("No") : sprintf('%02s', $i) }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Cancel Withdraw Request') }}</label>
                                    <span class="form-note">{{ __('User allow to cancel withdraw request within time.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="form-control-wrap w-150px">
                                        <select class="form-select" name="cancel_timeout">
                                            <option value="yes"{{ (sys_settings('withdraw_cancel_timeout', 15)==='yes') ? ' selected' : '' }}>{{ __("Yes") }}</option>
                                            @for($i=0; $i <=12; $i++)
                                            <option value="{{ ($i * 5) }}"{{ (sys_settings('withdraw_cancel_timeout', 15)===($i * 5)) ? ' selected' : '' }}>{{ ($i==0) ? __("No") : __(":num Min", ['num' => ($i * 5)]) }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Minimum Withdraw') }}</label>
                                    <span class="form-note">{{ __('The minimum amount of withdraw per transaction.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <div class="form-text-hint"><span>{{ base_currency() }}</span></div>
                                                <input type="number" class="form-control" name="fiat_minimum" value="{{ sys_settings('withdraw_fiat_minimum', '1') }}" min="0">
                                            </div>
                                            <div class="form-note"><strong class="text-base">{{ __('Fiat Currency') }}</strong> {{ __("('0' consider as 0.1)") }}</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <div class="form-text-hint"><span>{{ base_currency() }}</span></div>
                                                <input type="number" class="form-control" name="crypto_minimum" value="{{ sys_settings('withdraw_crypto_minimum', '0') }}" min="0">
                                            </div>
                                            <div class="form-note"><strong class="text-base">{{ __('Crypto Currency') }}</strong> {{ __("(same as fiat if '0')") }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Maximum Withdraw') }}</label>
                                    <span class="form-note">{{ __('The maximum amount of withdraw per transaction.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <div class="form-text-hint"><span>{{ base_currency() }}</span></div>
                                                <input type="number" class="form-control" name="fiat_maximum" value="{{ sys_settings('withdraw_fiat_maximum', '1') }}" min="0">
                                            </div>
                                            <div class="form-note"><strong class="text-base">{{ __('Fiat Currency') }}</strong> {{ __("('0' for unlimited)") }}</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <div class="form-text-hint"><span>{{ base_currency() }}</span></div>
                                                <input type="number" class="form-control" name="crypto_maximum" value="{{ sys_settings('withdraw_crypto_maximum', '0') }}" min="0">
                                            </div>
                                            <div class="form-note"><strong class="text-base">{{ __('Crypto Currency') }}</strong> {{ __("(same as fiat if '0')") }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Disable New Withdraw Request') }}</label>
                                    <span class="form-note">{{ __('Temporarily disable withdraw system.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input class="switch-option-value" type="hidden" name="disable_request" value="{{ sys_settings('withdraw_disable_request') ?? 'no' }}">
                                        <input id="withdraw-disable" type="checkbox" class="custom-control-input switch-option" 
                                               data-switch="yes"{{ (sys_settings('withdraw_disable_request', 'no') == 'yes') ? ' checked=""' : ''}}>
                                        <label for="withdraw-disable" class="custom-control-label">{{ __('Disable') }}</label>
                                    </div>
                                    <span class="form-note mt-1"><em class="text-danger">{{ __('Users unable to send new withdraw request if disable.') }}</em></span>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Display Notice to User') }}</label>
                                    <span class="form-note">{{ __('Add custom message to show on user-end.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" name="disable_title" value="{{ sys_settings('withdraw_disable_title', 'Temporarily unavailable!') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <textarea class="form-control textarea-sm" name="disable_notice">{{ sys_settings('withdraw_disable_notice') }}</textarea>
                                    </div>
                                    <div class="form-note">
                                        {{ __('This message will display when user going to withdraw their funds.') }}
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
                                    <input type="hidden" name="form_prefix" value="withdraw">
                                    <input type="hidden" name="form_type" value="withdraw-settings">
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
