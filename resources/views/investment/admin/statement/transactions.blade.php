@extends('admin.layouts.master')
@section('title', __('Invest Transactions'))

@php

$typeName = __("All Transactions");

if($type=='transfer') {
    $typeName = __("Transferred History");
}
if($type=='profit') {
    $typeName = __("Profit Settelment");
}

@endphp

@section('content')
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between gy-2 gx-3">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">{{ __('Invest Transactions') }}</h3>
                    <p>{!! __('Total :num transactions.', ['num' => '<span class="text-base">'.$transactions->total().'</span>' ]) !!}</p>
                </div>
                <div class="nk-block-head-content">
                    <ul class="nk-block-tools g-3">
                        <li>
                            <a href="{{ route('admin.investment.profits.list') }}" class="btn btn-white btn-dim btn-outline-gray d-none d-sm-inline-flex"><em class="icon ni ni-report-profit"></em><span>{{ __("Profit Logs") }}</span></a>
                            <a href="{{ route('admin.investment.profits.list') }}" class="btn btn-icon btn-white btn-dim btn-outline-gray d-inline-flex d-sm-none"><em class="icon ni ni-report-profit"></em></a>
                        </li>
                        <li class="nk-block-tools-opt">
                            <div class="btn-group">
                                <button class="m-tnx-manual btn btn-primary" data-action="manual" data-view="any" data-backdrop="static"><em class="icon ni ni-plus"></em> <span>{{ __("Add") }}</span></button>
                                <div class="btn-group dropdown">
                                    <button class="dropdown-toggle btn btn-icon btn-primary" data-toggle="dropdown"><em class="icon ni ni-chevron-down"></em></button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <ul class="link-list-opt no-bdr">
                                            <li class="opt-head overline-title"><span>{{ __("Add Manually") }}</span></li>
                                            <li><a href="javascript:void(0)" class="m-tnx-manual" data-action="manual" data-backdrop="static" data-view="profit">
                                                <em class="icon ni ni-wallet-in"></em><span>{{ __('Add Profit') }}</span></a>
                                            </li>
                                            <li><a href="javascript:void(0)" class="m-tnx-manual" data-action="manual" data-backdrop="static" data-view="loss">
                                                <em class="icon ni ni-wallet-saving"></em><span>{{ __('Add Loss') }}</span></a>
                                            </li>
                                            <li><a href="javascript:void(0)" class="m-tnx-manual" data-action="manual" data-backdrop="static" data-view="penalty">
                                                <em class="icon ni ni-wallet-out"></em><span>{{ __('Add Penalty') }}</span></a>
                                            </li>
                                            <li class="divider"></li>
                                            <li class="opt-head overline-title"><span>{{ __("Bulk Process") }}</span></li>
                                            <li><a href="javascript:void(0)" data-action="plan" data-backdrop="static" class="m-ivs-process{{ (is_locked('plan')) ? ' disabled' : '' }}">
                                                <em class="icon ni ni-update"></em><span>{{ __('Sync Invested Plans') }}</span></a>
                                            </li>
                                            <li><a href="javascript:void(0)" data-action="profit" data-backdrop="static" class="m-ivs-process{{ (is_locked('profit')) ? ' disabled' : '' }}">
                                                <em class="icon ni ni-check-circle-cut"></em><span>{{ __('Approve the Profits') }}</span></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <ul class="nk-nav nav nav-tabs mt-n3 mb-md-n3">
            <li class="nav-item">
                <a class="nav-link{{ (is_route('admin.investment.transactions.list') && $type=='all') ? ' active' : '' }}" href="{{ route('admin.investment.transactions.list') }}">
                    <span>{{ __('History') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link{{ (is_route('admin.investment.transactions.list') && $type=='profit') ? ' active' : '' }}" href="{{ route('admin.investment.transactions.list', 'profit') }}">
                    <span>{{ __('Profits') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link{{ (is_route('admin.investment.transactions.list') && $type=='transfer') ? ' active' : '' }}" href="{{ route('admin.investment.transactions.list', 'transfer') }}">
                    <span>{{ __('Transfer') }}</span>
                </a>
            </li>
        </ul>

        <div class="nk-block">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h6 class="nk-block-title">{{ __(':StatementType', ['statementType' => $typeName]) }}</h6>
                    </div>
                    <ul class="nk-block-tools gx-3">
                        <li><a href="#" class="search-toggle toggle-search btn btn-icon btn-trigger" data-target="search"><em class="icon ni ni-search"></em></a></li>
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
                                    <form action="{{ route('admin.investment.transactions.list') }}" method="GET">
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
                                            <a href="{{ route('admin.investment.transactions.list') }}" class="clickable">{{ __('Reset Filter') }}</a>
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
                                        <li><span>{{ __('Order') }}</span></li>
                                        @foreach(config('investorm.pgtn_order') as $item)
                                        <li class="update-meta{{ (user_meta('iv_tnx_order', 'desc') == $item) ? ' active' : '' }}">
                                            <a href="#" data-value="{{ $item }}" data-meta="order" data-type="iv_tnx">{{ __(strtoupper($item)) }}</a>
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
                            <input type="text" name="query" value="{{ request()->get('query') }}" class="form-control border-transparent form-focus-none" placeholder="Search by statement id">
                            <button class="search-submit btn btn-icon mr-1"><em class="icon ni ni-search"></em></button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card card-bordered card-stretch">
                <div class="card-inner-group">
                    @if(filled($transactions))
                    <div class="card-inner p-0">
                        <div class="nk-tb-list nk-tb-tnx{{ user_meta('iv_tnx_display') == 'compact' ? ' is-compact': '' }}">
                            <div class="nk-tb-item nk-tb-head">
                                <div class="nk-tb-col"><span>{{ __('Statement ID') }}</span></div>
                                <div class="nk-tb-col tb-col-sm"><span>{{ __('Date & Time') }}</span></div>
                                <div class="nk-tb-col tb-col-md"><span>{{ __('Details') }}</span></div>
                                <div class="nk-tb-col tb-col-md"><span>{{ __('Account') }}</span></div>
                                <div class="nk-tb-col tb-col-sm"><span>{{ __('Type') }}</span></div>
                                <div class="nk-tb-col text-right"><span>{{ __('Amount') }}</span></div>
                            </div>
                            @foreach($transactions as $tnx)
                                <div class="nk-tb-item" id="tnx-row-{{ $tnx->id }}">
                                    @include('investment.admin.statement.transaction-row', ['transaction' => $tnx])
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="card-inner pt-3 pb-3">
                        @if(filled($transactions))
                            {{ $transactions->appends(request()->all())->links('misc.pagination') }}
                        @endif
                    </div>
                    @else
                    <div class="alert alert-primary">
                        <div class="alert-cta flex-wrap flex-md-nowrap">
                            <div class="alert-text">
                                <p>{{ __('No transaction found.') }}</p>
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
          routes = { profit: "{{ route('admin.investment.process.profits') }}", plan: "{{ route('admin.investment.process.plans') }}", manual: "{{route('admin.investment.manual.add')}}" };
          msgs = {
              addnew:{ 
                title: "{{ __('Add Manual Transaction?') }}", 
                btn: {cancel: "{{ __('No') }}", confirm: "{{ __('Yes, Procced') }}"}, 
                context: "{!! __("You cannot revert back this action, so please confirm that you want to add the transaction manually.") !!}", 
                custom: "success", type: "info" 
            }
          };
</script>
@endpush