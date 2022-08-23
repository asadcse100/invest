@extends('admin.layouts.master')
@section('title', __('Profits / Interests'))

@php

use App\Enums\TransactionCalcType;
use App\Enums\LedgerTnxType;

@endphp

@section('content')
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between gy-2 gx-3">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">{{ __('Profits / Interests') }}</h3>
                    <p>{!! __('Total :num entries.', ['num' => '<span class="text-base">'.$profits->total().'</span>' ]) !!}</p>
                </div>
                <div class="nk-block-head-content">
                    <ul class="nk-block-tools g-3">
                        <li>
                            <a href="{{ route('admin.investment.transactions.list') }}" class="btn btn-white btn-dim btn-outline-gray d-none d-sm-inline-flex"><em class="icon ni ni-report-profit"></em><span>{{ __("Statement") }}</span></a>
                            <a href="{{ route('admin.investment.transactions.list') }}" class="btn btn-icon btn-white btn-dim btn-outline-gray d-inline-flex d-sm-none"><em class="icon ni ni-report-profit"></em></a>
                        </li>
                        <li class="nk-block-tools">
                            <div class="dropdown">
                                <a class="dropdown-toggle btn btn-primary" data-toggle="dropdown"><em class="icon ni ni-invest"></em><span class="d-none d-sm-inline">{{ __("Process") }}</span></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <ul class="link-list-opt no-bdr">
                                        <li><a href="javascript:void(0)" data-action="plan" data-backdrop="static" class="m-ivs-process{{ (is_locked('plan')) ? ' disabled' : '' }}">
                                            <em class="icon ni ni-update"></em><span>{{ __('Sync Invested Plans') }}</span></a>
                                        </li>
                                        <li><a href="javascript:void(0)" data-action="profit" data-backdrop="static" class="m-ivs-process{{ (is_locked('profit')) ? ' disabled' : '' }}">
                                            <em class="icon ni ni-check-circle-cut"></em><span>{{ __('Approve the Profits') }}</span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <ul class="nk-nav nav nav-tabs mt-n3 mb-md-n3">
            <li class="nav-item">
                <a class="nav-link{{ (is_route('admin.investment.profits.list') && $type=='all') ? ' active' : '' }}" href="{{ route('admin.investment.profits.list') }}">
                    <span>{{ __('History') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link{{ (is_route('admin.investment.profits.list') && $type=='pending') ? ' active' : '' }}" href="{{ route('admin.investment.profits.list', 'pending') }}">
                    <span>{{ __('Scheduled') }}</span>
                </a>
            </li>
        </ul>

        <div class="nk-block">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h6 class="nk-block-title">{{ ($type=='pending') ? __('Outstanding Profits') : __(':Type Profits', ['type' => $type])  }}</h6>
                    </div>
                    <ul class="nk-block-tools gx-3">
                        <li>
                            <a href="#" class="search-toggle toggle-search btn btn-icon btn-trigger" data-target="search"><em class="icon ni ni-search"></em></a>
                        </li>
                        <li>
                            <div class="dropdown">
                                <a class="dropdown-toggle btn btn-icon btn-trigger mx-n1" data-toggle="dropdown" data-offset="-8,0" aria-expanded="false">
                                    <em class="icon ni ni-setting"></em>
                                </a>
                                <div class="dropdown-menu dropdown-menu-xs dropdown-menu-right" style="">
                                    <ul class="link-check">
                                        <li><span>{{ __('Show') }}</span></li>
                                        @foreach(config('investorm.pgtn_pr_pg') as $item)
                                        <li class="update-meta{{ (user_meta('iv_profit_perpage', '20') == $item) ? ' active' : '' }}">
                                            <a href="#" data-value="{{ $item }}" data-meta="perpage" data-type="iv_profit">{{ $item }}</a>
                                        </li>
                                        @endforeach
                                    </ul>
                                    <ul class="link-check">
                                        <li><span>{{ __('Order') }}</span></li>
                                        @foreach(config('investorm.pgtn_order') as $item)
                                        <li class="update-meta{{ (user_meta('iv_profit_order', 'desc') == $item) ? ' active' : '' }}">
                                            <a href="#" data-value="{{ $item }}" data-meta="order" data-type="iv_profit">{{ __(strtoupper($item)) }}</a>
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
                            <input type="text" name="query" value="{{ request()->get('query') }}" class="form-control border-transparent form-focus-none" placeholder="{{ __("Search by invested plan id") }}">
                            <button class="search-submit btn btn-icon mr-1"><em class="icon ni ni-search"></em></button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card card-bordered card-stretch">
                <div class="card-inner-group">
                    @if(filled($profits))
                    <div class="card-inner p-0">
                        <table class="nk-plan-tnx table">
                            <thead class="thead-light">
                            <tr>
                                <th class="tb-col"><span class="overline-title">{{ __('Desc') }}</span></th>
                                <th class="tb-col"><span class="overline-title">{{ __('Invest ID') }}</span></th>
                                <th class="tb-col"><span class="overline-title">{{ __('User ID') }}</span></th>
                                <th class="tb-col"><span class="overline-title">{{ __('Date & Time') }}</span></th>
                                <th class="tb-col"><span class="overline-title">{{ __('Paid') }}</span> <em class="icon ni ni-info nk-tooltip small text-soft" title="{{ __("The profit amount transfered to investment account or not.") }}"></em></th>
                                <th class="tb-col tb-col-end"><span class="overline-title">{{ __('Amount') }}</span></th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($profits as $profit)
                            <tr>
                                <td class="tb-col">
                                    {{ __("Profit for :plan", ['plan' => data_get($profit, 'invest.scheme.name')]) }}
                                    <em class="icon ni ni-info nk-tooltip small text-soft" title="{{ __("Invested: :amount", ['amount' => money($profit->invested, base_currency()) ]) }}"></em>
                                </td>
                                <td class="tb-col">
                                    <a class="text-dark" href="{{ route('admin.investment.details', ['id' => the_hash($profit->invest->id)]) }}">{{ $profit->invest->ivx }}</a>
                                </td>
                                <td class="tb-col">
                                    {{ the_uid($profit->user_id) }}
                                    <em class="icon ni ni-info nk-tooltip small text-soft" title="{{ str_protect($profit->invest_by->email) }}"></em>
                                </td>
                                <td class="tb-col"><span class="sub-text">{{ show_date($profit->calc_at, true) }}</span></td>
                                <td class="tb-col"><span class="sub-text">{!! ($profit->payout) ? '<em class="icon ni ni-info nk-tooltip text-soft" title="'. __("Batch #:id", ['id' => $profit->payout]). '"></em> ' . show_date($profit->updated_at, true) : '<span class="font-italic text-soft">'.__("Not yet").'</span>' !!}</span></td>
                                <td class="tb-col tb-col-end"><span class="{{ (!$profit->payout) ? 'lead-text ' : '' }}text-dark">+ {{ amount_z($profit->amount, base_currency(), ['dp' => 'calc']) }}</span></td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-inner pt-3 pb-3">
                        @if(filled($profits))
                            {{ $profits->appends(request()->all())->links('misc.pagination') }}
                        @endif
                    </div>
                    @else
                    <div class="alert alert-primary">
                        <div class="alert-cta flex-wrap flex-md-nowrap">
                            <div class="alert-text">
                                <p>{{ __('No profit found.') }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modal')
<div class="modal fade" role="dialog" id="ajax-modal"></div>
@endpush

@push('scripts')
<script type="text/javascript">
    const updateSetting = "{{ route('admin.profile.update') }}", 
          routes = { profit: "{{ route('admin.investment.process.profits') }}", plan: "{{ route('admin.investment.process.plans') }}" };
</script>
@endpush