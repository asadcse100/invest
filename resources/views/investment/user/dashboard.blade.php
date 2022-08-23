@extends('user.layouts.master')
@section('title', __('Investment Overview'))

@section('content')
<div class="nk-content-body">
    <div class="nk-block-head">
        <div class="nk-block-head-sub"><span>{{ __('Investment') }}</span></div>
        <div class="nk-block-between-md g-4">
            <div class="nk-block-head-content">
                <h2 class="nk-block-title fw-normal">{{ __('Invested Plans') }}</h2>
                <div class="nk-block-des">
                    <p>{{ __('At a glance summary of your investment.') }}</p>
                </div>
            </div>
            <div class="nk-block-head-content">
                <ul class="nk-block-tools gx-3">
                    <li class="order-md-last"><a href="{{ route('user.investment.invest') }}" class="btn btn-primary"><span>{{ __('Invest & Earn') }}</span> <em class="icon ni ni-arrow-long-right"></em></a></li>
                    <li><a href="{{ route('deposit') }}" class="btn btn-light btn-white"><span>{{ __('Deposit Funds') }}</span> <em class="icon ni ni-arrow-long-right"></em></a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="nk-block">
        <div class="row gy-gs">
            <div class="col-md-6 col-lg-5 col-xxl-4">
                <div class="card card-full card-bordered card-wg on-left is-primary">
                    <div class="card-inner">
                        <div class="nk-wgacc">
                            <div class="nk-wgacc-title text-base">
                                {{ __("Investment Account") }} 
                                <em class="icon ni ni-info fs-13px text-soft nk-tooltip" title="{{ __("The available balance in your investment account.") }}"></em>
                            </div>
                            <div class="nk-wgacc-group flex-lg-nowrap gx-4">
                                <div class="nk-wgacc-sub">
                                    <div class="nk-wgacc-amount">
                                        <div class="number number-md">{{ account_balance(AccType('invest')) }} <small class="currency">{{ base_currency() }}</small></div>
                                    </div>
                                    <div class="nk-wgacc-subtitle">{{ __('Available Funds') }}</div>
                                </div>
                                <div class="nk-wgacc-sub">
                                    <span class="nk-wgacc-sign text-soft"><em class="icon ni ni-plus"></em></span>
                                    <div class="nk-wgacc-amount">
                                        <div class="number number-sm">{{ amount($amounts['locked'], base_currency(), ['zero' => true]) }}</div>
                                    </div>
                                    <div class="nk-wgacc-subtitle">{{ __('Locked') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-action">
                            <ul class="nk-block-tools gx-1">
                                <li><a href="{{ route('user.investment.payout') }}" class="btn btn-secondary iv-payout"><span>{{ __('Transfer Funds') }}</span> <em class="icon ni ni-arrow-long-right"></em></a></li>

                                @if (gss("iv_auto_transfer", "no") == "yes")
                                    <li><a href="{{ route('user.investment.settings') }}" class="btn btn-trans iv-settings"><em class="icon ni ni-setting"></em></a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-7 col-xxl-8">
                <div class="card card-bordered">
                    <div class="card-inner-group">
                        <div class="card-inner">
                            <div class="row gy-gs">
                                <div class="col-md-12">
                                    <div class="nk-wgacc">
                                        <div class="nk-wgacc-group flex-md-nowrap gx-4">
                                            <div class="flex-shrink-0">
                                                <div class="nk-wgacc-title text-base">
                                                    {{ __('Amount in Invested') }}
                                                    <em class="icon ni ni-info fs-13px text-soft nk-tooltip" title="{{ __("The investment currently actived without pending.") }}"></em>
                                                </div>
                                                <div class="nk-wgacc-group flex-md-nowrap gx-4">
                                                    <div class="nk-wgacc-sub">
                                                        <div class="nk-wgacc-amount">
                                                            <div class="number number-md">{{ amount($amounts['invested'], base_currency(), ['zero' => true]) }} <small class="currency">{{ base_currency() }}</small></div>
                                                        </div>
                                                        <div class="nk-wgacc-subtitle">{{ __('Currently Invested') }}</div>
                                                    </div>
                                                    <div class="nk-wgacc-sub">
                                                        <span class="nk-wgacc-sign text-soft"><em class="icon ni ni-plus"></em></span>
                                                        <div class="nk-wgacc-amount">
                                                            <div class="number number-sm">{{ amount($amounts['profit'], base_currency(), ['zero' => true]) }}</div>
                                                        </div>
                                                        <div class="nk-wgacc-subtitle">{{ __('Approx Profit') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="nk-wgacc-sub flex-grow-1 ml-lg-1 ml-xxl-5 d-md-none d-lg-block">
                                                <div class="nk-wgacc-ck lg mb-0">
                                                    <canvas class="chart-liner" id="dailyInvestment"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-inner">
                            <ul class="nk-wgacc-nav">
                                <li><a href="{{ route('user.investment.transactions') }}"><em class="icon ni ni-notes-alt"></em> <span>{{ __('Transactions') }}</span></a></li>
                                <li><a href="{{ route('user.investment.history') }}"><em class="icon ni ni-file-check"></em> <span>{{ __('History') }}</span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(!blank($pendingPlans = data_get($investments, 'pending', [])))
    <div class="nk-block nk-block-lg">
        <div class="nk-block-head-sm">
            <div class="nk-block-head-content">
                <h5 class="nk-block-title">{{ __('Pending Plan') }} <span class="count text-base">({{ count($pendingPlans) }})</span></h5>
            </div>
        </div>

        <div class="nk-plan-list">
            @foreach($pendingPlans as $plan)
                @include('investment.user.plan-row')
            @endforeach
        </div>
    </div>
    @endif

    @if(!blank($activePlans = data_get($investments, 'active', [])))
    <div class="nk-block nk-block-lg">
        <div class="nk-block-head-sm">
            <div class="nk-block-head-content">
                <h5 class="nk-block-title">{{ __('Active Plan') }} <span class="count text-base">({{ count($activePlans) }})</span></h5>
            </div>
        </div>

        <div class="nk-plan-list">
            @foreach($activePlans as $plan)
                @include('investment.user.plan-row', $plan)
            @endforeach
        </div>
    </div>
    @endif

    @if(!blank($recents))
    <div class="nk-block nk-block-lg">
        <div class="nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h5 class="nk-block-title">{{ __('Recently End') }} <span class="count text-base">({{ count($recents) }})</span></h5>
                </div>
                <div class="nk-block-head-content">
                    <a href="{{route('user.investment.history', 'completed')}}"><em class="icon ni ni-dot-box"></em> {{ __('Go to Archive') }}</a>
                </div>
            </div>
        </div>

        <div class="nk-plan-list">
            @foreach($recents as $plan)
                @include('investment.user.plan-row', $plan)
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

@push('modal')
<div class="modal fade" role="dialog" id="ajax-modal"></div>
@endpush

@push('scripts')

    <script>

        var investment=JSON.parse('{!! json_encode($investChart) !!}');

        var profit=JSON.parse('{!! json_encode($profitChart) !!}');

        var dailyInvestment = { tooltip: false, legend: true, labels: Object.keys(investment), dataUnit: '{{base_currency()}}', stacked: true, lineTension: .3,
            datasets: [{ label: "Investment", color: "#816bff", background: 'transparent', borderWidth: 2, data: Object.values(investment) },
            { label: "Profit", color: "#c4cefe", background: 'transparent', borderWidth: 2, data: Object.values(profit) }] };

        let $btnSettings = $('.iv-settings'), $modalTnx = $('#ajax-modal');
		$btnSettings.on('click', function(e) {
			e.preventDefault();
			let $self = $(this), url = $self.attr('href'), data = [];
	        NioApp.Form.toModal(url, data, {modal: $modalTnx});
		});
    </script>

@endpush