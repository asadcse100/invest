@extends('admin.layouts.modules')
@section('title', __('Investment Settings'))

@php
$base_currency = base_currency();
$alter_currency = secondary_currency();
$all_days = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
@endphp

@section('content')
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">{{ __('Investment Settings') }}</h3>
                    <p>{{ __('Manage your investment settings of the application.') }}</p>
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
                <form action="{{ route('admin.settings.investment.save') }}" class="form-settings" method="POST">
                    <h5 class="title">{{ __('Plan Purchase Option') }}</h5>
                    <div class="form-sets gy-3 wide-md">
                        <div class="row g-3 align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label" for="plan-ordered">{{ __('Show Plan/Scheme by Order') }}</label>
                                    <span class="form-note">{{ __('Set the order to display plan/scheme for investment.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="form-control-wrap w-max-225px">
                                        <select name="plan_order" class="form-select" id="plan-ordered">
                                            <option value="default"{{ (sys_settings('iv_plan_order') == 'default') ? ' selected' : '' }}>{{ __('Default') }}</option>
                                            <option value="reverse"{{ (sys_settings('iv_plan_order') == 'reverse') ? ' selected' : '' }}>{{ __('Reverse') }}</option>
                                            <option value="random"{{ (sys_settings('iv_plan_order') == 'random') ? ' selected' : '' }}>{{ __('Random') }}</option>
                                            <option value="featured"{{ (sys_settings('iv_plan_order') == 'featured') ? ' selected' : '' }}>{{ __('Featured') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Display Total Return in Percent') }}</label>
                                    <span class="form-note">{{ __('Show total return value (%) in plan purchase.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input class="switch-option-value" type="hidden" name="plan_total_percent" value="{{ sys_settings('iv_plan_total_percent') ?? 'yes' }}">
                                        <input id="display-total-return" type="checkbox" class="custom-control-input switch-option"
                                               data-switch="yes"{{ (sys_settings('iv_plan_total_percent', 'yes') == 'yes') ? ' checked=""' : ''}}>
                                        <label for="display-total-return" class="custom-control-label">{{ __('Show') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Display Capital Return') }}</label>
                                    <span class="form-note">{{ __('Show capital return in investment plan.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input class="switch-option-value" type="hidden" name="plan_capital_show" value="{{ sys_settings('iv_plan_capital_show') ?? 'no' }}">
                                        <input id="display-capital" type="checkbox" class="custom-control-input switch-option"
                                               data-switch="yes"{{ (sys_settings('iv_plan_capital_show', 'no') == 'yes') ? ' checked=""' : ''}}>
                                        <label for="display-capital" class="custom-control-label">{{ __('Show') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Display Plan Description') }}</label>
                                    <span class="form-note">{{ __('Show plan description in investment plan.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input class="switch-option-value" type="hidden" name="plan_desc_show" value="{{ sys_settings('iv_plan_desc_show') ?? 'no' }}">
                                        <input id="display-description" type="checkbox" class="custom-control-input switch-option"
                                               data-switch="yes"{{ (sys_settings('iv_plan_desc_show', 'no') == 'yes') ? ' checked=""' : ''}}>
                                        <label for="display-description" class="custom-control-label">{{ __('Show') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Display Payout Term') }}</label>
                                    <span class="form-note">{{ __('Show payout terms in investment plan.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input class="switch-option-value" type="hidden" name="plan_payout_show" value="{{ sys_settings('iv_plan_payout_show') ?? 'no' }}">
                                        <input id="display-payout" type="checkbox" class="custom-control-input switch-option"
                                               data-switch="yes"{{ (sys_settings('iv_plan_payout_show', 'no') == 'yes') ? ' checked=""' : ''}}>
                                        <label for="display-payout" class="custom-control-label">{{ __('Show') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Display Term Duration') }}</label>
                                    <span class="form-note">{{ __('Show term duration in investment plan.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input class="switch-option-value" type="hidden" name="plan_terms_show" value="{{ sys_settings('iv_plan_terms_show') ?? 'no' }}">
                                        <input id="display-terms" type="checkbox" class="custom-control-input switch-option"
                                               data-switch="yes"{{ (sys_settings('iv_plan_terms_show', 'no') == 'yes') ? ' checked=""' : ''}}>
                                        <label for="display-terms" class="custom-control-label">{{ __('Show') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Investment Page Headings') }}</label>
                                    <span class="form-note">{{ __('Update investment plan page headings.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group mb-3">
                                    <div class="form-control-wrap w-max-350px">
                                        <input type="text" class="form-control" name="plan_pg_heading" value="{{ sys_settings('iv_iv_plan_pg_heading', 'Investment Plans') }}">
                                    </div>
                                    <div class="form-note mt-1">{{ __('Main Heading') }} <span class="pl-2">{{ __("Eg. :content", ['content' => __("Investment Plans")]) }}</span></div>
                                </div>
                                <div class="form-group mb-3">
                                    <div class="form-control-wrap w-max-350px">
                                        <input type="text" class="form-control" name="plan_pg_title" value="{{ sys_settings('iv_plan_pg_title', 'Choose your favourite plan and start earning now.') }}">
                                    </div>
                                    <div class="form-note mt-1">{{ __('Title') }} <span class="pl-2">{{ __("Eg. :content", ['content' => __("Choose your favourite plan and start earning now.")]) }}</span></div>
                                </div>
                                <div class="form-group">
                                    <div class="form-control-wrap w-max-500px">
                                        <textarea class="form-control textarea-sm" name="plan_pg_text">{{ sys_settings('iv_plan_pg_text', 'Here is our several investment plans. You can invest daily, weekly or monthly and get higher returns in your investment.') }}</textarea> 
                                    </div>
                                    <div class="form-note mt-1">{{ __('Intro Content') }} <span class="pl-2">{{ __("Eg. :content", ['content' => __("Here is our several investment plans. You can invest daily, weekly or monthly and get higher returns in your investment.")]) }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="divider"></div>
                    <div class="form-sets gy-3 wide-md">
                        <h5 class="title">{{ __('Profit Adjustment') }}</h5>

                        <div class="row g-3 align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Adjust Profit / Interest') }}</label>
                                    <span class="form-note">{{ __('How do you want to adjust profit into account / ledger.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="form-control-wrap w-225px">
                                        <select class="form-select" name="profit_payout">
                                            <option value="everytime"{{ (sys_settings('iv_profit_payout', 10)=='everytime') ? ' selected' : '' }}>{{ __("Each Times") }}</option>
                                            <option value="threshold"{{ (sys_settings('iv_profit_payout', 10)=='threshold') ? ' selected' : '' }}>{{ __("Threshold Amount") }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Specify Threshold Amount') }}</label>
                                    <span class="form-note">{{ __('Set an amount that consider for auto adjustment.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="form-control-wrap w-225px">
                                        <div class="form-text-hint"><span>{{ base_currency() }}</span></div>
                                        <input type="number" class="form-control" name="profit_payout_amount" value="{{ sys_settings('iv_profit_payout_amount') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="notes">
                                    <p class="mb-1"><strong>{{ __("Note:") }}</strong></p>
                                    <ul>
                                        <li class="alert-note is-plain text-soft">
                                            <em class="icon ni ni-info"></em>
                                            <p><strong class="text-dark">{{ __("Each Times") }}</strong>: {{ __("Each time calculate the profit against any plan and adjust into investment account with admin action.") }}</p>
                                        </li>
                                        <li class="alert-note is-plain text-soft">
                                            <em class="icon ni ni-info"></em>
                                            <p><strong class="text-dark">{{ __("Threshold Amount") }}</strong>: {{ __("Adjust the investment account balance once calculated profits cross the amount specify above.") }}</p>
                                        </li>
                                        <li class="alert-note is-plain text-danger">
                                            <em class="icon ni ni-alert"></em>
                                            <p><strong>{{ __("Caution") }}</strong>: {{ __("Always admin action required to paid the profits from Invest Statement->Profit/Interest Logs to Paid to Profit.") }}</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="divider"></div>
                    <div class="form-sets gy-3 wide-md">
                        <h5 class="title">{{ __('Balance Transfer Setting') }}</h5>

                        <div class="row g-3 align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Automatic Investment Balance Transfer') }}</label>
                                    <span class="form-note">{{ __('Transfer investment account balance to main wallet automatically.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input class="switch-option-value" type="hidden" name="auto_transfer" value="{{ sys_settings('iv_auto_transfer') ?? 'yes' }}">
                                        <input id="auto-transfer" type="checkbox" class="custom-control-input switch-option"
                                               data-switch="yes"{{ (sys_settings('iv_auto_transfer', 'no') == 'yes') ? ' checked=""' : ''}}>
                                        <label for="auto-transfer" class="custom-control-label">{{ __('Enable') }}</label>
                                    </div>
                                    <span class="form-note mt-1"><em class="text-info">{{ __('If enabled, user can set their auto balance transfer option.') }}</em></span>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Default Mode for Balance Transfer') }}</label>
                                    <span class="form-note">{{ __('Set the default auto transfer mode for users.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="form-control-wrap w-225px">
                                        <select class="form-select" name="transfer_mode">
                                            <option value="all"{{ (sys_settings('iv_transfer_mode', 'all') == 'all') ? ' selected' : '' }}>{{ __("Enable for all users") }}</option>
                                            <option value="selective"{{ (sys_settings('iv_transfer_mode', 'all') == 'selective') ? ' selected' : '' }}>{{ __("Manual selection by users") }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Minimum Threshold Amount') }}</label>
                                    <span class="form-note">{{ __('Specify the threshold minimum amount for balance transfer.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="form-control-wrap w-225px">
                                        <div class="form-text-hint"><span>{{ base_currency() }}</span></div>
                                        <input type="number" class="form-control" name="min_transfer" value="{{ sys_settings('iv_min_transfer', 1) }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 align-center">
                            <div class="col-12">
                                <div class="notes">
                                    <p class="mb-1"><strong>{{ __("Note:") }}</strong></p>
                                    <ul>
                                        <li class="alert-note is-plain text-soft">
                                            <em class="icon ni ni-info"></em>
                                            <p>{{ __("If any user makes changes their personal setting, then default option not applicable.") }}</p>
                                        </li>
                                        <li class="alert-note is-plain text-soft">
                                            <em class="icon ni ni-info"></em>
                                            <p>{{ __("For those users who cannot cover the threshold minimum amount, they can transfer their balance manually.") }}</p>
                                        </li>
                                        <li class="alert-note is-plain text-danger">
                                            <em class="icon ni ni-alert"></em>
                                            <p><strong>{{ __("Caution") }}</strong>: {{ __("Always admin action is required to complete the auto transfer from Active Investment page to Process button.") }}</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="divider"></div>
                    <div class="form-sets gy-3 wide-md">
                        <h5 class="title">{{ __('Advanced Setting') }}</h5>

                        <div class="row g-3 align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Start After Admin Confirm') }}</label>
                                    <span class="form-note">{{ __('Require or not admin confirmation to start investment plan.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input class="switch-option-value" type="hidden" name="admin_confirmtion" value="{{ sys_settings('iv_admin_confirmtion') ?? 'yes' }}">
                                        <input id="admin-confirmtion" type="checkbox" class="custom-control-input switch-option"
                                               data-switch="yes"{{ (sys_settings('iv_admin_confirmtion', 'yes') == 'yes') ? ' checked=""' : ''}}>
                                        <label for="admin-confirmtion" class="custom-control-label">{{ __('Enable') }}</label>
                                    </div>
                                    <span class="form-note mt-1"><em class="text-info">{{ __('If not enabled, the plan will start automatically.') }}</em></span>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Cancel Subscribtion after Purchase') }}</label>
                                    <span class="form-note">{{ __('User allow to cancel the new subscription before time.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="form-control-wrap w-225px">
                                        <select class="form-select" name="cancel_timeout">
                                            <option value="yes"{{ (sys_settings('iv_cancel_timeout', 15)==='yes') ? ' selected' : '' }}>{{ __("Yes") }}</option>
                                            @for($i=0; $i <=12; $i++)
                                            <option value="{{ ($i * 5) }}"{{ (sys_settings('iv_cancel_timeout', 15)===($i * 5)) ? ' selected' : '' }}>{{ ($i==0) ? 'No' : ($i * 5).' Min' }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Disable New Plan Purchase') }}</label>
                                    <span class="form-note">{{ __('Temporarily disable the investment purchase feature.') }}<br>{{ __('It does not affect on any old or running investment plan.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input class="switch-option-value" type="hidden" name="disable_purchase" value="{{ sys_settings('iv_disable_purchase') ?? 'no' }}">
                                        <input id="purchase-disable" type="checkbox" class="custom-control-input switch-option"
                                               data-switch="yes"{{ (sys_settings('iv_disable_purchase', 'no') == 'yes') ? ' checked=""' : ''}}>
                                        <label for="purchase-disable" class="custom-control-label">{{ __('Disable') }}</label>
                                    </div>
                                    <span class="form-note mt-1"><em class="text-danger">{{ __('Users unable to purchase new plan if disable.') }}</em></span>
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
                                        <input type="text" class="form-control" name="disable_title" value="{{ sys_settings('iv_disable_title', 'Temporarily unavailable!') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <textarea class="form-control textarea-sm" name="disable_notice">{{ sys_settings('iv_disable_notice') }}</textarea>
                                    </div>
                                    <div class="form-note">
                                        <span>{{ __('This message will display when user going to invest on any plan.') }}</span>
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
                                    <input type="hidden" name="form_type" value="iv-option">
                                    <input type="hidden" name="form_prefix" value="iv">
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
