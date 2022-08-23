@extends('admin.layouts.master')
@section('title', __('Rewards Program'))

@section('has-content-sidebar', 'has-content-sidebar')

@section('content-sidebar')
    @include('admin.settings.content-sidebar')
@endsection

@section('content')
    <div class="nk-content-body">

        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">{{ __('Rewards Program') }}</h3>
                    <p>{{ __('Manage reward and bonuses that you want to give users.') }}</p>
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
                    <h5 class="title">{{ __('Signup Bonus') }}</h5>
                    <div class="form-sets gy-3 wide-md">
                        <div class="row g-3 align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label" for="allow-bonus-signup">{{ __('Allow Bonus on Signup') }}</label>
                                    <span class="form-note">{{ __('Give additional bonus for new signup into website.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input class="switch-option-value" type="hidden" name="signup_bonus_allow" value="{{ sys_settings('signup_bonus_allow') ?? 'no' }}">
                                        <input id="allow-bonus-signup" type="checkbox" class="custom-control-input switch-option" 
                                               data-switch="yes"{{ (sys_settings('signup_bonus_allow', 'no') == 'yes') ? ' checked=""' : ''}}>
                                        <label for="allow-bonus-signup" class="custom-control-label">{{ __('Enable') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label" for="bonus-amount">{{ __('Signup Bonus Amount') }}</label>
                                    <span class="form-note">{{ __('The amount will received once signup completed.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="form-control-wrap w-max-250px">
                                        <div class="form-text-hint"><span>{{ base_currency() }}</span></div>
                                        <input type="number" id="bonus-amount" class="form-control" name="signup_bonus_amount" value="{{ sys_settings('signup_bonus_amount', '0') }}" min="0">
                                    </div>
                                    <div class="form-note">{{ __('Specify the amount to add into account.') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="divider"></div>
                    <h5 class="title">{{ __('Deposit Bonus') }}</h5>
                    <div class="form-sets gy-3 wide-md">
                        <div class="row g-3 align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label" for="allow-bonus-deposit">{{ __('Allow Bonus on Deposit') }}</label>
                                    <span class="form-note">{{ __('Give additional bonus for the first successful deposit.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input class="switch-option-value" type="hidden" name="deposit_bonus_allow" value="{{ sys_settings('deposit_bonus_allow') ?? 'no' }}">
                                        <input id="allow-bonus-deposit" type="checkbox" class="custom-control-input switch-option" 
                                               data-switch="yes"{{ (sys_settings('deposit_bonus_allow', 'no') == 'yes') ? ' checked=""' : ''}}>
                                        <label for="allow-bonus-deposit" class="custom-control-label">{{ __('Enable') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label" for="dp-bonus-amount">{{ __('Deposit Bonus Amount') }}</label>
                                    <span class="form-note">{{ __('The amount will adjust into account once first deposit completed.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="row gx-1 gy-1 w-max-250px">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <input type="number" id="dp-bonus-amount" class="form-control" name="deposit_bonus_amount" value="{{ sys_settings('deposit_bonus_amount', '0') }}" min="0">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <select class="form-select" name="deposit_bonus_type">
                                                <option value="fixed"{{ (sys_settings('deposit_bonus_type', 'fixed')=='fixed') ? ' selected' : '' }}>{{ __("Fixed (:base)", [ 'base' => base_currency() ]) }}</option>
                                                <option value="percent"{{ (sys_settings('deposit_bonus_type', 'fixed')=='percent') ? ' selected' : '' }}>{{ __("Percent") }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-note">{{ __('Specify the amount to add into account.') }}</div>
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
                                    <input type="hidden" name="form_type" value="reward-options">
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
