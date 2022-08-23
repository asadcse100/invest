@extends('admin.layouts.master')
@section('title', __('Invest Dashboard'))

@php 
    use App\Enums\InvestmentStatus;
    use Illuminate\Support\Arr;

    $currency = base_currency();

    $plans = [];
    $renderIvGraph = !empty($ivGraph) && array_sum($ivGraph) > 0;
    $renderTopSchemeGraph = !empty($topSchemeGraph) && array_sum($topSchemeGraph) > 0;

    foreach ($activePlans as $name => $data) {
        $plans['name'][] = $name;
        $plans['colors'][] = $data['color'];
        $plans['data'][] = $data['count'];
    }
@endphp

@section('content')
<div class="nk-content-body">
    <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-between g-3">
            <div class="nk-block-head-content">
                <h3 class="nk-block-title page-title">{{ __("Investment Insight") }}</h3>
                <div class="nk-block-des text-soft">
                    <p>{{ __("Here is an insight of what's going on.") }}</p>
                </div>
            </div>
            <div class="nk-block-head-content">
                <a href="{{route('admin.investment.list')}}" class="btn btn-primary d-none d-sm-inline-flex"><em class="icon ni ni-invest"></em> <span>{{ __("View Investment") }}</span></a>
                <a href="{{route('admin.investment.list')}}" class="btn btn-icon btn-primary d-inline-flex d-sm-none"><em class="icon ni ni-invest"></em></a>
            </div>
        </div>
    </div>
    <div class="nk-block">
        <div class="row g-gs">
            <div class="col-md-6 col-xxl-4">
                <div class="card card-bordered card-full">
                    <div class="card-inner">
                        <div class="card-title-group mb-2">
                            <div class="card-title">
                                <h6 class="title">{{ __("Actived Investment") }}</h6>
                                <p>{{ __("The amount of investment currently actived.") }}</p>
                            </div>
                        </div>
                        <div class="card-amount">
                            <div class="amount">{{ to_amount(data_get($activeInvest, 'amount'), $currency) }} <span class="currency">{{ $currency }}</span></div>
                            <div class="amount-sm">{{ to_amount(data_get($activeInvest, 'since.amount'), $currency) }} <small>{{ __("since last week") }}</small></div>
                        </div>
                        <div class="card-stats mt-4 pb-3">
                            <div class="card-stats-group g-2">
                                <div class="card-stats-data">
                                    <div class="title">{{ __("Profit to Pay") }} <em class="card-hint icon ni ni-help fs-12px" data-toggle="tooltip" data-placement="top" title="{{ __("The aprox profit amount need to pay based on actived investment.") }}"></em></div>
                                    <div class="amount lg">{{ to_amount(data_get($activeInvest, 'profit'), $currency) }} <span class="currency">{{ $currency }}</span></div>
                                </div>
                                <div class="card-stats-data">
                                    <div class="title">{{ __("Active Plans") }}</div>
                                        <div class="amount lg">
                                            {{ data_get($activeInvest, 'plan') }}
                                            @if( data_get($activeInvest, 'diff.plan') !== 0 )
                                                <span class="change {{ data_get($activeInvest, 'diff.plan') > 0 ? 'up' : 'down'}} tipinfo" title="{{ __("Than last week") }}">
                                                <em class="icon ni ni-arrow-long-{{ data_get($activeInvest, 'diff.plan') > 0 ? 'up' : 'down' }}"></em>{{ abs(data_get($activeInvest, 'diff.plan')) }}%</span>
                                            @endif
                                        </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-stats-ck-full h-150px border-bottom border-primary">
                            @if ($renderIvGraph)
                            <canvas class="chart-minibar" id="activeInvestment"></canvas>
                            @endif
                        </div>
                        <div class="card-stats-ck-label mb-n1 mt-0 pt-1 border-top border-primary">
                            <div class="chart-label">{{ data_get($ivGraphDate, 'start') }}</div>
                            <div class="chart-label">{{ data_get($ivGraphDate, 'end') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xxl-4">
                <div class="card card-bordered card-full">
                    <div class="card-inner">
                        <div class="card-title-group mb-1">
                            <div class="card-title">
                                <h6 class="title">{{ __("Actived Investment Plan") }}</h6>
                                <p>{{ __("The invested plans that currently actived.") }}</p>
                            </div>
                        </div>
                        <div class="nk-ovb">
                            @if(!empty($activePlans))
                            <div class="nk-ovb-ck-dnut pt-2 pb-3">
                                @if (!empty($plans))
                                <canvas class="chart-dnut" id="activeInvestPlans"></canvas>
                                @endif
                            </div>
                            <div class="nk-ovb-group g-2">
                                @foreach($activePlans as $name => $data)
                                <div class="nk-ovb-data">
                                    <div class="title">
                                        <span class="dot dot-lg sq" data-bg="{{ $data['color'] }}"></span>
                                        <span>{{ $name }}</span>
                                    </div>
                                    <div class="amount">{{ $data['count'] }} <small>{{ $data['percentage'] }}%</small></div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="no-result pt-5 pb-5 px-1 text-center d-block">
                                <span class="text-soft font-italic small">{{ __("Available data is not enough to display chart.") }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xxl-4">
                <div class="card card-bordered card-full">
                    <div class="card-inner">
                        <div class="card-title-group mb-1">
                            <div class="card-title">
                                <h6 class="title">{{ __("Investment Overview") }}</h6>
                                <p>{{ __("The overview of investment.") }} <a href="{{ route('admin.investment.list') }}">{{ __("View Investment") }}</a></p>
                            </div>
                        </div>
                        <ul class="nav nav-tabs nav-tabs-card nav-tabs-xs">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#thismonth">{{ __("This Month") }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#thisyear">{{ __("This Year") }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#alltime">{{ __("All Time") }}</a>
                            </li>
                        </ul>
                        <div class="tab-content mt-0">
                            <div class="tab-pane active" id="thismonth">
                                <div class="nk-ivo gy-2">
                                    <div class="subtitle">{{ __("Total Investment") }}</div>
                                    <div class="nk-ivo-data">
                                        <div class="nk-ivo-info">
                                            <div class="amount">{{ to_amount(data_get($stats, 'month.amount'), $currency) }} <span class="currency">{{ $currency }}</span>
                                            @if(data_get($stats, 'month.diff.amount') !== 0)
                                                <span class="change {{ data_get($stats, 'month.diff.amount') > 0 ? 'up' : 'down'}} tipinfo" title="Than last month">
                                                <em class="icon ni ni-arrow-long-{{ data_get($stats, 'month.diff.amount') > 0 ? 'up' : 'down' }}"></em>{{ abs(data_get($stats, 'month.diff.amount')) }}%</span>
                                            @endif
                                            </div>
                                            <div class="title">{{ __("Investment Amount") }}</div>
                                        </div>
                                        <div class="nk-ivo-stats">
                                            <div class="amount">{{ data_get($stats, 'month.plan') }}
                                            @if(data_get($stats, 'month.diff.plan') !== 0)
                                                <span class="change {{ data_get($stats, 'month.diff.plan') > 0 ? 'up' : 'down'}} tipinfo" title="Than last month">
                                                <em class="icon ni ni-arrow-long-{{ data_get($stats, 'month.diff.plan') > 0 ? 'up' : 'down' }}"></em>{{ abs(data_get($stats, 'month.diff.plan')) }}%</span>
                                            @endif
                                            </div>
                                            <div class="title">{{ __("Plans") }}</div>
                                        </div>
                                    </div>
                                    <div class="nk-ivo-data">
                                        <div class="nk-ivo-info">
                                            <div class="amount">
                                                {{ to_amount(data_get($stats, 'month.profit'), $currency) }} <span class="currency">{{ $currency }}</span>
                                            </div>
                                            <div class="title">{{ __("Paid Profit") }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-ivo gy-2">
                                    <div class="subtitle">{{ __("Investment in Last Month") }}</div>
                                    <div class="nk-ivo-data">
                                        <div class="nk-ivo-info">
                                            <div class="amount">{{ to_amount(data_get($stats, 'month.last.amount'), $currency) }} <span class="currency">{{ $currency }}</span></div>
                                            <div class="title">{{ __("Investment Amount") }}</div>
                                        </div>
                                        <div class="nk-ivo-info">
                                            <div class="amount">{{ to_amount(data_get($stats, 'month.last.profit'), $currency) }} <span class="currency">{{ $currency }}</span></div>
                                            <div class="title">{{ __("Paid Profit") }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="thisyear">
                                <div class="nk-ivo gy-2">
                                    <div class="subtitle">{{ __("Total Investment") }}</div>
                                    <div class="nk-ivo-data">
                                        <div class="nk-ivo-info">
                                            <div class="amount">{{ to_amount(data_get($stats, 'year.amount'), $currency) }} <span class="currency">{{ $currency }}</span>
                                            @if(data_get($stats, 'year.diff.amount') !== 0)
                                                <span class="change {{ data_get($stats, 'year.diff.amount') > 0 ? 'up' : 'down'}} tipinfo" title="Than last month">
                                                <em class="icon ni ni-arrow-long-{{ data_get($stats, 'year.diff.amount') > 0 ? 'up' : 'down' }}"></em>{{ abs(data_get($stats, 'year.diff.amount')) }}%</span>
                                            @endif
                                            </div>
                                            <div class="title">{{ __("Investment Amount") }}</div>
                                        </div>
                                        <div class="nk-ivo-stats">
                                            <div class="amount">{{ data_get($stats, 'year.plan') }}
                                            @if(data_get($stats, 'year.diff.plan') !== 0)
                                                <span class="change {{ data_get($stats, 'year.diff.plan') > 0 ? 'up' : 'down'}} tipinfo" title="Than last month">
                                                <em class="icon ni ni-arrow-long-{{ data_get($stats, 'year.diff.plan') > 0 ? 'up' : 'down' }}"></em>{{ abs(data_get($stats, 'year.diff.plan')) }}%</span>
                                            @endif
                                            </div>
                                            <div class="title">{{ __("Plans") }}</div>
                                        </div>
                                    </div>
                                    <div class="nk-ivo-data">
                                        <div class="nk-ivo-info">
                                            <div class="amount">
                                                {{ to_amount(data_get($stats, 'year.profit'), $currency) }} <span class="currency">{{ $currency }}</span>
                                            </div>
                                            <div class="title">{{ __("Paid Profit") }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-ivo gy-2">
                                    <div class="subtitle">{{ __("Investment in Last Year") }}</div>
                                    <div class="nk-ivo-data">
                                        <div class="nk-ivo-info">
                                            <div class="amount">{{ to_amount(data_get($stats, 'year.last.amount'), $currency) }} <span class="currency">{{ $currency }}</span></div>
                                            <div class="title">{{ __("Investment Amount") }}</div>
                                        </div>
                                        <div class="nk-ivo-info">
                                            <div class="amount">{{ to_amount(data_get($stats, 'year.last.profit'), $currency) }} <span class="currency">{{ $currency }}</span></div>
                                            <div class="title">{{ __("Paid Profit") }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="alltime">
                                <div class="nk-ivo gy-2">
                                    <div class="subtitle">{{ __("Total Investment") }}</div>
                                    <div class="nk-ivo-data">
                                        <div class="nk-ivo-info">
                                            <div class="amount">{{ to_amount(data_get($stats, 'all.amount'), $currency) }}
                                            <span class="currency">{{ $currency }}</span></div>
                                            <div class="title">{{ __("Investment Amount") }}</div>
                                        </div>
                                        <div class="nk-ivo-stats">
                                            <div><span class="amount">{{ data_get($stats, 'all.plan') }}</span></div>
                                            <div class="title">{{ __("Plans") }}</div>
                                        </div>
                                    </div>
                                    <div class="nk-ivo-data">
                                        <div class="nk-ivo-info">
                                            <div class="amount">{{ to_amount(data_get($stats, 'all.profit'), $currency) }}
                                            <span class="currency">{{ $currency }}</span></div>
                                            <div class="title">{{ __("Paid Profit") }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-ivo gy-2">
                                    <div class="subtitle">{{ __("Investment in This Year") }}</div>
                                    <div class="nk-ivo-data">
                                        <div class="nk-ivo-info">
                                            <div class="amount">{{ to_amount(data_get($stats, 'year.amount'), $currency) }}
                                            <span class="currency">{{ $currency }}</span></div>
                                            <div class="title">{{ __("Investment Amount") }}</div>
                                        </div>
                                        <div class="nk-ivo-info">
                                            <div class="amount">{{ to_amount(data_get($stats, 'year.profit'), $currency) }}
                                            <span class="currency">{{ $currency }}</span></div>
                                            <div class="title">{{ __("Paid Profit") }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xxl-4">
                <div class="card card-bordered card-full">
                    <div class="card-inner pb-0">
                        <div class="card-title-group mb-3">
                            <div class="card-title">
                                <h6 class="title">{{ __("Top Invested Scheme") }}</h6>
                                <p>{{ __("In last 30 days top invested plans.") }}</p>
                            </div>
                            <div class="card-tools mt-n4 mr-n1">
                                <div class="drodown">
                                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <ul class="link-list-opt no-bdr">
                                            <li><a href="{{ route('admin.investment.list') }}">
                                                <em class="icon ni ni-invest"></em><span>{{ __("View Active Plan") }}</span>
                                            </a></li>
                                            <li><a href="{{ route('admin.investment.schemes') }}">
                                                <em class="icon ni ni-cards"></em><span>{{ __("View All Schemes") }}</span>
                                            </a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(!empty($topPlans))
                        <div class="progress-list gy-3">
                            @foreach ($topPlans as $name => $data)
                            <div class="progress-wrap">
                                <div class="progress-text">
                                    <div class="progress-label">{{ $name }}</div>
                                    <div class="progress-amount">{{ $data['count'] }}</div>
                                </div>
                                <div class="progress progress-md">
                                    <div class="progress-bar" data-progress="{{ $data['percentage'] }}"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="no-result pt-2 pb-4 px-1 text-center d-block">
                            <span class="text-soft font-italic small">{{ __("Available data is not enough to display chart.") }}</span>
                        </div>
                        @endif
                    </div>
                    @if ($renderTopSchemeGraph)
                    <div class="nk-ivo-ck mt-auto">
                        <canvas class="chart-liner" id="topInvestPlan"></canvas>
                    </div>
                    @endif
                </div>
            </div>

            <div class="col-md-12 col-xxl-8">
                <div class="card card-bordered card-full">
                    <div class="card-inner border-bottom">
                        <div class="card-title-group">
                            <div class="card-title">
                                <h6 class="title">{{ __("Recent Investment") }}</h6>
                            </div>
                            <div class="card-tools">
                                <a href="{{ route('admin.investment.list') }}" class="link">{{ __("View All") }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="nk-tb-list">
                        <div class="nk-tb-item nk-tb-head">
                            <div class="nk-tb-col"><span>{{ __("Plan") }}</span></div>
                            <div class="nk-tb-col tb-col-sm"><span>{{ __("Investor") }}</span></div>
                            <div class="nk-tb-col tb-col-lg"><span>{{ __("Date") }}</span></div>
                            <div class="nk-tb-col"><span>{{ __("Amount") }}</span></div>
                            <div class="nk-tb-col tb-col-sm"><span>&nbsp;</span></div>
                            <div class="nk-tb-col"><span>&nbsp;</span></div>
                        </div>
                        @if(filled($recent))
                        @foreach ($recent as $iv)
                        <div class="nk-tb-item">
                            <div class="nk-tb-col">
                                <div class="align-center">
                                    <div class="user-avatar user-avatar-sm bg-light">
                                        <span>{{ $iv->code }}</span>
                                    </div>
                                    <span class="tb-sub ml-2">{{ $iv->scheme['name'] }}
                                    <span class="d-none d-md-inline">- {{ $iv->calc_details_alter }}</span></span>
                                </div>
                            </div>
                            <div class="nk-tb-col tb-col-sm">
                                <div class="user-card">
                                    {!! user_avatar($iv->user, 'xs') !!}
                                    <div class="user-name">
                                        <span class="tb-lead">{{ $iv->user->name }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="nk-tb-col tb-col-lg">
                                <span class="tb-sub">{{ show_date($iv->order_at) }}</span>
                            </div>
                            <div class="nk-tb-col">
                                <span class="tb-sub tb-amount">{{ $iv->total }}
                                <span>{{ $currency }}</span></span>
                            </div>
                            <div class="nk-tb-col tb-col-sm">
                                @if($iv->status === InvestmentStatus::ACTIVE)
                                <div class="progress progress-sm w-80px">
                                    <div class="progress-bar" data-progress="{{ $iv->progress }}"></div>
                                </div>
                                @elseif($iv->status === InvestmentStatus::PENDING)
                                    <span class="badge badge-dim badge-warning">{{ __("Pending") }}</span>
                                @elseif($iv->status === InvestmentStatus::COMPLETED)
                                    <span class="badge badge-dim badge-success">{{ __("Completed") }}</span>
                                @elseif($iv->status === InvestmentStatus::CANCELLED)
                                    <span class="badge badge-dim badge-danger">{{ __("Cancelled") }}</span>
                                @endif
                            </div>
                            <div class="nk-tb-col nk-tb-col-action">
                                <a class="text-soft btn btn-sm btn-icon btn-trigger" href="{{ route('admin.investment.details', the_hash($iv->id)) }}">
                                    <em class="icon ni ni-chevron-right"></em>
                                </a>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <div class="no-result py-1 px-4 d-block">
                            <span class="text-soft font-italic small">{{ __("No recent investment available!") }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
@if ($renderIvGraph || $renderTopSchemeGraph || !empty($plans))
<script type="text/javascript">
    @if ($renderIvGraph)
    var activeInvestment = {
        tooltip: true,
        tooltipStyle: 'single',
        labels: @json(array_keys($ivGraph)),
        dataUnit: "{{ $currency }}",
        lineTension: .3,
        datasets: [{ label: "{{ __("Invest") }}", color: "#798bff", background: 'solid', borderWidth: 2, data: @json(array_values($ivGraph)) }],
    };
    @endif

    @if ($renderTopSchemeGraph)
    var topInvestPlan = {   
        tooltip: true,
        tooltipStyle: 'single',
        legend: false,
        labels: @json(array_keys($topSchemeGraph)),
        dataUnit: 'Plan',
        showDots: false,
        stacked: false,
        lineTension: .5,
        datasets: [{ label: "{{ __("Invested") }}", color: "#816bff", background: 'gradient', borderWidth: 3, data: @json(array_values($topSchemeGraph)) }]
    };
    @endif

    @if (!empty($plans))
    var activeInvestPlans = {
        labels: @json($plans["name"]),
        dataUnit: 'Plan',
        legend: false,
        datasets: [{ borderColor: '#fff', background: @json($plans["colors"]), data: @json($plans["data"]) }]
    };
    @endif
</script>
@endif
<script src="{{asset('assets/js/charts.js')}}"></script>
@endpush
