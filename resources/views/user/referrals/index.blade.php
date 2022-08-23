@extends('user.layouts.master')

@section('title', __('Referrals'))

@section('content')
<div class="nk-content-body">
	<div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-head-sub"><span>{{ __('Referrals') }}</span></div>
        <div class="nk-block-between-md g-4">
            <div class="nk-block-head-content">
                <h2 class="nk-block-title fw-normal">{{ __('Referral Activity') }}</h2>
                <div class="nk-block-des">
                    <p>{{ __("See who you've referred and statistic of your referrals.") }}</p>
                </div>
            </div>
        </div>
    </div>
    
    {!! Panel::profile_alerts() !!}

    @if (sys_settings('referral_show_referred_users', 'no') == 'yes')
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <h5 class="nk-block-title">{{ __('Referral List') }}</h5>
            </div>
            <div class="card card-bordered">
                <table class="nk-plan-tnx table">
                    <thead class="thead-light">
                    <tr>
                        <th class="tb-col-type w-50"><span class="overline-title">{{ __('Username') }}</span></th>
                        <th class="tb-col-date tb-col-md"><span class="overline-title">{{ __('Join Date') }}</span></th>
                        @if (in_array('earning', sys_settings('referral_user_table_opts', [])))
                            <th class="tb-col-amount tb-col-end"><span class="overline-title">{{ __('Earned') }}</span></th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                        @if (filled($refers))
                        @foreach($refers as $refer)
                        <tr>
                            <td class="tb-col-type w-50">
                                <span class="sub-text">{{ in_array('compact', sys_settings('referral_user_table_opts', [])) ? str_compact(data_get($refer, 'referred.username')) : data_get($refer, 'referred.username') }}</span>
                            </td>
                            <td class="tb-col-date tb-col-md">
                                <span class="sub-text">{{ show_date(data_get($refer, 'join_at'), true) }}</span>
                            </td>
                            @if (in_array('earning', sys_settings('referral_user_table_opts', [])))
                            <td class="tb-col-amount tb-col-end">
                                <span>{{ isset($earnings[$refer->user_id]) ? money($earnings[$refer->user_id]->sum(), base_currency(), ['dp' => 'calc']) : money('0', base_currency()) }}</span>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                        @else 
                        <tr>
                            <td colspan="{{ (in_array('earning', sys_settings('referral_user_table_opts', []))) ? '3' : '2' }}">{{ __("No one join yet!") }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                @if($refers->hasPages())
                <div class="card-inner border-top pt-3 pb-3">
                    {{ $refers->appends(request()->except('refers'))->links('misc.pagination') }}
                </div>
                @endif
            </div>
        </div>
    @endif

    <div class="nk-block nk-block-lg">
        <div class="nk-block-head">
            <h5 class="nk-block-title">{{ __('Referral Commissions') }}</h5>
        </div>
        <div class="card card-bordered">
            <table class="nk-plan-tnx table">
                <thead class="thead-light">
                <tr>
                    <th class="tb-col-type w-50"><span class="overline-title">{{ __('Details') }}</span></th>
                    <th class="tb-col-date tb-col-md"><span class="overline-title">{{ __('Date') }}</span></th>
                    <th class="tb-col-status tb-col-sm"><span class="overline-title">{{ __('Status') }}</span></th>
                    <th class="tb-col-amount tb-col-end"><span class="overline-title">{{ __('Earning') }}</span></th>
                </tr>
                </thead>
                <tbody>
                @if (filled($transactions))
                @foreach($transactions as $tranx)
                <tr>
                    <td class="tb-col-type w-50"><span class="sub-text">{{ $tranx->description }}</span></td>
                    <td class="tb-col-date tb-col-md">
                        <span class="sub-text">{{ show_date(data_get($tranx, 'created_at'), true) }}</span>
                    </td>
                    <td class="tb-col-status tb-col-sm">
                        <span class="sub-text">{{ ucfirst(__(tnx_status_switch($tranx->status))) }} {!! ($tranx->completed_at) ? '<em class="icon ni ni-info nk-tooltip text-soft" title="'. __("At :time", ['time' => show_date($tranx->completed_at, true) ]). '"></em> ' : '' !!}</span>
                    </td>
                    <td class="tb-col-amount tb-col-end"><span>{{ amount_z($tranx->amount, base_currency(), ['dp' => 'calc']) }}</span></td>
                </tr>
                @endforeach
                @else 
                <tr>
                    <td colspan="4">{{ __("No transactions found!") }}</td>
                </tr>
                @endif

                </tbody>
            </table>
            @if($transactions->hasPages())
            <div class="card-inner border-top pt-3 pb-3">
                {{ $transactions->appends(request()->except('transactions'))->links('misc.pagination') }}
            </div>
            @endif
        </div>
    </div>

    <div class="nk-block">
        {!! Panel::referral('invite-card') !!}
    </div>

    {!! Panel::cards('support') !!}
    
</div>
@endsection
