@extends('user.layouts.master')

@section('title', __('Transaction History'))

@section('content')
    <div class="nk-content-body">
        <div class="nk-block-head">
            <div class="nk-block-head-sub"><span>{{ __('History') }}</span></div>
            <div class="nk-block-between-sm g-4">
                <div class="nk-block-head-content">
                    <h2 class="nk-block-title fw-normal">{{ __('Transactions') }}</h2>
                    <div class="nk-block-des">
                        <p>{{ __('List of transactions in your account.') }}</p>
                    </div>
                </div>
                <div class="nk-block-head-content d-none d-md-inline-flex">
                    <ul class="nk-block-tools gx-3">
                        @if (module_exist('FundTransfer', 'mod') && feature_enable('transfer'))
                            <li><a href="{{ route('user.send-funds.show') }}" class="btn btn-light btn-white"><span>{{ __('Send Funds') }}</span> <em class="icon ni ni-arrow-long-right"></em></a></li>
                        @endif
                        <li><a href="{{ route('deposit') }}" class="btn btn-primary"><span>{{ __('Deposit') }}</span> <em class="icon ni ni-arrow-long-right"></em></a></li>
                    </ul>
                </div>
            </div>
        </div>

        <ul class="nk-nav nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link{{ (blank(request('type')) && blank(request()->get('view'))) ? ' active' : '' }}" href="{{ route('transaction.list') }}">{{ __('History') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link{{ (request()->get('type')==$tnxTypes['DEPOSIT']) ? ' active' : '' }}" href="{{ route('transaction.list', ['type'=> $tnxTypes['DEPOSIT']]) }}">{{ __('Deposit') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link{{ (request()->get('type')==$tnxTypes['WITHDRAW']) ? ' active' : '' }}" href="{{ route('transaction.list', ['type'=> $tnxTypes['WITHDRAW']]) }}">{{ __('Withdraw') }}</a>
            </li>
            @if($scheduledCount > 0)
                <li class="nav-item">
                    <a class="nav-link{{ (request()->get('view')=='scheduled') ? ' active' : '' }}" href="{{ route('transaction.list', [ 'view'=> 'scheduled' ]) }}">
                        {{ __('Scheduled') }} <span class="badge badge-primary">{{ $scheduledCount }}</span>
                    </a>
                </li>
            @endif
        </ul>
        <div class="nk-block nk-block-xs">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    @if(filled($transactions))
                        <div class="nk-block-head-content">
                            <h6 class="nk-block-title">{{ __(':type Transaction', ['type' => (request('type')) ? __(ucfirst(request('type'))) : __('All')]) }}</h6>
                        </div>
                        <ul class="nk-block-tools gx-2">
                            <li>
                                <a href="#" class="search-toggle toggle-search btn btn-icon btn-trigger"
                                   data-target="search"><em class="icon ni ni-search"></em></a>
                            </li>
                            <li>
                                <div class="dropdown">
                                    <a href="#" class="btn btn-trigger btn-icon dropdown-toggle" data-toggle="dropdown">
                                        <div class="dot dot-primary"></div>
                                        <em class="icon ni ni-filter-alt"></em>
                                    </a>

                                    <div class="filter-wg dropdown-menu dropdown-menu-lg dropdown-menu-right">
                                        <div class="dropdown-head">
                                            <span class="sub-title dropdown-title">{{ __('Filter Transaction') }}</span>
                                        </div>
                                        <form action="{{ route('transaction.list') }}" method="GET">
                                            <input type="hidden" name="filter" value="true">
                                            <div class="dropdown-body dropdown-body-rg">
                                                <div class="row gx-6 gy-3">
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label
                                                                class="overline-title overline-title-alt">{{ __('Type') }}</label>
                                                            <select name="type" class="form-select form-select-sm">
                                                                <option value="any">{{ __('Any Type') }}</option>
                                                                @foreach($tnxTypes as $type)
                                                                    <option{{ (request()->get('type') == $type) ? ' selected' : '' }} value="{{ $type }}">{{ ucfirst(__($type)) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label
                                                                class="overline-title overline-title-alt">{{ __('Status') }}</label>
                                                            <select name="status" class="form-select form-select-sm">
                                                                <option value="any">{{ __('Any Status') }}</option>
                                                                @foreach($tnxStates as $status)
                                                                    <option{{ (request()->get('status') == $status) ? ' selected' : '' }} value="{{ $status }}">{{ ucfirst(__($status)) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label
                                                                class="overline-title overline-title-alt">{{ __('From') }}</label>
                                                            <input class="form-control date-picker" name="date[from]"
                                                                   type="text"
                                                                   value="{{ \Illuminate\Support\Arr::get(request()->get('date'), 'from') }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label
                                                                class="overline-title overline-title-alt">{{ __('To') }}</label>
                                                            <input class="form-control date-picker" name="date[to]"
                                                                   type="text"
                                                                   value="{{ \Illuminate\Support\Arr::get(request()->get('date'), 'to') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="dropdown-foot between">
                                                <button type="submit"
                                                        class="btn btn-secondary">{{ __('Filter') }}</button>
                                                <a href="{{ route('transaction.list') }}"
                                                   class="clickable">{{ __('Reset Filter') }}</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="dropdown">
                                    <a class="dropdown-toggle btn btn-icon btn-trigger mr-n1" data-toggle="dropdown"
                                       data-offset="-8,0"><em class="icon ni ni-setting"></em></a>
                                    <div class="dropdown-menu dropdown-menu-xs dropdown-menu-right">
                                        <ul class="link-check">
                                            <li><span>{{ __('Show') }}</span></li>
                                            @foreach(config('investorm.pgtn_pr_pg') as $item)
                                            <li class="update-meta{{ (user_meta('tnx_perpage', '10') == $item) ? ' active' : '' }}">
                                                <a href="#" data-value="{{ $item }}" data-meta="perpage" data-type="tnx">{{ __(ucfirst($item)) }}</a>
                                            </li>
                                            @endforeach
                                        </ul>
                                        <ul class="link-check">
                                            <li><span>{{ __('Density') }}</span></li>
                                            @foreach(config('investorm.pgtn_dnsty') as $item)
                                            <li class="update-meta{{ (user_meta('tnx_display', 'regular') == $item) ? ' active' : '' }}">
                                                <a href="#" data-value="{{ $item }}" data-meta="display" data-type="tnx">{{ __(ucfirst($item)) }}</a>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    @endif
                </div>
                <form action="{{ route('transaction.list') }}" method="GET">
                    <div class="search-wrap search-wrap-extend bg-lighter" data-search="search">
                        <div class="search-content">
                            <a href="#" class="search-back btn btn-icon toggle-search" data-target="search"><em
                                    class="icon ni ni-arrow-left"></em></a>
                            <input type="text" name="query" class="form-control border-transparent form-focus-none"
                                   placeholder="{{ __('Search by transaction id') }}">
                            <button class="search-submit btn btn-icon mr-1"><em class="icon ni ni-search"></em></button>
                        </div>
                    </div>
                </form>
            </div>
            <div
                class="nk-odr-list is-stretch card card-bordered {{ user_meta('tnx_display') == 'compact' ? 'is-compact': '' }}">
                @if(filled($transactions))
                    @foreach($transactions as $transaction)
                        @include('user.transaction.trans-row', compact('transaction'))
                    @endforeach
                @else
                    <div class="nk-odr-item">
                        <div class="nk-odr-col">{{ __('No transactions found!') }}</div>
                    </div>
                @endif
            </div>
            <div class="mt-4">
                @if(filled($transactions))
                    {{ $transactions->appends(request()->all())->links('misc.pagination') }}
                @endif
            </div>
        </div>
    </div>
@endsection

@push('modal')
<div class="modal fade" role="dialog" id="ajax-modal"></div>
@endpush

@push('scripts')
    <script>
        new ClipboardJS('.clipboard-init', {
            container: document.getElementById('ajax-modal')
        });
    </script>
@endpush
