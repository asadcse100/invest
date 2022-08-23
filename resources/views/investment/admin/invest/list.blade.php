@extends('admin.layouts.master')
@section('title', __('Invested Plan'))

@section('content')
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">{{ __('Invested Plans') }}</h3>
                    <p>{!! __('Total :count entries.', ['count' => '<span class="text-base">'.$investments->total().'</span>' ]) !!}</p>
                </div>
                <div class="nk-block-head-content">
                    <ul class="nk-block-tools g-3">
                        <li>
                            <a href="{{ route('admin.investment.schemes') }}" class="btn btn-white btn-dim btn-outline-gray d-none d-sm-inline-flex"><em class="icon ni ni-package-fill"></em><span>{{ __("Manage Scheme") }}</span></a>
                            <a href="{{ route('admin.investment.schemes') }}" class="btn btn-icon btn-white btn-dim btn-outline-gray d-inline-flex d-sm-none"><em class="icon ni ni-package-fill"></em></a>
                        </li>
                        <li class="nk-block-tools">
                            <div class="dropdown">
                                <a class="dropdown-toggle btn btn-primary" data-toggle="dropdown"><em class="icon ni ni-invest"></em><span class="d-none d-sm-inline">{{ __("Process") }}</span></a>
                                <div class="dropdown-menu dropdown-menu-smd dropdown-menu-right">
                                    <ul class="link-list-opt no-bdr">
                                        <li><a href="javascript:void(0)" data-action="plan" data-backdrop="static" class="m-ivs-process{{ (is_locked('plan')) ? ' disabled' : '' }}">
                                            <em class="icon ni ni-update"></em><span>{{ __('Sync Invested Plans') }}</span></a>
                                        </li>
                                        <li><a href="javascript:void(0)" data-action="profit" data-backdrop="static" class="m-ivs-process{{ (is_locked('profit')) ? ' disabled' : '' }}">
                                            <em class="icon ni ni-check-circle-cut"></em><span>{{ __('Approve the Profits') }}</span></a>
                                        </li>
                                        <li><a href="javascript:void(0)" data-action="transfer" data-backdrop="static" class="m-ivs-process{{ (is_locked('transfer')) ? ' disabled' : '' }}"">
                                            <em class="icon ni ni-swap-alt"></em><span>{{ __('Complete Auto Transfers') }}</span></a>
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
                <a class="nav-link{{ ($listing=='active') ? ' active' : '' }}" href="{{ route('admin.investment.list', ['status' => 'active']) }}"><span>{{ __('Actived') }}</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link{{ ($listing=='pending') ? ' active' : '' }}" href="{{ route('admin.investment.list', ['status' => 'pending']) }}">
                    <span>{{ __('Pending') }} @if($pendingCount > 0) <span class="badge badge-primary">{{ $pendingCount }}</span> @endif </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link{{ ($listing=='completed') ? ' active' : '' }}" href="{{ route('admin.investment.list', ['status' => 'completed']) }}">
                    <span>{{ __('Completed') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link{{ ($listing=='all') ? ' active' : '' }}" href="{{ route('admin.investment.list') }}">
                    <span>{{ __('All Plans') }}</span>
                </a>
            </li>
        </ul>
        <div class="nk-block nk-block-sm">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h6 class="nk-block-title">{{ __(':status Invested Plans', ['status' => ucfirst((($listing=='active') ? 'Actived' : $listing)) ?? 'Actived']) }}</h6>
                    </div>
                    <ul class="nk-block-tools gx-3">
                        <li>
                            <a href="#" class="search-toggle toggle-search btn btn-icon btn-trigger" data-target="search"><em class="icon ni ni-search"></em></a>
                        </li>
                        <li>
                            <div class="dropdown">
                                <a class="dropdown-toggle btn btn-icon btn-trigger mx-n2" data-toggle="dropdown" data-offset="-8,0" aria-expanded="false"><em class="icon ni ni-setting"></em></a>
                                <div class="dropdown-menu dropdown-menu-xs dropdown-menu-right" style="">
                                    <ul class="link-check">
                                        <li><span>{{ __('Show') }}</span></li>
                                        @foreach(config('investorm.pgtn_pr_pg') as $item)
                                        <li class="update-meta{{ (user_meta('iv_invest_perpage', '10') == $item) ? ' active' : '' }}">
                                            <a href="#" data-value="{{ $item }}" data-meta="perpage" data-type="iv_invest">{{ $item }}</a>
                                        </li>
                                        @endforeach
                                    </ul>
                                    <ul class="link-check">
                                        <li><span>{{ __('Order') }}</span></li>
                                        @foreach(config('investorm.pgtn_order') as $item)
                                        <li class="update-meta{{ (user_meta('iv_invest_order', 'desc') == $item) ? ' active' : '' }}">
                                            <a href="#" data-value="{{ $item }}" data-meta="order" data-type="iv_invest">{{ __(strtoupper($item)) }}</a>
                                        </li>
                                        @endforeach
                                    </ul>
                                    <ul class="link-check">
                                        <li><span>{{ __('Density') }}</span></li>
                                        @foreach(config('investorm.pgtn_dnsty') as $item)
                                        <li class="update-meta{{ (user_meta('iv_invest_display', 'regular') == $item) ? ' active' : '' }}">
                                            <a href="#" data-value="{{ $item }}" data-meta="display" data-type="iv_invest">{{ __(ucfirst($item)) }}</a>
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
                            <a href="#" class="search-back btn btn-icon toggle-search" data-target="search"><em class="icon ni ni-arrow-left"></em></a>
                            <input type="text" name="query" value="{{ request()->get('query') }}" class="form-control border-transparent form-focus-none" placeholder="{{ __("Search by investment id") }}">
                            <button class="search-submit btn btn-icon"><em class="icon ni ni-search"></em></button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card card-bordered">
                <div class="card-inner-group">
                    @if(blank($investments))
                        <div class="alert alert-primary">
                            <div class="alert-cta flex-wrap flex-md-nowrap">
                                <div class="alert-text">
                                    <p>{{ __('No investment plan found.') }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                    <div class="card-inner p-0">
                        <div class="nk-tb-list nk-tb-ivx{{ user_meta('iv_invest_display') == 'compact' ? ' is-compact': '' }}">
                            <div class="nk-tb-item nk-tb-head">
                                <div class="nk-tb-col"><span>{{ __('Plan') }}</span></div>
                                <div class="nk-tb-col tb-col-sm"><span>{{ __('Invest By') }}</span></div>
                                <div class="nk-tb-col tb-col-lg"><span>{{ __('Start Date') }}</span></div>
                                <div class="nk-tb-col tb-col-lg"><span>{{ __('End Date') }}</span></div>
                                <div class="nk-tb-col tb-col-lg"><span>{{ __('Investment ID') }}</span></div>
                                <div class="nk-tb-col"><span>{{ __('Amount') }}</span></div>
                                <div class="nk-tb-col tb-col-sm"><span>{{ __('Status') }}</span></div>
                                <div class="nk-tb-col"><span>&nbsp;</span></div>
                            </div>

                            @foreach($investments as $plan)
                            <div class="nk-tb-item">
                                <div class="nk-tb-col">
                                    <div class="align-center">
                                        <div class="user-avatar user-avatar-sm bg-light">
                                            <span>{{ strtoupper(substr(data_get($plan, 'scheme.short'), 0, 2)) }}</span>
                                        </div>
                                        <span class="tb-sub ml-2">{{ data_get($plan, 'scheme.name') }} <span class="d-none d-md-inline">- {{ data_get($plan, 'calc_details_alter') }}</span></span>
                                    </div>
                                </div>
                                <div class="nk-tb-col tb-col-sm">
                                    <div class="user-card">
                                        {!! user_avatar($plan->user, 'xs') !!}
                                        <div class="user-name">
                                            <span class="tb-lead">{{ data_get($plan, 'user.name') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-tb-col tb-col-lg">
                                    <span class="tb-sub">{{ show_date(data_get($plan, 'term_start'), true) }}</span>
                                </div>
                                <div class="nk-tb-col tb-col-lg">
                                    <span class="tb-sub">{{ show_date(data_get($plan, 'term_end'), true) }}</span>
                                </div>
                                <div class="nk-tb-col tb-col-lg">
                                    <span class="tb-sub">{{ data_get($plan, 'ivx') }}</span>
                                </div>
                                <div class="nk-tb-col">
                                    <span class="tb-sub tb-amount">{{ money(data_get($plan, 'amount'), base_currency()) }}</span>
                                </div>
                                <div class="nk-tb-col tb-col-sm">
                                    @if(data_get($plan, 'status')=='active' && $listing!='all')
                                    <div class="progress progress-sm w-100px nk-tooltip" title="{{ __("Received :amount (:percent)", ['amount' => money(data_get($plan, 'received', 0), base_currency()), 'percent' => data_get($plan, 'progress', 0).'%']) }}">
                                        <div class="progress-bar" data-progress="{{ data_get($plan, 'progress', 0) }}"></div>
                                    </div>
                                    @else 
                                    <span class="badge badge-dim {{ the_state(data_get($plan, 'status'), ['prefix' => 'badge']) }}">{{ __(ucfirst(data_get($plan, 'status'))) }}</span>
                                    @endif
                                </div>
                                <div class="nk-tb-col nk-tb-col-action">
                                    <a href="{{ route('admin.investment.details', ['id' => the_hash($plan->id)]) }}" class="text-soft btn btn-sm btn-icon btn-trigger"><em class="icon ni ni-chevron-right"></em></a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @if(filled($investments) && $investments->hasPages())
                    <div class="card-inner pt-3 pb-3">
                        {{ $investments->appends(request()->all())->links('misc.pagination') }}
                    </div>
                    @endif
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
            profit: "{{ route('admin.investment.process.profits') }}", 
            plan: "{{ route('admin.investment.process.plans') }}",
            transfer: "{{ route('admin.investment.process.transfers') }}",
            quick: "{{ route('admin.investment.plan.action') }}",
            approve: "{{ route('admin.investment.plan.approve') }}",
            reject: "{{ route('admin.investment.plan.cancel') }}"
        },
        msgs = {
            approved: {
                title: "{{ __('Approved Investment?') }}",
                btn: {cancel: "{{ __('Cancel') }}", confirm: "{{ __('Yes, Procced') }}"},
                context: "{!! __("Please confirm that you want to procced the request and start the investment.") !!}",
                custom: "success", type: "info"
            },
            cancelled: {
                title: "{{ __('Cancel Investment?') }}",
                btn: {cancel: "{{ __('No') }}", confirm: "{{ __('Yes, Cancel') }}"},
                context: "{!! __("You cannot revert back this action, so please confirm that you want to cancel.") !!}",
                custom: "danger", type: "warning"
            }
        };
</script>
@endpush
