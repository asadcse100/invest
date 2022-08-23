@extends('admin.layouts.master')
@section('title', __('Transaction List'))

@section('content')
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between gy-2 gx-3">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">
                        @if($menuType == 'dedicated' && ($tnxType=='deposit'||$tnxType=='withdraw'||$tnxType=='referral'))
                        {{ __(':Tnxtype', ['tnxtype' => $tnxType.(($tnxType=='withdraw') ? 'als' : 's')]) }}
                        @else 
                        {{ __('Transactions') }}
                        @endif
                    </h3>
                    <p>{!! __('Total :count transactions.', ['count' => '<span class="text-base">'.$transactions->total().'</span>']) !!}</p>
                </div>
                <div class="nk-block-head-content">
                    <div class="toggle-wrap nk-block-tools-toggle">
                        <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                        <div class="toggle-expand-content" data-content="pageMenu">
                            <ul class="nk-block-tools g-3">
                                {{-- <li><a href="javascript:void(0)" class="btn btn-white btn-dim btn-outline-gray">
                                    <em class="icon ni ni-download-cloud"></em><span>{{ __('Export') }}</span></a>
                                </li> --}}
                                <li class="nk-block-tools-opt">
                                    <div class="btn-group">
                                        <button class="m-tnx-manual btn btn-primary" data-action="manual" data-view="any" data-backdrop="static"><em class="icon ni ni-plus"></em> <span>{{ __("Add") }}</span></button>
                                        <div class="btn-group dropdown">
                                            <button class="dropdown-toggle btn btn-icon btn-primary" data-toggle="dropdown"><em class="icon ni ni-chevron-down"></em></button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <ul class="link-list-opt no-bdr">
                                                    <li><a href="javascript:void(0)" class="m-tnx-manual" data-action="manual" data-backdrop="static" data-view="deposit">
                                                        <em class="icon ni ni-wallet-in"></em><span>{{ __('Add Deposit') }}</span></a>
                                                    </li>
                                                    <li><a href="javascript:void(0)" class="m-tnx-manual" data-action="manual" data-backdrop="static" data-view="bonus">
                                                        <em class="icon ni ni-wallet-saving"></em><span>{{ __('Add Bonus') }}</span></a>
                                                    </li>
                                                    <li><a href="javascript:void(0)" class="m-tnx-manual" data-action="manual" data-backdrop="static" data-view="charge">
                                                        <em class="icon ni ni-wallet-out"></em><span>{{ __('Add Charge') }}</span></a>
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
            </div>
        </div>
        <ul class="nk-nav nav nav-tabs mt-n3 mb-md-n3">
        @if($menuType == 'dedicated')
            <li class="nav-item">
                <a class="nav-link {{ (is_route('admin.transactions.'.$tnxType.'.all') && $tnxStatus == 'any') ? 'active' : '' }}"
                   href="{{ route('admin.transactions.'.$tnxType.'.all', ['status' => 'any']) }}">
                   <span>{{ __('History') }}</span></a>
            </li>
            
            @if($pendingCount > 0)
            <li class="nav-item">
                <a class="nav-link {{ (is_route('admin.transactions.'.$tnxType.'.all') && $tnxStatus == 'pending') ? 'active' : '' }}"
                   href="{{ route('admin.transactions.'.$tnxType.'.all', ['status' => 'pending']) }}">
                   <span>{{ __('Pending') }}</span></a>
            </li>
            @endif

            @if($confirmedCount > 0)
            <li class="nav-item">
                <a class="nav-link {{ (is_route('admin.transactions.'.$tnxType.'.all') && $tnxStatus == 'confirmed') ? 'active' : '' }}"
                   href="{{ route('admin.transactions.'.$tnxType.'.all', ['status' => 'confirmed']) }}">
                   <span>{{ __('Confirmed') }}</span></a>
            </li>
            @endif

            @if($onHoldCount > 0)
            <li class="nav-item">
                <a class="nav-link {{ (is_route('admin.transactions.'.$tnxType.'.all') && $tnxStatus == 'onhold') ? 'active' : '' }}"
                   href="{{ route('admin.transactions.'.$tnxType.'.all', ['status' => 'onhold']) }}">
                   <span>{{ __('On Hold') }}</span></a>
            </li>
            @endif

            @if($processCount > 0)
            <li class="nav-item">
                <a class="nav-link {{ (is_route('admin.transactions.'.$tnxType.'.all') && $tnxStatus == 'process') ? 'active' : '' }}"
                   href="{{ route('admin.transactions.'.$tnxType.'.all', ['status' => 'process']) }}">
                   <span>{{ __('Proceed') }} <span class="badge badge-primary">{{ $processCount }}</span></span></a>
            </li>
            @endif
        @else
            @if($showAll || request()->routeIs('admin.transactions.list'))
            <li class="nav-item">
                <a class="nav-link {{ ($menuType == 'history') ? 'active' : '' }}"
                   href="{{ route('admin.transactions.list') }}"><span>{{ __('History') }}</span></a>
            </li>
            @endif

            @if($showAll || request()->routeIs('admin.transactions.confirmed'))
                @if($confirmedCount > 0)
                <li class="nav-item">
                    <a class="nav-link {{ (is_route('admin.transactions.confirmed') || $menuType == 'confirmed') ? 'active' : '' }}"
                       href="{{ route('admin.transactions.list', ['list_type' => 'confirmed']) }}">
                       <span>{{ __('Confirmed') }}</span></a>
                </li>
                @endif
            @endif

            @if($showAll || request()->routeIs('admin.transactions.pending'))
                @if($pendingCount > 0)
                <li class="nav-item">
                    <a class="nav-link {{ (is_route('admin.transactions.pending') || $menuType == 'pending') ? 'active' : '' }}"
                       href="{{ route('admin.transactions.list', ['list_type' => 'pending']) }}">
                       <span>{{ __('Pending') }}</span></a>
                </li>
                @endif
            @endif

            @if($showAll || request()->routeIs('admin.transactions.on-hold'))
                @if($onHoldCount > 0)
                <li class="nav-item">
                    <a class="nav-link {{ (is_route('admin.transactions.onhold') || $menuType == 'on-hold') ? 'active' : '' }}"
                       href="{{ route('admin.transactions.list', ['list_type' => 'on-hold']) }}">
                       <span>{{ __('On Hold') }}</span></a>
                </li>
                @endif
            @endif

            @if($showAll || request()->routeIs('admin.transactions.deposit'))
            <li class="nav-item">
                <a class="nav-link {{ (is_route('admin.transactions.deposit') || $menuType == 'deposit') ? 'active' : '' }}"
                   href="{{ route('admin.transactions.list', ['list_type' => 'deposit']) }}">
                   <span>{{ __('Deposit') }}</span></a>
            </li>
            @endif

            @if($showAll || request()->routeIs('admin.transactions.withdraw'))
            <li class="nav-item">
                <a class="nav-link {{ (is_route('admin.transactions.withdraw') || $menuType == 'withdraw') ? 'active' : '' }}"
                   href="{{ route('admin.transactions.list', ['list_type' => 'withdraw']) }}">
                   <span>{{ __('Withdraw') }}</span></a>
            </li>
            @endif

            @if($showAll || request()->routeIs('admin.transactions.process'))
                @if($processCount > 0)
                <li class="nav-item">
                    <a class="nav-link {{ (is_route('admin.transactions.process') || $menuType == 'process') ? 'active' : '' }}"
                    href="{{ route('admin.transactions.list', ['list_type' => 'process']) }}">
                    <span>{{ __('Proceed') }} <span class="badge badge-primary">{{ $processCount }}</span></span></a>
                </li>
                @endif
            @endif
        @endif
        </ul>

        <div class="nk-block">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h6 class="nk-block-title">{{ __('All Orders') }}</h6>
                    </div>
                    <ul class="nk-block-tools gx-3">
                        <li>
                            <a href="#" class="search-toggle toggle-search btn btn-icon btn-trigger" data-target="search"><em class="icon ni ni-search"></em></a>
                        </li>
                        <li>
                            <div class="dropdown">
                                <a href="#" class="btn btn-trigger btn-icon dropdown-toggle" data-toggle="dropdown">
                                    @if(filter_count() > 0)
                                        <div class="badge badge-circle badge-primary"> {{ filter_count() }} </div>
                                    @endif
                                    <em class="icon ni ni-filter-alt"></em>
                                </a>
                                <div class="filter-wg dropdown-menu dropdown-menu-xl dropdown-menu-right">
                                    <div class="dropdown-head">
                                        <span class="sub-title dropdown-title">{{ __('Advance Filter') }}</span>
                                    </div>
                                    <form action="{{ route('admin.transactions.list') }}" method="GET">
                                        <input type="hidden" name="filter" value="true">
                                        <div class="dropdown-body dropdown-body-rg">
                                            <div class="row gx-6 gy-3">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label
                                                            class="overline-title overline-title-alt">{{ __('Type') }}</label>
                                                        <select name="type" class="form-select form-select-sm">
                                                            <option value="any">{{ __('Any Type') }}</option>
                                                            @foreach($transactionTypes as $type)
                                                                <option @if(request()->get('type') == $type) selected
                                                                        @endif
                                                                        value="{{ $type }}">{{ ucfirst(__($type)) }}</option>
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
                                                            @foreach($transactionStatuses as $status)
                                                                <option
                                                                    @if(request()->get('status') == $status) selected
                                                                    @endif
                                                                    value="{{ $status }}">{{ ucfirst(__($status)) }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label
                                                            class="overline-title overline-title-alt">{{ __('Pay Currency') }}</label>
                                                        <select name="currency" class="form-select form-select-sm">
                                                            <option value="any">{{ __('Any Currency') }}</option>
                                                            @foreach($payCurrencies as $key => $currency)
                                                            <option @if(request()->get('currency') == $key) selected @endif value="{{ $key }}">{{ data_get($currency, 'name') }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label class="overline-title overline-title-alt">{{ __('Method') }}</label>
                                                        <select name="method" class="form-select form-select-sm">
                                                            <option value="any">{{ __('Any Method') }}</option>
                                                            @foreach($tnxMethods as $key => $item)
                                                                <option @if(request()->get('method') == $key) selected @endif value="{{ $key }}">{{ $item }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <div class="custom-control custom-control-sm custom-checkbox">
                                                            <input name="include_deleted" type="checkbox" class="custom-control-input"
                                                                   id="includeDel">
                                                            <label class="custom-control-label" for="includeDel">
                                                                {{ __('Including Deleted') }} </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dropdown-foot between">
                                            <button type="submit" class="btn btn-secondary">{{ __('Filter') }}</button>
                                            <a href="{{ route('admin.transactions.list') }}" class="clickable">{{ __('Reset Filter') }}</a>
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
                                        <li class="update-meta{{ (user_meta('tnx_perpage', '10') == $item) ? ' active' : '' }}">
                                            <a href="#" data-value="{{ $item }}" data-meta="perpage" data-type="tnx">{{ $item }}</a>
                                        </li>
                                        @endforeach
                                    </ul>
                                    <ul class="link-check">
                                        <li><span>{{ __('Order') }}</span></li>
                                        @foreach(config('investorm.pgtn_order') as $item)
                                        <li class="update-meta{{ (user_meta('tnx_order', 'asc') == $item) ? ' active' : '' }}">
                                            <a href="#" data-value="{{ $item }}" data-meta="order" data-type="tnx">{{ __(strtoupper($item)) }}</a>
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
                </div>
                <form action="{{ url()->current() }}" method="GET">
                    <div class="search-wrap search-wrap-extend bg-lighter" data-search="search">
                        <div class="search-content">
                            <a href="#" class="search-back btn btn-icon toggle-search" data-target="search"><em class="icon ni ni-arrow-left"></em></a>
                            <input type="text" name="query" class="form-control border-transparent form-focus-none" placeholder="Search by Transaction id or User id">
                            <button class="search-submit btn btn-icon mr-1"><em class="icon ni ni-search"></em></button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card card-bordered card-stretch">
                <div class="card-inner-group">
                    @if(filled($transactions))
                        @include('admin.transaction.transaction-table', ['transactions' => $transactions])
                    @else
                        <div class="alert alert-primary">
                            <div class="alert-cta flex-wrap flex-md-nowrap">
                                <div class="alert-text">
                                    <p>{{ __('No Transaction Found') }}</p>
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
        routes = { 
            view: "{{ route('admin.transaction.details') }}", 
            online: "{{ route('admin.transaction.status.check') }}", 
            approve: "{{ route('admin.transaction.update', 'approve') }}",
            reject: "{{ route('admin.transaction.update', 'reject') }}",
            confirm: "{{ route('admin.transaction.update', 'confirm') }}",
            manual: "{{route('admin.transaction.manual.add')}}"
        },
        msgs = {
            completed: {
                title: "{{ __('Complete Transaction?') }}", 
                btn: {cancel: "{{ __('Cancel') }}", confirm: "{{ __('Yes, Procced') }}"}, 
                context: "{!! __("Please confirm that you want to procced the request and complete the transaction.") !!}", 
                custom: "success", type: "info" 
            },
            confirmed: { 
                title: "{{ __('Procced Withdraw?') }}", 
                btn: {cancel: "{{ __('Cancel') }}", confirm: "{{ __('Yes, Procced') }}"}, 
                context: "{!! __("Please confirm that you want to procced the request and send the payment.") !!}", 
                custom: "", type: "info" 
            },
            cancelled: { 
                title: "{{ __('Cancel Transaction?') }}", 
                btn: {cancel: "{{ __('No') }}", confirm: "{{ __('Yes, Cancel') }}"}, 
                context: "{!! __("You cannot revert back this action, so please confirm that you've not received the payment yet and want to cancel.") !!}", 
                custom: "danger", type: "warning" 
            },
            addnew: { 
                title: "{{ __('Add Manual Transaction?') }}", 
                btn: {cancel: "{{ __('No') }}", confirm: "{{ __('Yes, Procced') }}"}, 
                context: "{!! __("You cannot revert back this action, so please confirm that you want to add the transaction manually.") !!}", 
                custom: "success", type: "info" 
            }
        };
</script>
@endpush