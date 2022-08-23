@extends('user.layouts.master')

@section('title', __('Investment Transactions'))

@section('content')
<div class="nk-content-body">
    <div class="nk-block-head">
        <div class="nk-block-head-sub">
            <a href="{{ route('user.investment.dashboard') }}" class="text-soft back-to"><em class="icon ni ni-arrow-left"> </em><span>{{ __("Investment") }}</span></a>
        </div>
        <div class="nk-block-between-sm g-4">
            <div class="nk-block-head-content">
                <h2 class="nk-block-title fw-normal">{{ __("Investment Transactions") }}</h2>
                <div class="nk-block-des">
                    <p>{{ __("List of investment related transactions in your account.") }}</p>
                </div>
            </div>
            <div class="nk-block-head-content">
                <ul class="nk-block-tools gx-3">
                    <li class="order-md-last"><a href="{{ route('user.investment.invest') }}" class="btn btn-primary"><span>{{ __('Invest & Earn') }}</span> <em class="icon ni ni-arrow-long-right"></em></a></li>
                </ul>
            </div>
        </div>
    </div>

    <ul class="nk-nav nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link{{ ($type == 'all') ? ' active' : '' }}" href="{{ route('user.investment.transactions') }}">{{ __("History") }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ ($type == 'profit') ? ' active' : '' }}" href="{{ route('user.investment.transactions', ['type' => 'profit']) }}">{{ __("Profit") }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ ($type == 'transfer') ? ' active' : '' }}" href="{{ route('user.investment.transactions', ['type' => 'transfer']) }}">{{ __("Transfered") }}</a>
        </li>
    </ul>
    <div class="nk-block nk-block-xs">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h6 class="nk-block-title">{{ __('All Transaction') }}</h6>
                </div>
                <ul class="nk-block-tools gx-2">
                    <li>
                        <a href="#" class="search-toggle toggle-search btn btn-icon btn-trigger" data-target="search"><em class="icon ni ni-search"></em></a>
                    </li>
                    <li>
                        <div class="dropdown">
                            <a href="#" class="btn btn-trigger btn-icon dropdown-toggle" data-toggle="dropdown">
                                @if($filter_count)
                                    <div class="badge badge-circle badge-primary">{{ $filter_count }}</div>
                                @endif
                                <em class="icon ni ni-filter-alt"></em>
                            </a>
                            </a>
                            <div class="filter-wg dropdown-menu dropdown-menu-xl dropdown-menu-right">
                                <div class="dropdown-head">
                                    <span class="sub-title dropdown-title">{{ __('Advance Filter') }}</span>
                                </div>
                                <form action="{{ route('user.investment.transactions') }}" method="GET">
                                    <input type="hidden" name="filter" value="true">
                                    <div class="dropdown-body dropdown-body-rg">
                                        <div class="row gx-6 gy-3">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label
                                                        class="overline-title overline-title-alt">{{ __('Type') }}</label>
                                                    <select name="type" class="form-select form-select-sm">
                                                        <option value="any">{{ __("Any Type") }}</option>
                                                        @foreach($ledgers as $type)
                                                            <option{{ (request()->get('type') == $type) ? ' selected' : '' }} value="{{ $type }}">
                                                            {{ ucfirst(__($type)) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label
                                                        class="overline-title overline-title-alt">{{ __('Source') }}</label>
                                                    <select name="source" class="form-select form-select-sm">
                                                        <option value="any">{{ __("Any Source") }}</option>
                                                        @foreach($sources as $source)
                                                            <option{{ (request()->get('source') == $source) ? ' selected' : '' }} value="{{ $source }}">
                                                            {{ w2n($source) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dropdown-foot between">
                                        <button type="submit" class="btn btn-secondary">{{ __('Filter') }}</button>
                                        <a href="{{ route('user.investment.transactions') }}" class="clickable">{{ __('Reset Filter') }}</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="dropdown">
                            <a class="dropdown-toggle btn btn-icon btn-trigger mx-n1" data-toggle="dropdown"
                               data-offset="-8,0" aria-expanded="false"><em class="icon ni ni-setting"></em></a>
                            <div class="dropdown-menu dropdown-menu-xs dropdown-menu-right" style="">
                                <ul class="link-check">
                                    <li><span>{{ __('Show') }}</span></li>
                                    @foreach(config('investorm.pgtn_pr_pg') as $item)
                                        <li class="update-meta{{ (user_meta('iv_tnx_perpage', '10') == $item) ? ' active' : '' }}">
                                            <a href="#" data-value="{{ $item }}" data-meta="perpage" data-type="iv_tnx">{{ $item }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                                <ul class="link-check">
                                    <li><span>{{ __('Density') }}</span></li>
                                    @foreach(config('investorm.pgtn_dnsty') as $item)
                                        <li class="update-meta{{ (user_meta('iv_tnx_display', 'regular') == $item) ? ' active' : '' }}">
                                            <a href="#" data-value="{{ $item }}" data-meta="display" data-type="iv_tnx">{{ __(ucfirst($item)) }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <form action="{{ url()->current() }}" method="GET">
                <div class="search-wrap search-wrap-extend bg-lighter{{ (request()->get('query')) ? ' active' : '' }}" data-search="search">
                    <div class="search-content">
                        <a href="{{ url()->current() }}" class="search-back btn btn-icon toggle-search" data-target="search"><em class="icon ni ni-arrow-left"></em></a>
                        <input type="text" name="query" value="{{ request()->get('query') }}" class="form-control border-transparent form-focus-none" placeholder="{{ __("Search by transaction id") }}">
                        <button class="search-submit btn btn-icon mr-1"><em class="icon ni ni-search"></em></button>
                    </div>
                </div>
            </form>
        </div>

        @if(filled($transactions))
        <div class="nk-odr-list is-stretch card card-bordered{{ user_meta('iv_tnx_display') == 'compact' ? ' is-compact': '' }}">
            @foreach($transactions as $transaction)
                <div class="nk-odr-item">
                    <div class="nk-odr-col">
                        <div class="nk-odr-info">
                            <div class="nk-odr-badge">
                                @if(data_get($transaction, 'type') == 'capital')
                                    <span class="nk-odr-icon bg-primary-dim text-secondary"><em class="icon ni ni-coin-alt"></em></span>
                                @elseif(data_get($transaction, 'type') == 'profit')
                                    <span class="nk-odr-icon bg-secondary-dim text-secondary"><em class="icon ni ni-percent"></em></span>
                                @elseif(data_get($transaction, 'type') == 'transfer')
                                    <span class="nk-odr-icon bg-purple-dim text-purple"><em class="icon ni ni-exchange"></em></span>
                                @elseif(data_get($transaction, 'type') == 'invest')
                                    <span class="nk-odr-icon bg-danger-dim text-danger"><em class="icon ni ni-coin-alt"></em></span>
                                @elseif(data_get($transaction, 'type') == 'loss')
                                    <span class="nk-odr-icon bg-danger-dim text-danger"><em class="icon ni ni-exchange"></em></span>
                                @elseif(data_get($transaction, 'type') == 'penalty')
                                    <span class="nk-odr-icon bg-danger-dim text-danger"><em class="icon ni ni-exchange"></em></span>
                                @endif
                            </div>
                            <div class="nk-odr-data">
                                <div class="nk-odr-label">
                                    <strong class="ellipsis">
                                    @if(data_get($transaction, 'type') == 'capital')
                                        {{ __('Received Invested Capital') }}
                                    @elseif(data_get($transaction, 'type') == 'profit')
                                        @if(!$transaction->is_manual)
                                            {{ __('Profit Earned') }}
                                        @else
                                            {{ __(data_get($transaction, 'desc')) }}
                                        @endif
                                    @elseif(data_get($transaction, 'type') == 'transfer')
                                        {{ __('Transfered Funds') }}
                                    @elseif(data_get($transaction, 'type') == 'penalty')
                                        @if(!$transaction->is_manual)
                                            {{ __('Penalty Added') }}
                                        @else
                                            {{ __(data_get($transaction, 'desc')) }}
                                        @endif
                                    @elseif(data_get($transaction, 'type') == 'loss')
                                        @if(!$transaction->is_manual)
                                            {{ __('Loss Added') }}
                                        @else
                                            {{ __(data_get($transaction, 'desc')) }}
                                        @endif
                                    @elseif(data_get($transaction, 'type') == 'invest')
                                        {{ __('Invest on :scheme', ['scheme' => data_get($transaction, 'invest.scheme.name')]) }}
                                    @endif
                                    </strong>
                                </div>
                                <div class="nk-odr-meta">
                                    <span class="meta">{{ the_tnx(data_get($transaction, 'ivx'), 'ivx') }}</span>
                                    <span class="meta dot-join d-none d-sm-inline-block">{{ w2n(data_get($transaction, 'source')) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="nk-odr-col nk-odr-col-amount">
                        <div class="nk-odr-amount">
                            <div class="number-md">
                                {{ (data_get($transaction, 'calc') == 'credit') ? '+' : '-'  }}
                                {{ money(data_get($transaction, 'total'), base_currency()) }} <span class="currency">{{ base_currency() }}</span>
                            </div>
                            <div class="number-sm">{{ money(base_to_secondary(data_get($transaction, 'total')), secondary_currency()) }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @if(filled($transactions) && $transactions->hasPages())
        <div class="mt-4">
            {{ $transactions->appends(request()->all())->links('misc.pagination') }}
        </div>
        @endif
        @else
            <div class="alert alert-primary">
                <div class="alert-cta flex-wrap flex-md-nowrap">
                    <div class="alert-text">
                        <p>{{ __('Investment transaction not available.') }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('modal')
<div class="modal fade" role="dialog" id="ajax-modal"></div>
@endpush
