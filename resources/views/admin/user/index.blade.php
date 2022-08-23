@extends('admin.layouts.master')

@php 

use \App\Enums\UserStatus as uStatus;
use \App\Enums\UserRoles as uRole;

$base_currency = base_currency();
@endphp

@section('content')
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between g-3">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">{{ __('User') }} / <span class="text-primary small">{{ __($user->name) }}</span></h3>
                    <div class="nk-block-des text-soft">
                        <ul class="list-inline">
                            <li>{{ __('User ID:') }} <span class="text-base">{{ the_uid($user->id) }}</span></li>
                            <li>{{ __('Email:') }} <span class="text-base">{{ $user->is_verified ? __('Verified') : __('Pending') }}</span></li>
                            <li>{{ __('Account Status:') }} <span class="text-base">{{ __(ucfirst($user->status)) }}</span></li>
                        </ul>
                    </div>
                </div>
                <div class="nk-block-head-content">
                    <a href="{{ (in_array($user->role, [uRole::ADMIN, uRole::SUPER_ADMIN])) ? route('admin.users.administrator') : route('admin.users') }}" class="btn btn-outline-light bg-white d-none d-sm-inline-flex"><em class="icon ni ni-arrow-left"></em><span>{{ __('Back') }}</span></a>
                    <a href="{{ (in_array($user->role, [uRole::ADMIN, uRole::SUPER_ADMIN])) ? route('admin.users.administrator') : route('admin.users') }}" class="btn btn-icon btn-outline-light bg-white d-inline-flex d-sm-none"><em class="icon ni ni-arrow-left"></em></a>
                </div>
            </div>
        </div>
        {{-- nk-block-head --}}
        <div class="nk-block">
            <div class="card card-bordered card-stretch">
                <div class="card-aside-wrap">
                    <div class="card-content">
                        <ul class="nav nav-tabs nav-tabs-mb-icon nav-tabs-card">
                            <li class="nav-item{{ ($type == 'personal') ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.users.details', ['id' => $user->id, 'type' => 'personal']) }}"><em class="icon ni ni-user-circle"></em><span>{{ __('Personal') }}</span></a>
                            </li>
                            @if(!in_array($user->role, [uRole::ADMIN, uRole::SUPER_ADMIN]))
                            <li class="nav-item{{ ($type == 'transactions') ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.users.details', ['id' => $user->id, 'type' => 'transactions']) }}"><em class="icon ni ni-repeat"></em><span>{{ __('Transactions') }}</span></a>
                            </li>
                            @endif
                            @if(!in_array($user->role, [uRole::ADMIN, uRole::SUPER_ADMIN]))
                            <li class="nav-item{{ ($type == 'investments') ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.users.details', ['id' => $user->id, 'type' => 'investments']) }}"><em class="icon ni ni-invest"></em><span>{{ __('Investments') }}</span></a>
                            </li>
                            @endif
                            @if(!in_array($user->role, [uRole::ADMIN, uRole::SUPER_ADMIN]))
                            <li class="nav-item{{ ($type == 'referrals') ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.users.details', ['id' => $user->id, 'type' => 'referrals']) }}"><em class="icon ni ni-users"></em><span>{{ __('Referrals') }}</span></a>
                            </li>
                            @endif
                            <li class="nav-item{{ ($type == 'activities') ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.users.details', ['id' => $user->id, 'type' => 'activities']) }}"><em class="icon ni ni-activity"></em><span>{{ __('Activities') }}</span></a>
                            </li>
                            <li class="nav-item nav-item-trigger d-xxl-none">
                                <a href=javascript:void(0) class="toggle btn btn-icon btn-trigger" data-target="userAside"><em class="icon ni ni-user-list-fill"></em></a>
                            </li>
                        </ul>
                        <div class="card-inner">
                            @include('admin.user.'.$type)
                        </div>
                    </div>
                    <div class="card-aside card-aside-right user-aside toggle-slide toggle-slide-right toggle-break-xxl" data-content="userAside" data-toggle-screen="xxl" data-toggle-overlay="true" data-toggle-body="true">
                        <div class="card-inner-group" data-simplebar>
                            <div class="card-inner">
                                <div class="user-card user-card-s2">
                                    {!! user_avatar($user, 'lg') !!}
                                    <div class="user-info">
                                        <div class="badge badge-pill ucap{{ ($user->role==uRole::SUPER_ADMIN) ? ' badge-dim badge-danger' : ' badge-outline-light' }}">
                                            {{ __(str_replace(['-', '_'], ' ', $user->role)) }}
                                        </div>
                                        <h5>{{ $user->name }}</h5>
                                        <span class="sub-text">{{ str_protect($user->email) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-inner card-inner-sm">
                                <ul class="btn-toolbar justify-center gx-1">
                                    <li><a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="{{ __('Send Email') }}" class="btn btn-trigger btn-icon send-email" data-uid="{{ $user->id }}"><em class="icon ni ni-mail"></em></a></li>

                                    @if(auth()->user()->id !== $user->id)
                                    <li><a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="{{ __('Reset Password') }}" class="btn btn-trigger btn-icon quick-action" data-action="password" data-uid="{{ $user->id }}"><em class="icon ni ni-shield-star"></em></a></li>

                                    @if($user->status!=uStatus::LOCKED)
                                    <li><a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="{{ __('Make as Locked') }}" class="btn btn-trigger btn-icon quick-action" data-action="locked" data-reload="1" data-uid="{{ $user->id }}"><em class="icon ni ni-shield-off"></em></a></li>
                                    @endif

                                    @if($user->status!=uStatus::ACTIVE)
                                    <li><a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="{{ __('Make as Active') }}" class="btn btn-trigger btn-icon quick-action" data-action="active" data-reload="1" data-uid="{{ $user->id }}"><em class="icon ni ni-user-check"></em></a></li>
                                    @endif

                                    @if($user->status!=uStatus::SUSPEND)
                                    <li><a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="{{ __('Make as Suspend') }}" class="btn btn-trigger btn-icon text-danger quick-action" data-action="suspend" data-reload="1" data-uid="{{ $user->id }}"><em class="icon ni ni-na"></em></a></li>
                                    @endif

                                    @else 
                                    <li><a href="{{ route('admin.profile.view') }}" data-toggle="tooltip" data-placement="top" title="{{ __('View My Profile') }}" class="btn btn-trigger btn-icon"><em class="icon ni ni-account-setting-alt"></em></a></li>
                                    @endif
                                </ul>
                            </div>
                            @if($user->role==uRole::USER)
                            <div class="card-inner">
                                <div class="overline-title-alt mb-2">{{ __("Main Account") }}</div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="profile-stats">
                                            <span class="amount">{{ money($user->balance(AccType('main')), '') }} <small class="currency">{{ $base_currency }}</small></span>
                                            <span class="sub-text">{{ __("Available Balance") }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="profile-stats">
                                            <span class="amount">{{ money($user->balance('locked_amount'), '') }}</span>
                                            <span class="sub-text">{{ __("Locked Amount") }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-inner">
                                <div class="overline-title-alt mb-2">{{ __("Invested Account") }}</div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="profile-stats">
                                            <span class="amount">{{ money($user->balance(AccType('invest')), '') }} <small class="currency">{{ $base_currency }}</small></span>
                                            <span class="sub-text">{{ __("Investment Wallet") }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="profile-stats">
                                            <span class="amount">{{ money($user->balance('active_invest'), '') }}</span>
                                            <span class="sub-text">{{ __("Active Investment") }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="card-inner">
                                <h6 class="overline-title-alt mb-2">{{ __('Additional') }}</h6>
                                <div class="row g-3">
                                    <div class="col-6">
                                        <span class="sub-text">{{ __('User ID:') }}</span>
                                        <span>{{ the_uid($user->id) }}</span>
                                    </div>
                                    <div class="col-6">
                                        <span class="sub-text">{{ __('Last Login:') }}</span>
                                        <span>{!! ($user->last_login) ? show_date($user->last_login) : '<em class="small text-soft">'.__('Not yet').'</em>' !!}</span>
                                    </div>
                                    <div class="col-6">
                                        <span class="sub-text">{{ __('Email Status:') }}</span>
                                        @if($user->is_verified)
                                            <span class="lead-text text-success">{{ __('Verified') }}</span>
                                        @else
                                            <span class="lead-text text-info">{{ __('Pending') }}</span>
                                        @endif
                                    </div>
                                    @if (in_array(data_get($user, 'kyc_status'), ['verified', 'pending', 'reject', 'resubmit']))
                                    <div class="col-6">
                                        <span class="sub-text">{{ __('KYC Status:') }}</span>
                                        @if (data_get($user, 'kyc_verified'))
                                            <span class="lead-text text-success">{{ __('Verified') }}</span>
                                        @elseif (data_get($user, 'kyc_rejected'))
                                            <span class="lead-text text-danger">{{ __('Rejected') }}</span>
                                        @else
                                            <span class="lead-text text-info">{{ __('Pending') }}</span>
                                        @endif
                                    </div>
                                    @endif
                                    <div class="col-6">
                                        <span class="sub-text">{{ __('Register At:') }}</span>
                                        <span>{{ show_date($user->created_at, true) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- card-aside --}}
                </div>
            </div>
        </div>
        {{-- nk-block --}}
    </div>
@endsection

@push('modal')
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
    const quick_update = "{{ route('admin.users.action') }}";
    const qmsg = { title: "{{ __('Are you sure?') }}", btn: {cancel: "{{ __('Cancel') }}", confirm: "{{ __('Confirm') }}"}, context: "{!! __("Do you want to perform this action?") !!}", action: {active: "{!! __("Do you want to actived the user account?") !!}", suspend: "{!! __("Do you want to suspend the user account?") !!}", password: "{!! __("Do you want to reset the password? Once you confirmed, new password will set into account and the user unable to login using existing password. A confirmation email will send to user after successfully reseted.") !!}"} };
</script>
@endpush