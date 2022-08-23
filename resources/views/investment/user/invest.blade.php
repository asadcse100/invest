@extends('user.layouts.master')

@section('title', __('Invest & Earn'))

@php

$currency = base_currency();
$default = ($single==true) ? $schemes : $schemes->first();
$fixed = (data_get($default, 'is_fixed', 0)==1) ? 'yes' : 'no';
$minimum = (data_get($default, 'amount')) ? data_get($default, 'amount') : 0;
$maximum = (data_get($default, 'maximum') && $fixed=='no') ? data_get($default, 'maximum') : 0;

@endphp

@section('content')
<div class="nk-content-body">
    <div class="page-invest wide-xs m-auto" id="iv-step-container">
        <div class="nk-pps-apps">
            <div class="nk-pps-steps">
                <span class="step active"></span>
                <span class="step"></span>
            </div>
            <div class="nk-pps-title text-center">
                @if($single==true)
                <h3 class="title">{{ __('Invest on :Name Plan', ['name' => data_get($default, 'name')]) }}</h3>
                <p class="caption-text">{{ __(data_get($default, 'desc')) }}</p>
                @else
                <h3 class="title">{{ __('Invest & Earn') }}</h3>
                <p class="caption-text">{{ __("We have various investment plans for you.") }} <br class="d-none d-sm-block">{{ __("You can invest daily, weekly or monthly and start earning now.") }}</p>
                @endif
            </div>
            <form action="{{ route('user.investment.invest.preview') }}" class="nk-pps-form">
                <div class="nk-pps-field form-group">
                    <div class="form-label-group">
                        <label class="form-label">{{ __('Invested Plan') }}</label>
                    </div>
                    <input type="hidden" value="{{ the_hash(data_get($default, 'id')) }}" name="scheme" id="iv-invest">
                    @if($single==true)
                    <div class="nk-cm-item single">
                        <div class="nk-cm-icon">
                            <em class="icon ni ni-offer"></em>
                        </div>
                        <div class="nk-cm-text">
                            <span class="label fw-medium">{{ data_get($default, 'plan_name') }}</span>
                            <span class="desc">
                                {{ __("Invest for :terms & earn :calc :amount as profit.", [
                                    'terms' => __(data_get($default, 'term_text_alter')),
                                    'calc' => __(data_get($default, 'calc_period')),
                                    'amount' => data_get($default, 'rate_text'),
                                ]) }}
                            </span>
                        </div>
                    </div>
                    @else 
                    <div class="dropdown nk-pps-dropdown">
                        <a href="javascript:void(0)" class="dropdown-indicator" data-toggle="dropdown" id="iv-invest-scheme">
                            <div class="nk-cm-item">
                                <div class="nk-cm-icon">
                                    <em class="icon ni ni-offer"></em>
                                </div>
                                <div class="nk-cm-text">
                                    <span class="label fw-medium">{{ data_get($default, 'plan_name') }}</span>
                                    <span class="desc">
                                        {{ __("Invest for :terms & earn :calc :amount as profit.", [
                                            'terms' => __(data_get($default, 'term_text_alter')), 
                                            'calc' => __(data_get($default, 'calc_period')),
                                            'amount' => data_get($default, 'rate_text'),
                                        ]) }}
                                    </span>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-auto dropdown-menu-mxh">
                            <ul class="nk-dlist">
                                @foreach($schemes as $plan)
                                <li class="nk-dlist-item{{ ($loop->first) ? ' selected' : '' }}">
                                    <a href="javascript:void(0)" data-plan="{{ the_hash(data_get($plan, 'id')) }}" data-uid="{{ data_get($plan, 'uid_code') }}" data-change="iv-invest" class="nk-dlist-opt iv-plan-change">
                                        <div class="nk-cm-item">
                                            <div class="nk-cm-icon">
                                                <em class="icon ni ni-offer"></em>
                                            </div>
                                            <div class="nk-cm-text">
                                                <span class="label fw-medium">{{ data_get($plan, 'plan_name') }}</span>
                                                <span class="desc">
                                                    {{ __("Invest for :terms & earn :calc :amount as profit.", [
                                                        'terms' => __(data_get($plan, 'term_text_alter')),
                                                        'calc' => __(data_get($plan, 'calc_period')),
                                                        'amount' => data_get($plan, 'rate_text'),
                                                    ]) }}
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="nk-pps-field form-group">
                    <div class="form-label-group">
                        <label class="form-label iv-invest-lb-amount{{ ($fixed=='yes') ? ' hide' : '' }}">{{ __('Enter Your Amount') }}</label>
                        <label class="form-label iv-invest-lb-fixed{{ ($fixed=='no') ? ' hide' : '' }}">{{ __('Fixed Investment Amount') }}</label>
                    </div>
                    <div class="form-control-group">
                        <div class="form-info">{{ strtoupper($currency) }}</div>
                        <input type="text" name="amount" class="form-control form-control-lg form-control-number iv-invest-amount bg-white" placeholder="{{ data_get($default, 'amount') }}" value="{{ ($fixed=='yes') ? data_get($default, 'amount') : '' }}"{{ ($fixed=='yes') ? ' readonly' : '' }}>
                    </div>
                    <div class="form-note-group">
                        <span class="form-note-alt iv-invest-fixed{{ ($fixed=='no') ? ' hide' : '' }}">
                            <em>{!! __('Note: The investment amount is a fixed amount for the selected plan.') !!}</em>
                        </span>
                        <span class="form-note-alt iv-invest-min{{ ($fixed=='yes'||$minimum==0) ? ' hide' : '' }}">
                            <span>{!! __('Minimum: :amount', ['amount' => '<span class="amount">'.money($minimum, $currency).'</span>' ]) !!}</span>
                        </span>
                        <span class="form-note-alt iv-invest-max{{ ($fixed=='yes'||$maximum==0) ? ' hide' : '' }}">
                            <span>{!! __('Maximum: :amount', ['amount' => '<span class="amount">'.money($maximum, $currency).'</span>' ]) !!}</span>
                        </span>
                    </div>
                </div>
                <div class="nk-pps-field form-group">
                    <div class="form-label-group">
                        <label class="form-label">{{ __('Payment Account') }}</label>
                    </div>
                    <div class="form-control-group">
                        <input type="hidden" value="wallet" name="source">
                        <div class="nk-cm-item single">
                            <div class="nk-cm-icon">
                                <em class="icon ni ni-wallet-fill"></em>
                            </div>
                            <div class="nk-cm-text">
                                <span class="label">{{ __('Main Balance') }}</span>
                                <span class="desc">
                                    {{ __('Current Balance: :amount ( :alter )', [
                                        'amount' => money(user_balance(AccType('main')), $currency),
                                        'alter' => money(base_to_secondary(user_balance(AccType('main'))), alter_currency())
                                    ]) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="nk-pps-field form-action">
                    <div class="nk-pps-action">
                        <a href="javascript:void(0)" class="btn btn-lg btn-block btn-primary iv-get-started">
                            <span>{{ __('Continue to Invest') }}</span>
                            <span class="spinner-border spinner-border-sm hide" role="status" aria-hidden="true"></span>
                        </a>
                    </div>
                </div>
                <div class="form-note text-base text-center mb-n1">{{ __('By continue this, you agree to our investment terms and conditions.') }}</div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    const routes = { confirm: "{{ route('user.investment.invest.confirm') }}" }, plans = @json($plans);
</script>
@endpush
