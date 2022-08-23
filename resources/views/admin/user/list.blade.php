@extends('admin.layouts.master')

@php 
use \App\Enums\UserStatus as uStatus;
use \App\Enums\UserRoles as uRole;

$isAdmin = (request('state') == 'teams' || is_route('admin.users.administrator')) ? true : false;
$byTypes = (!request('state')) ? false : ucfirst(request('state'));
$pageTitle = ($isAdmin) ? __('Administrator Users') : (($byTypes) ? __(':Type Users', ['type' => $byTypes]) : __('Users List'));

$getRole = request()->get('role');
$getStatus = request()->get('status');
$getRegMethod = request()->get('regMethod');
$getHasBalance = request()->get('hasBalance');
$getEmailVerified = request()->get('emailVerified');
$getReferralJoin = request()->get('referralJoin');

$base_currency = base_currency();
@endphp

@section('title', __($pageTitle))

@section('content')
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">{{ $pageTitle }}</h3>
                    <div class="nk-block-des text-soft">
                        <p>{!! __('Total :number :type account.', ['number' => '<strong class="text-base">'.$users->total() .'</strong>', 'type' => '<span class="text-base">'.(($isAdmin) ? __(ucfirst('admin')) : __(ucfirst('user'))).'</span>']) !!}</p>
                    </div>
                </div>
                <div class="nk-block-head-content">
                    <ul class="nk-block-tools g-3">
                        <li>
                            <div class="dropdown">
                                <a class="btn btn-white btn-dim btn-outline-gray" data-toggle="dropdown"><em class="icon ni ni-download-cloud"></em> <span class="d-none d-sm-inline">{{ __("Export") }}</span></a>
                                <div class="dropdown-menu dropdown-menu-xs dropdown-menu-right">
                                    <ul class="link-list-plain">
                                        <li><a href="{{ route('admin.users.export', ['type' => 'entire']) }}" class="export">{{ __('Entire') }}</a></li>
                                        <li><a href="{{ route('admin.users.export', ['type' => 'minimum']) }}" class="export">{{ __('Minimum') }}</a></li>
                                        <li><a href="{{ route('admin.users.export', ['type' => 'compact']) }}" class="export">{{ __('Compact') }}</a></li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a data-toggle="modal" href="#add-new-user" data-toggle="modal" class="btn btn-primary d-none d-sm-inline-flex"><em class="icon ni ni-user-add"></em><span>{{ __("Add User") }}</span></a>
                            <a data-toggle="modal" href="#add-new-user" data-toggle="modal" class="btn btn-icon btn-primary d-inline-flex d-sm-none"><em class="icon ni ni-user-add"></em></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        {{-- nk-block-head --}}
        <div class="nk-block">
            @if(filled($users))
            <div class="card card-bordered card-stretch">
                <div class="card-inner-group">
                    <div class="card-inner position-relative card-tools-toggle">
                        <div class="card-title-group">
                            <div class="card-tools">
                                <div class="form-inline flex-nowrap gx-3">
                                    <div class="form-wrap w-bulk-select">
                                        <select id="bulk-action" class="form-select form-select-sm" data-search="off">
                                            <option value="0">{{ __('Bulk Action') }}</option>
                                            @if (request('state')=='inactive')
                                            <option value="removed">{{ __('Remove Permanently') }}</option>
                                            @endif
                                            <option value="locked"{{ (request('state')=='inactive') ? ' disabled' : '' }}>{{ __('Mark as Locked') }}</option>
                                            <option value="suspended"{{ (request('state')=='inactive') ? ' disabled' : '' }}>{{ __('Mark as Suspend') }}</option>
                                            <option value="actived"{{ (request('state')=='inactive') ? ' disabled' : '' }}>{{ __('Mark as Active') }}</option>
                                        </select>
                                    </div>
                                    <div class="btn-wrap">
                                        <span class="d-none d-md-block"><button class="bulk-apply btn btn-dim btn-outline-primary disabled" disabled>{{ __('Apply') }}</button></span>
                                        <span class="d-md-none"><button class="bulk-apply btn btn-dim btn-outline-primary btn-icon disabled" disabled><em class="icon ni ni-arrow-right"></em></button></span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-tools mr-n1">
                                <ul class="btn-toolbar gx-1">
                                    <li>
                                        <a href="javascript:void(0)" class="btn btn-icon search-toggle toggle-search" data-target="search"><em class="icon ni ni-search"></em></a>
                                    </li>
                                    <li class="btn-toolbar-sep"></li>
                                    <li>
                                        <div class="toggle-wrap">
                                            <a href="javascript:void(0)" class="btn btn-icon btn-trigger toggle" data-target="cardTools"><em class="icon ni ni-menu-right"></em></a>
                                            <div class="toggle-content" data-content="cardTools">
                                                <ul class="btn-toolbar gx-2">
                                                    <li class="toggle-close">
                                                        <a href="javascript:void(0)" class="btn btn-icon btn-trigger toggle" data-target="cardTools"><em class="icon ni ni-arrow-left"></em></a>
                                                    </li>
                                                    <li>
                                                        <div class="dropdown">
                                                            <a href="javascript:void(0)" class="btn btn-trigger btn-icon dropdown-toggle" data-toggle="dropdown">
                                                                <div class="dot dot-primary"></div>
                                                                <em class="icon ni ni-filter-alt"></em>
                                                            </a>
                                                            </a>
                                                            <div class="filter-wg dropdown-menu dropdown-menu-xl dropdown-menu-right">
                                                                <div class="dropdown-head">
                                                                    <span class="sub-title dropdown-title">{{ __('Filter Users') }}</span>
                                                                </div>
                                                                <form action="{{ route('admin.users') }}" method="GET">
                                                                <div class="dropdown-body dropdown-body-rg">
                                                                    <div class="row gx-6 gy-3">
                                                                        <div class="col-6">
                                                                            <div class="custom-control custom-control-sm custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="hasBalance" name="hasBalance" value="true"{{ ($getHasBalance==true) ? ' checked' : '' }}>
                                                                                <label class="custom-control-label" for="hasBalance"> {{ __('Has Balance') }}</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <div class="custom-control custom-control-sm custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="email-verified" name="emailVerified" value="true"{{ ($getEmailVerified==true) ? ' checked' : '' }}>
                                                                                <label class="custom-control-label" for="email-verified"> {{ __('Email Verified') }}</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <div class="custom-control custom-control-sm custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="referral-join" name="referralJoin" value="true"{{ ($getReferralJoin==true) ? ' checked' : '' }}>
                                                                                <label class="custom-control-label" for="referral-join"> {{ __('Referral Join') }}</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <div class="form-group">
                                                                                <label class="overline-title overline-title-alt">{{ __('Role') }}</label>
                                                                                <select name="role" class="form-select form-select-sm">
                                                                                    <option value="any">
                                                                                        {{ __('Any Role') }}
                                                                                    </option>
                                                                                    <option value="admin"{{ ($getRole=='admin') ? ' selected' : '' }}>{{ __('Admin') }}</option>
                                                                                    <option value="user"{{ ($getRole=='user') ? ' selected' : '' }}>{{ __('User') }}</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <div class="form-group">
                                                                                <label class="overline-title overline-title-alt">{{ __('Status') }}</label>
                                                                                <select name="status" class="form-select form-select-sm">
                                                                                    <option value="any">{{ __('Any Status') }}</option>
                                                                                    <option value="inactive"{{ ($getStatus=='inactive') ? ' selected' : '' }}>{{ __('Inactive') }}</option>
                                                                                    <option value="active"{{ ($getStatus=='active') ? ' selected' : '' }}>{{ __('Active') }}</option>
                                                                                    <option value="locked"{{ ($getStatus=='locked') ? ' selected' : '' }}>{{ __('Locked') }}</option>
                                                                                    <option value="suspend"{{ ($getStatus=='suspend') ? ' selected' : '' }}>{{ __('Suspend') }}</option>
                                                                                    <option value="deleted"{{ ($getStatus=='deleted') ? ' selected' : '' }}>{{ __('Deleted') }}</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <div class="form-group">
                                                                                <label class="overline-title overline-title-alt">{{ __('Reg. Method') }}</label>
                                                                                <select name="regMethod" class="form-select form-select-sm">
                                                                                    <option value="any">{{ __('Any Method') }}</option>
                                                                                    <option value="direct"{{ ($getRegMethod=='direct') ? ' selected' : '' }}>{{ __('By Direct') }}</option>
                                                                                    <option value="email"{{ ($getRegMethod=='email') ? ' selected' : '' }}>{{ __('By Email') }}</option>
                                                                                    <option value="social"{{ ($getRegMethod=='social') ? ' selected' : '' }}>{{ __('By Social') }}</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="dropdown-foot between">
                                                                    <button type="submit" class="btn btn-secondary">{{ __('Filter') }}</button>
                                                                    <a href="{{ route('admin.users') }}" class="clickable">{{ __('Reset Filter') }}</a>
                                                                </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="dropdown">
                                                            <a href="javascript:void(0)" class="btn btn-trigger btn-icon dropdown-toggle" data-toggle="dropdown">
                                                                <em class="icon ni ni-setting"></em></a>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-xs dropdown-menu-right">
                                                                <ul class="link-check">
                                                                    <li><span>{{ __('Show') }}</span></li>
                                                                    @foreach(config('investorm.pgtn_pr_pg') as $item)
                                                                    <li class="update-meta{{ (user_meta('user_perpage', '10') == $item) ? ' active' : '' }}">
                                                                        <a href="#" data-value="{{ $item }}" data-meta="perpage" data-type="user">{{ __(ucfirst($item)) }}</a>
                                                                    </li>
                                                                    @endforeach
                                                                </ul>
                                                                <ul class="link-check">
                                                                    <li><span>{{ __('Order') }}</span></li>
                                                                    @foreach(config('investorm.pgtn_order') as $item)
                                                                    <li class="update-meta{{ (user_meta('user_order', 'desc') == $item) ? ' active' : '' }}">
                                                                        <a href="#" data-value="{{ $item }}" data-meta="order" data-type="user">{{ __(strtoupper($item)) }}</a>
                                                                    </li>
                                                                    @endforeach
                                                                </ul>
                                                                <ul class="link-check">
                                                                    <li><span>{{ __('Density') }}</span></li>
                                                                    @foreach(config('investorm.pgtn_dnsty') as $item)
                                                                    <li class="update-meta{{ (user_meta('user_display', 'regular') == $item) ? ' active' : '' }}">
                                                                        <a href="#" data-value="{{ $item }}" data-meta="display" data-type="user">{{ __(ucfirst($item)) }}</a>
                                                                    </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-search search-wrap{{ (request()->get('query', false)) ? ' active' : '' }}" data-search="search">
                            <div class="card-body">
                                <form action="{{ route('admin.users') }}">
                                    <div class="search-content">
                                        <a href="javascript:void(0)" class="search-back btn btn-icon toggle-search{{ (request()->get('query', false)) ? ' active' : '' }}" data-target="search"><em class="icon ni ni-arrow-left"></em></a>
                                        <input name="query" type="text" value="{{ request()->get('query', '') }}" class="form-control border-transparent form-focus-none" placeholder="{{ __('Search by user or email') }}">
                                        <button type="submit" class="search-submit btn btn-icon"><em class="icon ni ni-search"></em></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-inner p-0">
                        <div class="nk-tb-list nk-tb-ulist{{ user_meta('user_display') == 'compact' ? ' is-compact': '' }}">
                            <div class="nk-tb-item nk-tb-head">
                                <div class="nk-tb-col nk-tb-col-check">
                                    <div class="custom-control custom-control-sm custom-checkbox notext">
                                        <input type="checkbox" class="custom-control-input qs-checkbox" id="choose-by-all">
                                        <label class="custom-control-label" for="choose-by-all"></label>
                                    </div>
                                </div>
                                <div class="nk-tb-col"><span class="sub-text">{{ __('User') }}</span></div>
                                @if(!$isAdmin)
                                <div class="nk-tb-col tb-col-md">
                                    <span class="sub-text">{{ __("Account Balance") }} <em class="icon ni ni-info" data-toggle="tooltip" title="{{ __("Amount without locked") }}"></em></span>
                                </div>
                                @endif
                                <div class="nk-tb-col tb-col-sm"><span class="sub-text">{{ __('Verified') }}</span></div>
                                <div class="nk-tb-col tb-col-lg"><span class="sub-text">{{ __('Last Login') }}</span></div>
                                <div class="nk-tb-col tb-col-md"><span class="sub-text">{{ __('Status') }}</span></div>
                                <div class="nk-tb-col nk-tb-col-tools text-right">&nbsp;</div>
                            </div>

                            {{-- User list item --}}
                            @foreach($users as $user)
                                <div class="nk-tb-item">
                                    <div class="nk-tb-col nk-tb-col-check">
                                        <div class="custom-control custom-control-sm custom-checkbox notext">
                                            <input type="checkbox" class="custom-control-input qs-checkbox-i" data-uid="{{ $user->id }}" id="qs-checkbox-uid-{{ $user->id }}">
                                            <label class="custom-control-label" for="qs-checkbox-uid-{{ $user->id }}"></label>
                                        </div>
                                    </div>
                                    <div class="nk-tb-col w-500px">
                                        <a href="{{ (auth()->user()->id == $user->id) ? route('admin.profile.view') : route('admin.users.details', ['id' => $user->id, 'type' => 'personal']) }}">
                                            <div class="user-card">
                                                {!! user_avatar($user) !!}
                                                <div class="user-info">
                                                    <span class="tb-lead align-center">
                                                        <span class="user-info-name">{{ $user->name }}</span>
                                                        <span class="dot d-md-none ml-1{{ css_state($user->status, 'dot') }}"></span>
                                                        @if($user->role == uRole::SUPER_ADMIN)
                                                        <span class="badge badge-dim badge-danger ml-1">{{ __("Super Admin") }}</span>
                                                        @endif
                                                    </span>
                                                    <span>{{ str_protect($user->email) }}</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    @if(!$isAdmin)
                                    <div class="nk-tb-col tb-col-md">
                                        <span class="tb-amount">{{ money($user->balance(AccType('main')), $base_currency) }}</span>
                                    </div>
                                    @endif
                                    <div class="nk-tb-col tb-col-sm">
                                        <ul class="list-status">
                                            <li>
                                                <em class="icon ni {{ data_get($user, 'is_verified') ? 'text-success ni-check-circle' : 'ni-alert-circle' }}"></em> 
                                                <span>{{ __('Email') }}</span>
                                            </li>
                                            @if (in_array(data_get($user, 'kyc_status'), ['verified', 'pending', 'reject', 'resubmit']))
                                            <li>
                                                @if (data_get($user, 'kyc_verified') || data_get($user, 'kyc_rejected'))
                                                <em class="icon ni {{ data_get($user, 'kyc_verified') ? 'text-success ni-check-circle' : 'text-danger ni-cross-circle' }}"></em>
                                                @else
                                                <em class="icon ni ni-alert-circle"></em>
                                                @endif
                                                <span>{{ __('KYC') }}</span>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                    <div class="nk-tb-col tb-col-lg">
                                        @if($user->status !== uStatus::INACTIVE)
                                        <span>{!! !blank($user->last_login) ? $user->last_login->format('j F Y') : '<em class="small">'.__('Not yet').'</em>' !!}</span>
                                        @else 
                                        <span class="small font-italic">{{ __('Not verified yet') }}</span>
                                        @endif
                                    </div>
                                    <div class="nk-tb-col tb-col-md w-140px">
                                        <span class="tb-status u-status-{{$user->id . css_state($user->status, 'text') }}">{{ __(ucfirst($user->status)) }}</span>
                                    </div>
                                    <div class="nk-tb-col nk-tb-col-tools">
                                        <ul class="nk-tb-actions gx-1">
                                            @if($user->status !== uStatus::INACTIVE)
                                            <li class="nk-tb-action-hidden">
                                                <a href="{{ (auth()->user()->id == $user->id) ? route('admin.profile.view') : route('admin.users.details', ['id' => $user->id, 'type' => 'personal']) }}" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="{{ __('View Profile') }}">
                                                    <em class="icon ni ni-eye-fill"></em>
                                                </a>
                                            </li>
                                            @endif
                                            <li class="nk-tb-action-hidden">
                                                <a href="javascript:void(0)" class="btn btn-trigger btn-icon send-email" data-uid="{{ $user->id }}" data-toggle="tooltip" data-placement="top" title="{{ __('Send Email') }}">
                                                    <em class="icon ni ni-mail-fill"></em>
                                                </a>
                                            </li>
                                            @if(auth()->user()->id !== $user->id && $user->status !== uStatus::INACTIVE)
                                            <li class="nk-tb-action-hidden u-sw-suspend-{{ $user->id }}{{ ($user->status == uStatus::SUSPEND) ? ' d-none' : '' }}">
                                                <a class="btn btn-trigger btn-icon quick-action" data-action="suspend" data-uid="{{ $user->id }}" data-toggle="tooltip" data-placement="top" title="{{ __('Make as Suspend') }}">
                                                    <em class="icon ni ni-user-cross-fill"></em>
                                                </a>
                                            </li>
                                            <li class="nk-tb-action-hidden u-sw-active-{{ $user->id }}{{ ($user->status == uStatus::ACTIVE) ? ' d-none' : '' }}">
                                                <a class="btn btn-trigger btn-icon quick-action" data-action="active" data-uid="{{ $user->id }}" data-toggle="tooltip" data-placement="top" title="{{ __('Make as Active') }}">
                                                    <em class="icon ni ni-user-check-fill"></em>
                                                </a>
                                            </li>
                                            @endif
                                            <li>
                                                <div class="drodown">
                                                    <a href="javascript:void(0)" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <ul class="link-list-opt no-bdr">
                                                            @if($user->status !== uStatus::INACTIVE)
                                                            <li><a href="{{ (auth()->user()->id == $user->id) ? route('admin.profile.view') : route('admin.users.details', ['id' => $user->id, 'type' => 'personal']) }}"><em class="icon ni ni-eye"></em><span>{{ (auth()->user()->id == $user->id) ? __('View Profile') : __('View Details') }}</span></a></li>

                                                            @if(!in_array($user->role, [uRole::ADMIN, uRole::SUPER_ADMIN]))
                                                            <li><a href="{{ route('admin.users.details', ['id' => $user->id, 'type' => 'transactions']) }}"><em class="icon ni ni-repeat"></em><span>{{ __('Transaction') }}</span></a></li>
                                                            @endif

                                                            @else
                                                            <li><a class="quick-action" data-action="verification" data-uid="{{ $user->id }}"><em class="icon ni ni-send"></em><span>{{ __('Resend Verification') }}</span></a></li>
                                                            @endif

                                                            <li class="divider"></li>
                                                            <li><a href="javascript:void(0)" class="send-email" data-uid="{{ $user->id }}"><em class="icon ni ni-mail"></em><span>{{ __('Send an Email') }}</span></a></li>

                                                            @if((auth()->user()->id !== $user->id && $user->status !== uStatus::INACTIVE) && !(auth()->user()->role == uRole::ADMIN && $user->role == uRole::SUPER_ADMIN))
                                                            <li><a class="quick-action" data-action="password" data-uid="{{ $user->id }}"><em class="icon ni ni-shield-star"></em><span>{{ __('Reset Password') }}</span></a></li>
                                                            <li class="u-sw-suspend-{{ $user->id }}{{ ($user->status == uStatus::SUSPEND) ? ' d-none' : '' }}"><a class="quick-action" data-action="suspend" data-uid="{{ $user->id }}"><em class="icon ni ni-user-cross"></em><span>{{ __('Suspend User') }}</span></a></li>
                                                            <li class="u-sw-active-{{ $user->id }}{{ ($user->status == uStatus::ACTIVE) ? ' d-none' : '' }}"><a class="quick-action" data-action="active" data-uid="{{ $user->id }}"><em class="icon ni ni-user-check"></em><span>{{ __('Active User') }}</span></a></li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        {{-- User List item-end --}}
                        </div>
                    </div>
                    {{-- Pagination --}}
                    <div class="card-inner">
                        <div class="nk-block-between-md g-3">
                            {{ $users->appends(request()->all())->links('admin.user.pagination') }}
                        </div>
                    </div>
                </div>
            </div>
            @else 
            <div class="card card-bordered text-center">
                <div class="card-inner card-inner-lg py-5">
                    <h4>{{ __("No User Found") }}</h4>
                    <p>{{ __("We have not found any :status user, you can add new user.", ['status' => $byTypes]) }}</p>
                    <p><a data-toggle="modal" href="#add-new-user" data-toggle="modal" class="btn btn-primary"><em class="icon ni ni-user-add"></em><span>{{ __("Add User") }}</span></a></p>
                </div>
            </div>
            @endif
        </div>
    {{-- nk-block --}}
    </div>
@endsection

@push('modal')
{{-- User Add Modal --}}
<div class="modal fade" tabindex="-1" role="dialog" id="add-new-user">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <a href="javascript:void(0)" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            <div class="modal-body modal-body-md">
                <h4 class="title nk-modal-title">{{ __('Add New User') }}</h4>
                <form action="{{ route('admin.users.save') }}" method="POST" class="form-validate is-alter">
                    <div class="row gy-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="user-type">{{ __('User Type') }} <span class="text-danger">*</span></label>
                                <select name="role" id="user-type" class="form-select form-select-sm">
                                    <option value="user">{{ __('Regular User') }}</option>
                                    <option value="admin">{{ __('Admin User') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="full-name">{{ __('Full Name') }} <span class="text-danger">*</span></label>
                                <div class="form-control-wrap">
                                    <input type="text" name="name" class="form-control" id="full-name" placeholder="{{ __('Enter full name') }}" required maxlength="190">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="email">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                                <div class="form-control-wrap">
                                    <input type="email" name="email" autocomplete="off" class="form-control" id="email" placeholder="{{ __('Enter an email') }}" required>
                                </div>
                                <div class="form-note">{{ __("Email address should be unique.") }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="password">{{ __('Password') }}</label>
                                <div class="form-control-wrap">
                                    <input type="password" autocomplete="new-password" name="password" class="form-control" id="password" placeholder="{{ __('Password') }}">
                                </div>
                                <div class="form-note">{{ __("Generate a password if you leave blank.") }}</div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="verified" class="custom-control-input" id="required-verification">
                                <label class="custom-control-label" for="required-verification"> {{ __('Required Email Verification') }}</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                <li>
                                    <button type="button" id="user-add" class="btn btn-lg btn-primary">{{ __('Add User') }}</button>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" data-dismiss="modal" class="link link-light">{{ __('Cancel') }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Send Email Modal --}}
<div class="modal fade" tabindex="-1" role="dialog" id="send-email-user">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <a href="javascript:void(0)" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            <div class="modal-body modal-body-md">
                <h5 class="title nk-modal-title">{{ __('Send an Email') }}</h5>
                <form action="{{ route('admin.users.send.email') }}" method="POST" class="form-validate is-alter">
                    <input type="hidden" name="send_to" value="" id="userid">
                    <div class="row gy-4">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="email-subject">{{ __('Email Subject') }} <span class="text-danger">*</span></label>
                                <div class="form-control-wrap">
                                    <input type="text" name="subject" class="form-control form-control-lg" id="email-subject" placeholder="{{ __('Subject') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="greeting">{{ __('Email Greeting') }}</label>
                                <div class="form-control-wrap">
                                    <input type="text" name="greeting" class="form-control form-control-lg" id="greeting" placeholder="{{ __('Hello!') }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="message">{{ __('Your Message') }}<span class="text-danger">*</span></label>
                                <div class="form-control-wrap">
                                    <textarea name="message" id="message" class="form-control form-control-lg" placeholder="{{ __('Write your message') }}" required></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                <li>
                                    <button type="button" class="btn btn-lg btn-primary u-send-mail">
                                        <span>{{ __('Send Email') }}</span>
                                        <span class="spinner-border spinner-border-sm hide" role="status" aria-hidden="true"></span>
                                    </button>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" data-dismiss="modal" class="link link-light">{{ __('Cancel') }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script type="text/javascript">
    const quick_update = "{{ route('admin.users.action') }}", bulk_update = "{{ route('admin.users.action.bulk') }}", updateSetting = "{{ route('admin.profile.update') }}";
    const qmsg = { title: "{{ __('Are you sure?') }}", btn: {cancel: "{{ __('Cancel') }}", confirm: "{{ __('Confirm') }}"}, bulkbtn: {cancel: "{{ __('No') }}", confirm: "{{ __('Yes') }}"}, context: "{!! __("Do you want to perform this action?") !!}", action: {active: "{!! __("Do you want to actived the user account?") !!}", suspend: "{!! __("Do you want to suspend the user account?") !!}", password: "{!! __("Do you want to reset the password? Once you confirmed, new password will set into account and the user unable to login using existing password. A confirmation email will send to user after successfully reseted.") !!}", removed: "{!! __("Do you want to remove unverified user accounts? You cannot revert back this action, so please confirm that you want to delete permanently.") !!}", bulk: "{!! __("Do you want to update user profile with this bulk action?") !!}"} };
</script>
@endpush