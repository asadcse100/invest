@extends('admin.layouts.master')

@section('title', __('Admin Dashboard'))

@php

use App\Enums\TransactionCalcType;
use App\Enums\TransactionType;

$currency = base_currency();

@endphp

@section('content')
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between g-3">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">{{ __("Overview") }}</h3>
                    <div class="nk-block-des text-soft">
                        <p>{{ __("Here is an insight of what's going on.") }}</p>
                    </div>
                </div>
                <div class="nk-block-head-content">
                    <a href="{{ route('admin.transactions.list') }}" class="btn btn-primary d-none d-sm-inline-flex"><em class="icon ni ni-tranx"></em> <span>{{ __("View Transaction") }}</span></a>
                    <a href="{{ route('admin.transactions.list') }}" class="btn btn-icon btn-primary d-inline-flex d-sm-none"><em class="icon ni ni-tranx"></em></a>
                </div>
            </div>
        </div>
        @if (!count(available_payment_methods()) > 0 || count($pending) > 0 || is_demo())
        <div class="nk-block">
            @if(is_demo())
            <div class="alert alert-danger bg-white alert-outline{{ (!count(available_payment_methods()) > 0 || count($pending)) ? ' mb-2' : '' }}">
                {!! 'All the additional <span class="badge badge-pill badge-dark">Module</span> and <span class="badge badge-pill badge-danger">Add-ons</span> are NOT part of main product. Please feel free to <strong><a class="alert-link" href="'. the_link('softn' . 'io' .'.com' .'/'. 'contact'). '" target="_blank">contact us</a></strong> for more information or to get those.' !!}
            </div>
            @endif
            @if(!count(available_payment_methods())>0)
            <div class="alert alert-danger alert-icon">
                <em class="icon ni ni-alert"></em>
                <strong>{!! __("Important: Please setup at least one :link to receive payments.", ['link' => '<a href="'.route('admin.settings.gateway.payment.list').'" class="alert-link">'.__("payment method").'</a>']) !!}</strong>
            </div>
            @endif
            @if(count($pending)>0)
            <div class="nk-alert-action">
                <div class="nk-alert-message">
                    <div class="nk-alert-icon"><em class="icon ni ni-info"></em></div>
                    <div class="nk-alert-text"><span>{{ __("Attention:") }}</span> {{ __("You have few pending request, that need to review.") }}</div>
                </div>
                <div class="blank-sp d-none d-md-inline-flex"> &nbsp; </div>
                <div class="nk-alert-nav">
                    <ul class="nk-alert-links gx-4">
                    @foreach ($pending as $type)
                        <li><a href="{{ route('admin.transactions.list', $type->type) }}">{{ $type->total }} {{ strtoupper($type->type) }}</a></li>
                    @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>
        @endif
        <div class="nk-block">
            <div class="row g-gs">
                <div class="col-lg-8">
                    <div class="card card-bordered">
                        <div class="card-inner">
                            <div class="card-title-group align-start mb-3">
                                <div class="card-title">
                                    <h6 class="title">{{ __("Daily Insight") }}</h6>
                                    <p>{{ __("Daywise overall deposit & withdraw.") }}</p>
                                </div>
                                <div class="card-tools mt-n1 mr-n1">
                                    <div class="drodown">
                                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                            <ul class="link-list-opt no-bdr">
                                                <li><a href="{{route('admin.dashboard') }}?days=15"
                                                @if((request()->has('days') && request()->days==15) ||!request()->has('days')) class="active" @endif
                                                ><span>{{ __("15 Days") }}</span></a></li>
                                                <li><a href="{{route('admin.dashboard') }}?days=30"
                                                @if(request()->has('days') && request()->days==30) class="active" @endif
                                                ><span>{{ __("30 Days") }}</span></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>{{-- .card-title-group --}}
                            <div class="nk-insight">
                                <div class="row g-4 align-end">
                                    <div class="col-xxl-8">
                                        <div class="nk-insight-ck">
                                            <canvas class="chart-insight" id="inoutOverview"></canvas>
                                        </div>
                                    </div>{{-- .col --}}
                                    <div class="col-xxl-4">
                                        <div class="row g-4">
                                            <div class="col-sm-6 col-xxl-12">
                                                <div class="nk-insight-data payin">
                                                    <div class="amount">{{ to_amount($deposit['total'], $currency) }} <small class="currency">{{ $currency }}</small></div>
                                                    <div class="info">{{ __("Last month") }} <strong>{{ to_amount($deposit['last_month'], $currency) }} <span class="currency">{{ $currency }}</span></strong></div>
                                                    <div class="title"><em class="icon ni ni-arrow-down-left"></em> {{ __("Deposit") }}</div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-xxl-12">
                                                <div class="nk-insight-data payout">
                                                    <div class="amount">{{ to_amount($withdraw['total'], $currency) }} <small class="currency">{{ $currency }}</small></div>
                                                    <div class="info">{{ __("Last month") }} <strong> {{ to_amount($withdraw['last_month'], $currency) }} <span class="currency">{{ $currency }}</span></strong></div>
                                                    <div class="title"><em class="icon ni ni-arrow-up-right"></em> {{ __("Withdraw") }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>{{-- .col --}}
                                </div>
                            </div>{{-- .nk-insight --}}
                        </div>{{-- .card-inner --}}
                    </div>{{-- .card --}}
                </div>{{-- .col --}}
                <div class="col-lg-4">
                    <div class="row g-gs">
                        <div class="col-md-6 col-lg-12">
                            <div class="card card-bordered">
                                <div class="card-inner">
                                    <div class="card-title-group align-start mb-0">
                                        <div class="card-title">
                                            <h6 class="subtitle text-base">{{ __("Total Deposit") }}</h6>
                                        </div>
                                        <div class="card-tools">
                                            <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left" title="{{ __("The total amount of deposit all the time.") }}"></em>
                                        </div>
                                    </div>
                                    <div class="card-amount">
                                        <span class="amount"> {{ to_amount($deposit['total'], $currency) }} <span class="currency">{{ $currency }}</span>
                                        </span>
                                    </div>
                                    <div class="card-stats">
                                        <div class="card-stats-group g-2">
                                            <div class="card-stats-data">
                                                <div class="title">{{ __("This Month") }}</div>
                                                <div class="amount"> {{ to_amount($deposit['this_month'], $currency) }}
                                                    @if($deposit['prtc_monthly'] > 0)
                                                        <span class="change up tipinfo" title="{{ __("Than last month") }}">
                                                        <em class="icon ni ni-arrow-long-up"></em>{{ abs($deposit['prtc_monthly']) }}%</span>
                                                    @elseif($deposit['prtc_monthly'] < 0)
                                                        <span class="change down tipinfo" title="{{ __("Than last month") }}">
                                                        <em class="icon ni ni-arrow-long-down"></em>{{ abs($deposit['prtc_monthly']) }}%</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="card-stats-data">
                                                <div class="title">{{ __("This Week") }}</div>
                                                <div class="amount">  {{ to_amount($deposit['this_week'], $currency) }}
                                                    @if($deposit['prtc_weekly'] > 0)
                                                        <span class="change up tipinfo" title="{{ __("Than last month") }}">
                                                        <em class="icon ni ni-arrow-long-up"></em>{{ abs($deposit['prtc_weekly']) }}%</span>
                                                    @elseif($deposit['prtc_weekly'] < 0)
                                                        <span class="change down tipinfo" title="{{ __("Than last month") }}">
                                                        <em class="icon ni ni-arrow-long-down"></em>{{ abs($deposit['prtc_weekly']) }}%</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-stats-ck">
                                            <canvas class="chart-liner" id="totalDeposit"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-12">
                            <div class="card card-bordered">
                                <div class="card-inner">
                                    <div class="card-title-group align-start mb-0">
                                        <div class="card-title">
                                            <h6 class="subtitle text-base">{{ __("Total Withdraw") }}</h6>
                                        </div>
                                        <div class="card-tools">
                                            <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left" title="{{ __("The total amount of withdraw all the time.") }}"></em>
                                        </div>
                                    </div>
                                    <div class="card-amount">
                                        <span class="amount"> {{ to_amount($withdraw['total'], $currency) }} <span class="currency">{{ $currency }}</span>
                                        </span>
                                    </div>
                                    <div class="card-stats">
                                        <div class="card-stats-group g-2">
                                            <div class="card-stats-data">
                                                <div class="title">{{ __("This Month") }}</div>
                                                <div class="amount"> {{ to_amount($withdraw['this_month'], $currency) }}
                                                    @if($withdraw['prtc_monthly'] > 0)
                                                        <span class="change up tipinfo" title="{{ __("Than last month") }}">
                                                        <em class="icon ni ni-arrow-long-up"></em>{{ abs($withdraw['prtc_monthly']) }}%</span>
                                                    @elseif($withdraw['prtc_monthly'] < 0)
                                                        <span class="change down tipinfo" title="{{ __("Than last month") }}">
                                                        <em class="icon ni ni-arrow-long-down"></em>{{ abs($withdraw['prtc_monthly']) }}%</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="card-stats-data">
                                                <div class="title">{{ __("This Week") }}</div>
                                                <div class="amount"> {{ to_amount($withdraw['this_week'], $currency) }}
                                                    @if($withdraw['prtc_weekly'] > 0)
                                                        <span class="change up tipinfo" title="{{ __("Than last month") }}">
                                                        <em class="icon ni ni-arrow-long-up"></em>{{ abs($withdraw['prtc_weekly']) }}%</span>
                                                    @elseif($withdraw['prtc_weekly'] < 0)
                                                        <span class="change down tipinfo" title="{{ __("Than last month") }}">
                                                        <em class="icon ni ni-arrow-long-down"></em>{{ abs($withdraw['prtc_weekly']) }}%</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-stats-ck">
                                            <canvas class="chart-liner" id="totalWithdraw"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>{{-- .card --}}
                        </div>{{-- .col --}}
                    </div>
                </div>{{-- .col --}}
                <div class="col-md-6 col-xxl-4">
                    <div class="card card-bordered card-full">
                        <div class="card-inner border-bottom">
                            <div class="card-title-group">
                                <div class="card-title">
                                    <h6 class="title">{{ __("Transactions") }}</h6>
                                </div>
                                <div class="card-tools">
                                    <ul class="card-tools-nav nav">
                                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tnxin"><span>{{ __("In") }}</span></a></li>
                                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tnxout"><span>{{ __("Out") }}</span></a></li>
                                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tnxall"><span>{{ __("All") }}</span></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="tab-content mt-0">
                            <div class="tab-pane active" id="tnxall">
                                <div class="nk-tnx-pro is-scrollable h-425px" data-simplebar>
                                    @if(filled($transactions['all_tnx']))
                                        @foreach ($transactions['all_tnx'] as $tnx)
                                        @if($tnx->calc===TransactionCalcType::CREDIT)
                                        <div class="nk-tnx-pro-item">
                                            <div class="nk-tnx-pro-col">
                                                <ul class="icon-overlap-alt">
                                                    <li><em class="bg-success-dim icon-circle icon ni ni-arrow-down-left"></em></li>
                                                    <li><em class="bg-white icon-circle md icon ni ni-sign-{{ strtolower($tnx->tnx_currency) }}"></em></li>
                                                </ul>
                                                <div class="nk-tnx-pro-data">
                                                    <div class="label">{{ $tnx->type_of_fund }}</div>
                                                    <div class="date">{{ show_date($tnx->completed_at) }} <span class="d-none d-sm-inline">{{ show_time($tnx->completed_at) }}</span></div>
                                                </div>
                                            </div>
                                            <div class="nk-tnx-pro-col">
                                                <div class="nk-tnx-pro-amount">
                                                    <div class="amount">+ {{ to_amount($tnx->tnx_amount, $tnx->tnx_currency) }} <span class="currency">{{ $tnx->tnx_currency}}</span></div>
                                                    <div class="amount-sm up">+ {{ to_amount($tnx->amount, $tnx->currency) }} <span class="currency">{{ $tnx->currency}}</span></div>
                                                </div>
                                            </div>
                                        </div>{{-- .pro-item --}}
                                        @elseif($tnx->calc===TransactionCalcType::DEBIT)
                                        <div class="nk-tnx-pro-item">
                                            <div class="nk-tnx-pro-col">
                                                <ul class="icon-overlap-alt">
                                                    <li><em class="bg-warning-dim icon-circle icon ni ni-arrow-up-right"></em></li>
                                                    <li><em class="bg-white icon-circle md icon ni ni-sign-{{ strtolower($tnx->tnx_currency) }}"></em></li>
                                                </ul>
                                                <div class="nk-tnx-pro-data">
                                                    <div class="label">{{ $tnx->type_of_fund }}</div>
                                                    <div class="date">{{ show_date($tnx->completed_at) }} <span class="d-none d-sm-inline">{{ show_time($tnx->completed_at,true) }}</span></div>
                                                </div>
                                            </div>
                                            <div class="nk-tnx-pro-col">
                                                <div class="nk-tnx-pro-amount">
                                                    <div class="amount">- {{ to_amount($tnx->tnx_amount, $tnx->tnx_currency) }} <span class="currency"> {{ $tnx->tnx_currency}}</span></div>
                                                    <div class="amount-sm down">- {{ to_amount($tnx->amount, $tnx->currency) }} <span class="currency">{{ $tnx->currency}}</span></div>
                                                </div>
                                            </div>
                                        </div>{{-- .pro-item --}}
                                        @endif
                                        @endforeach
                                    @else
                                        <div class="no-result py-5 px-1 text-center d-block">
                                            <span class="icon-circle icon-circle-lg icon-lg text-gray bg-white"><em class="icon ni ni-info"></em></span>
                                            <span class="d-block font-italic small">{{ __("No transaction available!") }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="tab-pane" id="tnxout">
                                <div class="nk-tnx-pro is-scrollable h-425px" data-simplebar>
                                    @if(filled($transactions['debits']))
                                        @foreach ($transactions['debits'] as $debit)
                                        <div class="nk-tnx-pro-item">
                                            <div class="nk-tnx-pro-col">
                                                <ul class="icon-overlap-alt">
                                                    <li><em class="bg-warning-dim icon-circle icon ni ni-arrow-up-right"></em></li>
                                                    <li><em class="bg-white icon-circle md icon ni ni-sign-{{ strtolower($debit->tnx_currency) }}"></em></li>
                                                </ul>
                                                <div class="nk-tnx-pro-data">
                                                    <div class="label">{{ $debit->type_of_fund }}</div>
                                                    <div class="date">{{ show_date($debit->completed_at) }} <span class="d-none d-sm-inline">{{ show_time($debit->completed_at) }}</span></div>
                                                </div>
                                            </div>
                                            <div class="nk-tnx-pro-col">
                                                <div class="nk-tnx-pro-amount">
                                                    <div class="amount">- {{ to_amount($debit->tnx_amount, $debit->tnx_currency) }} <span class="currency">{{ $debit->tnx_currency}}</span></div>
                                                    <div class="amount-sm down">- {{ to_amount($debit->amount, $currency) }} <span class="currency">{{ $debit->currency}}</span></div>
                                                </div>
                                            </div>
                                        </div>{{-- .pro-item --}}
                                        @endforeach
                                    @else
                                        <div class="no-result py-5 px-1 text-center d-block">
                                            <span class="icon-circle icon-circle-lg icon-lg text-gray bg-white"><em class="icon ni ni-info"></em></span>
                                            <span class="d-block font-italic small">{{ __("No transaction available!") }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="tab-pane" id="tnxin">
                                <div class="nk-tnx-pro is-scrollable h-425px" data-simplebar>
                                @if(filled($transactions['credits']))
                                    @foreach ($transactions['credits'] as $credit)
                                    <div class="nk-tnx-pro-item">
                                        <div class="nk-tnx-pro-col">
                                            <ul class="icon-overlap-alt">
                                                <li><em class="bg-success-dim icon-circle icon ni ni-arrow-down-left"></em></li>
                                                <li><em class="bg-white icon-circle md icon ni ni-sign-{{ strtolower($credit->tnx_currency) }}"></em></li>
                                            </ul>
                                            <div class="nk-tnx-pro-data">
                                                <div class="label">{{ $credit->type_of_fund }}</div>
                                                <div class="date">{{ show_date($credit->completed_at) }} <span class="d-none d-sm-inline">{{ show_time($credit->completed_at) }}</span></div>
                                            </div>
                                        </div>
                                        <div class="nk-tnx-pro-col">
                                            <div class="nk-tnx-pro-amount">
                                                <div class="amount">+ {{ to_amount($credit->tnx_amount, $credit->tnx_currency) }} <span class="currency">{{ $credit->tnx_currency}}</span></div>
                                                <div class="amount-sm up">+ {{ to_amount($credit->amount, $credit->currency) }} <span class="currency">{{ $credit->currency}}</span></div>
                                            </div>
                                        </div>
                                    </div>{{-- .pro-item --}}
                                    @endforeach
                                @else
                                    <div class="no-result py-5 px-1 text-center d-block">
                                        <span class="icon-circle icon-circle-lg icon-lg text-gray bg-white"><em class="icon ni ni-info"></em></span>
                                        <span class="d-block font-italic small">{{ __("No transaction available!") }}</span>
                                    </div>
                                @endif
                                </div>
                            </div>
                        </div>
                    </div>{{-- .card --}}
                </div>{{-- .col --}}
                <div class="col-md-6 col-xxl-4">
                    <div class="card card-bordered card-full">
                        <div class="card-inner">
                            <div class="card-title-group">
                                <div class="card-title">
                                    <h6 class="title">{{__("Investment Activities") }}</h6>
                                </div>
                                <div class="card-tools">
                                    <a href="{{route('admin.investment.list') }}" class="link">{{__("All investments") }}</a>
                                </div>
                            </div>
                        </div>{{-- .card-inner --}}
                        <div class="card-inner p-0 border-top">
                            <div class="nk-olistr is-scrollable h-425px" data-simplebar>
                                <div class="nk-tb-list nk-tb-orders">
                                @if(filled($transactions['investments']))
                                    @foreach ($transactions['investments'] as $iv)
                                    <div class="nk-tb-item">
                                        <div class="nk-tb-col nk-tb-orders-type">
                                            <ul class="user-avatar user-avatar-sm bg-light">
                                                <span>{{ strtoupper(substr($iv->scheme['short'], 0, 2)) }}</span>
                                            </ul>
                                        </div>
                                        <div class="nk-tb-col">
                                            <span class="tb-lead">{{ $iv->scheme['name'] }} <span class="d-none d-md-inline">- {{ $iv->calc_details_alter }}</span></span>
                                            <div class="date">{{ show_date($iv->created_at) }} <span class="d-none d-sm-inline">{{ show_time($iv->created) }}</span></div>
                                        </div>
                                        <div class="nk-tb-col text-right">
                                            <span class="tb-amount-lg">+ {{ to_amount($iv->amount, $currency) }} <span class="currency">{{ $currency }}</span></span>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="no-result py-5 px-1 text-center d-block">
                                        <span class="icon-circle icon-circle-lg icon-lg text-gray bg-white"><em class="icon ni ni-info"></em></span>
                                        <span class="d-block font-italic small">{{ __("No activities available!") }}</span>
                                    </div>
                                @endif
                                </div>
                            </div>
                        </div>{{-- .card-inner --}}
                    </div>{{-- .card --}}
                </div>{{-- .col --}}
                <div class="col-md-12 col-xxl-4">
                    <div class="row g-gs">
                        <div class="col-md-6 col-xxl-12">
                            <div class="card card-bordered">
                                <div class="card-inner">
                                    <div class="card-title-group align-start mb-3">
                                        <div class="card-title">
                                            <h6 class="title">{{ __("User Activities") }}</h6>
                                            <p>{{ __("In last 30 days") }} <em class="icon ni ni-info" data-toggle="tooltip" data-placement="right" title="Signup Activities"></em></p>
                                        </div>
                                    </div>
                                    <div class="join-insight-group g-4">
                                        <div class="join-insight">
                                            <em class="icon ni ni-users"></em>
                                            <div class="info">
                                                <span class="amount">{{ $stats['this_month'] }}</span>
                                                <span class="title">{{ __("Direct Join") }}</span>
                                            </div>
                                        </div>
                                        <div class="join-insight">
                                            <em class="icon ni ni-share"></em>
                                            <div class="info">
                                                <span class="amount">{{ $stats['ref_count'] }}</span>
                                                <span class="title">{{ __("Referral Join") }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="join-insight-ck">
                                    <canvas class="chart-bar" id="userStats"></canvas>
                                </div>
                            </div>{{-- .card --}}
                        </div>{{-- .col --}}
                        <div class="col-md-6 col-xxl-12">
                            <div class="card card-bordered">
                                <div class="card-inner">
                                    <div class="card-title-group align-start mb-3">
                                        <div class="card-title">
                                            <h6 class="title">{{ __("Total Stats") }}</h6>
                                        </div>
                                        <div class="card-tools">
                                        </div>
                                        <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left" title="Total statistics number."></em>
                                    </div>
                                    <div class="nk-ovb">
                                        <div class="nk-ovb-data-group g-4">
                                            <div class="nk-ovb-data">
                                                <div class="title"><span class="dot dot-lg sq" data-bg="#f7bf90"></span><span>{{ __("Deposits") }}</span></div>
                                                <div class="amount">{{ $transactions['dp_count'] }}</div>
                                                <div class="amount-sm">{{ $transactions['dp_since'] }} <small>{{ __("since last month") }}</small></div>
                                            </div>
                                            <div class="nk-ovb-data">
                                                <div class="title"><span class="dot dot-lg sq" data-bg="#ffa9ce"></span><span>{{ __("Withdraws") }}</span></div>
                                                <div class="amount">{{ $transactions['wd_count'] }}</div>
                                                <div class="amount-sm">{{ $transactions['wd_since'] }} <small>{{ __("since last month") }}</small></div>
                                            </div>
                                            <div class="nk-ovb-data">
                                                <div class="title"><span class="dot dot-lg sq" data-bg="#b8acff"></span><span>{{ __("Transactions") }}</span></div>
                                                <div class="amount">{{ $transactions['tnx_count'] }}</div>
                                                <div class="amount-sm">{{ $transactions['tnx_since'] }} <small>{{ __("since last month") }}</small></div>
                                            </div>
                                            <div class="nk-ovb-data">
                                                <div class="title"><span class="dot dot-lg sq" data-bg="#9cabff"></span><span>{{ __("Users") }}</span></div>
                                                <div class="amount">{{ $stats['user_count'] }}</div>
                                                <div class="amount-sm">{{ $stats['this_month'] }} <small>{{ __("since last month") }}</small></div>
                                            </div>
                                        </div>
                                    </div>{{-- .nk-ovb --}}
                                </div>
                            </div>{{-- .card --}}
                        </div>{{-- .col --}}
                    </div>{{-- .row --}}
                </div>{{-- .col --}}
            </div>
        </div>
    </div>
@endsection
@push('scripts')
@php
    $inout = array_merge(array_values($dailyInsights['deposit']), array_values($dailyInsights['withdraw']));
    $minio = array_filter($inout, function($item){ return $item > 0; });
    $max = max($inout);
    $min = (is_array($minio) && !empty($minio)) ? (min($minio) === $max ? 0 : min($minio)) : 0;
@endphp
<script>
    var totalDeposit = {
        tooltip: true, tooltipStyle: 'single', dataUnit : '{{ $currency }}', stacked : false, labels : @json(array_keys($dailyInsights['deposit'])),
        datasets : [{ label : "{{ __("Deposit") }}", color : "#6576ff", background : 'gradient', borderWidth: 2, data: @json(array_values($dailyInsights['deposit'])) }]
    };

    var totalWithdraw = {
        tooltip: true, tooltipStyle: 'single', dataUnit: '{{ $currency }}', stacked: false, labels: @json(array_keys($dailyInsights['withdraw'])),
        datasets: [{ label: "{{ __("Withdraw") }}", color: "#e85347", background: 'gradient', borderWidth: 2, data: @json(array_values($dailyInsights['withdraw'])) }]
    };

    var inoutOverview = {
        tooltip: true, dataUnit: '{{ $currency }}', stacked: false, lineTension: 1, labels: @json(array_keys($dailyInsights['deposit'])),
        scales: { min: {{ $min }}, max: {{ $max }}, step: {{  round($max / 10, 0) }} },
        datasets: [
            { label: "{{ __("Deposit") }}", color: NioApp.hexRGB('#6576ff', 1), borderWidth: 3, data: @json(array_values($dailyInsights['deposit'])) },
            { label: "{{ __("Withdraw") }}", color: NioApp.hexRGB('#e85347', .6), borderWidth: 3, data: @json(array_values($dailyInsights['withdraw'])) }
        ]
    };

    var userStats = {
        tooltip: true, legend: false, dataUnit: '{{ __("Member") }}', stacked: true, lineTension: .4, labels: @json(array_keys($stats['directGraph'])),
        datasets: [
            { label: "{{ __("Direct") }}", color: "#798bff", data: @json(array_values($stats['directGraph'])) },
            { label: "{{ __("Referral") }}", color: "#ccd4ff", data: @json(array_values($stats['referralGraph'])) }
        ]
    };
</script>
<script src={{asset('assets/js/charts.js') }}></script>
@endpush
