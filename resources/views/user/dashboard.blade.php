@extends('user.layouts.master')

@section('title', __('Dashboard'))

@section('content')
<div class="nk-content-body">
    <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-head-sub"><span>{{ __('Welcome!') }}</span></div>
        <div class="nk-block-between-md g-4">
            <div class="nk-block-head-content">
                <div class="nk-block-des">
                    <p>{{ __("Here's a summary of your account. Have fun!") }}</p>
                </div>
            </div>
            <div class="nk-block-head-content d-none d-md-inline-flex">
                <ul class="nk-block-tools gx-3">
                    @if (module_exist('FundTransfer', 'mod') && feature_enable('transfer'))
                        <li><a href="{{ route('user.send-funds.show') }}" class="btn btn-light btn-white"><span>{{ __('Send Funds') }}</span> <em class="icon ni ni-arrow-long-right d-none d-lg-inline-block"></em></a></li>
                    @endif
                    @if(has_route('user.investment.invest'))
                    <li><a href="{{ route('user.investment.invest') }}" class="btn btn-secondary"><span>{{ __('Invest & Earn') }}</span> <em class="icon ni ni-arrow-long-right d-none d-lg-inline-block"></em></a></li>
                    @endif
                    <li><a href="{{ route('deposit') }}" class="btn btn-primary"><span>{{ __('Deposit') }}</span> <em class="icon ni ni-arrow-long-right"></em></a></li>
                </ul>
            </div>
        </div>
    </div>
    
    @if(has_restriction())
    <div class="nk-block">
        <div class="alert alert-danger bg-white alert-thick">
            <div class="alert-cta flex-wrap flex-md-nowrap g-2">
                <div class="alert-text has-icon">
                    <em class="icon ni ni-report-fill text-danger"></em>
                    <p class="text-base"><strong>{{ __("Caution") }}:</strong> {{ 'All the transactions are NOT real as you have logged into demo application to see the platform.' }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    {!! Panel::profile_alerts() !!}

    <div class="nk-block">
        <div class="row g-gs">
            <div class="col-md-4">
                {!! Panel::balance('account', ['cta' => true]) !!}
            </div>
            <div class="col-md-4">
                {!! Panel::balance('deposit') !!}
            </div>
            <div class="col-md-4">
                {!! Panel::balance('withdraw') !!}
            </div>
        </div>
    </div>

    @if (filled($recentTransactions))
    <div class="nk-block nk-block-lg">
        <div class="nk-block-head-sm">
            <div class="nk-block-between-md g-4">
                <div class="nk-block-head-content">
                    <h5 class="nk-block-title title">{{ __('Recent Activity') }}</h5>
                </div>
                <div class="nk-block-head-content">
                    <a href="{{ route('transaction.list') }}">{{ __('See History') }}</a>
                </div>
            </div>
        </div>
        <div class="nk-odr-list card card-bordered">
            @foreach($recentTransactions as $transaction)
                @include('user.transaction.trans-row', compact('transaction'))
            @endforeach
        </div>
    </div>
    @endif

    {!! Panel::referral('invite-card') !!}

    {!! Panel::cards('support') !!}

    @if(Panel::news()) 
    <div class="nk-block">
        <div class="card card-bordered d-xl-none">
            <div class="card-inner card-inner-sm">
                {!! Panel::news() !!}
            </div>
        </div>
    </div>
    @endif

</div>
@endsection

@if (filled($recentTransactions))
    @push('modal')
    <div class="modal fade" role="dialog" id="ajax-modal"></div>
    @endpush
@endif