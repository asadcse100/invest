@extends('user.layouts.master')

@section('title', __('Investment History'))

@section('content')
<div class="nk-content-body">
    <div class="nk-block-head">
        <div class="nk-block-head-sub">
            <a href="{{ route('user.investment.dashboard') }}" class="text-soft back-to"><em class="icon ni ni-arrow-left"> </em><span>{{ __("Investment") }}</span></a>
        </div>
        <div class="nk-block-between-sm g-4">
            <div class="nk-block-head-content">
                <h2 class="nk-block-title fw-normal">{{ __("Investment History") }}</h2>
                <div class="nk-block-des">
                    <p>{{ __("List of your investment plan that you have invested.") }}</p>
                </div>
            </div>
            <div class="nk-block-head-content">
                <ul class="nk-block-tools gx-3">
                    <li class="order-md-last"><a href="{{ route('user.investment.invest') }}" class="btn btn-primary"><span>{{ __('Invest More') }}</span> <em class="icon ni ni-arrow-long-right"></em></a></li>
                </ul>
            </div>
        </div>
    </div>

    <ul class="nk-nav nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link{{ ($listing == 'all' || empty($listing)) ? ' active' : '' }}" href="{{ route('user.investment.history') }}">{{ __("History") }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ ($listing == 'active') ? ' active' : '' }}" href="{{ route('user.investment.history', 'active') }}">{{ __("Active") }} <span class="badge badge-primary">{{ data_get($investCount, 'active') }}</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ ($listing == 'pending') ? ' active' : '' }}" href="{{ route('user.investment.history', 'pending') }}">{{ __("Pending") }} <span class="badge badge-primary">{{ data_get($investCount, 'pending') }}</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ ($listing == 'completed') ? ' active' : '' }}" href="{{ route('user.investment.history', 'completed') }}">{{ __("Completed") }}</a>
        </li>
    </ul>
    <div class="nk-block nk-block-xs">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h6 class="nk-block-title">{{ __("All Plans") }}</h6>
                </div>
                <ul class="nk-block-tools gx-3">
                    <li>
                        <a href="#" class="search-toggle toggle-search btn btn-icon btn-trigger" data-target="search"><em class="icon ni ni-search"></em></a>
                    </li>
                    <li>
                        <div class="dropdown">
                            <a class="dropdown-toggle btn btn-icon btn-trigger mx-n1" data-toggle="dropdown" data-offset="-8,0"><em class="icon ni ni-setting"></em></a>
                            <div class="dropdown-menu dropdown-menu-xs dropdown-menu-right">
                                <ul class="link-check">
                                    <li><span>{{ __('Show') }}</span></li>
                                    @foreach(config('investorm.pgtn_pr_pg') as $item)
                                        <li class="update-meta{{ (user_meta('iv_history_perpage', '10') == $item) ? ' active' : '' }}">
                                            <a href="#" data-value="{{ $item }}" data-meta="perpage" data-type="iv_history">{{ $item }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                                <ul class="link-check">
                                    <li><span>{{ __('Density') }}</span></li>
                                    @foreach(config('investorm.pgtn_dnsty') as $item)
                                        <li class="update-meta{{ (user_meta('iv_history_display', 'regular') == $item) ? ' active' : '' }}">
                                            <a href="#" data-value="{{ $item }}" data-meta="display" data-type="iv_history">{{ __(ucfirst($item)) }}</a>
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
                        <input type="text" name="query" value="{{ request()->get('query') }}" class="form-control border-transparent form-focus-none" placeholder="{{ __("Search by invest id") }}">
                        <button class="search-submit btn btn-icon mr-1"><em class="icon ni ni-search"></em></button>
                    </div>
                </div>
            </form>
        </div>

        @if(filled($investments))
            <div class="card card-bordered card-stretch">
                <table class="table table-plans{{ user_meta('iv_history_display') != 'compact' ? ' table-lg': '' }}">
                    <thead class="thead-white">
                        <tr>
                            <th class="tb-col fw-normal text-soft">{{ __("ID") }}</th>
                            <th class="tb-col fw-normal text-soft tb-col-sm">{{ __("Plan") }}</th>
                            <th class="tb-col fw-normal text-soft tb-col-lg">{{ __("Date") }}</th>
                            <th class="tb-col fw-normal text-soft">{{ __("Invested") }}</th>
                            <th class="tb-col fw-normal text-soft tb-col-md">{{ __("Received") }}</th>
                            <th class="tb-col fw-normal text-soft tb-col-mb">{{ __("Status") }}</th>
                            <th class="tb-col fw-normal text-soft tb-col-end align-middle">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($investments as $invest)
                        <tr>
                            <td class="tb-col"><a href="{{ route('user.investment.details', ['id' => the_hash($invest->id)]) }}" class="text-secondary fw-bold">{{ the_inv(data_get($invest, 'ivx')) }}</a></td>
                            <td class="tb-col tb-col-sm">{{ data_get($invest, 'scheme.name') }}</td>
                            <td class="tb-col tb-col-lg">{{ show_date(data_get($invest, 'term_start'), true) }}</td>
                            <td class="tb-col">{{ money(data_get($invest, 'amount'), data_get($invest, 'currency')) }}</td>
                            <td class="tb-col tb-col-md">{{ money(data_get($invest, 'received'), data_get($invest, 'currency')) }}</td>
                            <td class="tb-col tb-col-mb">
                                <span class="badge badge-dim {{ the_state(data_get($invest, 'status'), ['prefix' => 'badge']) }}">{{ __(ucfirst(data_get($invest, 'status'))) }}</span>
                            </td>
                            <td class="tb-col tb-col-end p-1 align-middle">
                                <a class="btn btn-icon btn-sm btn-round btn-trans" href="{{ route('user.investment.details', ['id' => the_hash($invest->id)]) }}"><em class="icon ni ni-chevron-right"></em></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if(filled($investments) && $investments->hasPages())
            <div class="card-inner pt-3 pb-3">
                {{ $investments->appends(request()->all())->links('misc.pagination') }}
            </div>
            @endif
        @else
            <div class="alert alert-primary">
                <div class="alert-cta flex-wrap flex-md-nowrap">
                    <div class="alert-text">
                        <p>{{ __('No investment plan found.') }}</p>
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
