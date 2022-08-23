@extends('admin.layouts.master')

@section('title', __('Admin Profile'))

@php 

use \App\Enums\UserRoles as uRole;

$authUser = auth()->user();

@endphp

@section('content')
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-head-content">
                <h3 class="nk-block-title page-title">{{ __('My Profile') }}</h3>
                <div class="nk-block-des">
                    <p>{{ __('Here is your basic info, personalized settings etc.') }}</p>
                </div>
            </div>
        </div>
        <div class="nk-block">
            <div class="card card-bordered card-stretch">
                <div class="card-aside-wrap">
                    <div class="card-content">
                        <ul class="nav nav-tabs nav-tabs-mb-icon nav-tabs-card d-lg-none">
                            <li class="nav-item">
                                <a class="nav-link{{ ($type=='personal') ? ' active' : '' }}" href="{{ route('admin.profile.view') }}"><em class="icon ni ni-user-fill-c"></em><span>{{ __("Personal") }}</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link{{ ($type=='settings') ? ' active' : '' }}" href="{{ route('admin.profile.view', ['settings']) }}"><em class="icon ni ni-lock-alt-fill"></em><span>{{ __("Setting") }}</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link{{ ($type=='activity') ? ' active' : '' }}" href="{{ route('admin.profile.view', ['activity']) }}"><em class="icon ni ni-activity-round-fill"></em><span>{{ __("Activity") }}</span></a>
                            </li>
                            <li class="nav-item nav-item-trigger d-xxl-none">
                                <a class="btn btn-icon btn-trigger" data-toggle="modal" data-target="#profile-edit"><em class="icon ni ni-edit-fill"></em></a>
                            </li>
                        </ul>
                        <div class="card-inner card-inner-lg">
                            @include('admin.profile.'.$type)
                        </div>
                    </div>
                    <div class="card-aside card-aside-left user-aside d-none d-lg-block">
                        <div class="card-inner-group" data-simplebar>
                            <div class="card-inner">
                                <div class="user-card user-card-s2">
                                    {!! user_avatar($authUser, 'xl') !!}
                                    <div class="user-info">
                                        <div class="badge badge-pill ucap{{ ($authUser->role==uRole::SUPER_ADMIN) ? ' badge-dim badge-danger' : ' badge-outline-light' }}">{{ __(str_replace(['-', '_'], ' ', $authUser->role). ' Account') }}</div>
                                        <h5>{{ $authUser->name }}</h5>
                                        <span class="sub-text">{{ $authUser->email }}</span>
                                    </div>
                                    <div class="user-actions pt-3">
                                        <ul class="btn-group is-multi g-2">
                                            <li>
                                                <a data-toggle="modal" data-target="#profile-edit" class="btn btn-outline-light btn-white"><em class="icon ni ni-edit-fill"></em><span>{{ __('Update Profile') }}</span></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-inner p-0">
                                <ul class="link-list-menu nav nav-tabs">
                                    <li><a class="@if($type == 'personal') active @endif" href="{{ route('admin.profile.view') }}"><em class="icon ni ni-user-fill-c"></em><span>{{ __("Personal Infomation") }}</span></a></li>
                                    <li><a class="@if($type == 'settings') active @endif" href="{{ route('admin.profile.view', ['settings']) }}"><em class="icon ni ni-lock-alt-fill"></em><span>{{ __("Security Setting") }}</span></a></li>
                                    <li><a class="@if($type == 'activity') active @endif" href="{{ route('admin.profile.view', ['activity']) }}"><em class="icon ni ni-activity-round-fill"></em><span>{{ __("Account Activity") }}</span></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    <div class="modal fade" role="dialog" id="profile-edit">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <a href="javascript:void(0)" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            <div class="modal-body modal-body-lg">
                <h5 class="title">{{ __('Update Profile') }}</h5>
                <ul class="nk-nav nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#personal">{{ __('Personal') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#address">{{ __('Address') }}</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="personal">
                        <form action="{{ route('admin.profile.update.personal') }}" method="POST" class="form-validate is-alter form-profile">
                            @csrf
                            <div class="row gy-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="full-name">{{ __('Full Name') }}  <span class="text-danger"> &nbsp;*</span></label>
                                        </div>

                                        <div class="form-control-wrap">
                                            <input type="text" name="name" value="{{ $authUser->name }}" class="form-control form-control-lg" id="full-name" placeholder="{{ __('Enter Full name') }}" required maxlength="190">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="display-name">{{ __('Display Name') }} <span class="text-danger"> &nbsp;*</span></label>
                                        </div>

                                        <div class="form-control-wrap">
                                            <input type="text" name="profile_display_name" value="{{ user_meta('profile_display_name') ?? '' }}" class="form-control form-control-lg" id="display-name" placeholder="{{ __('Enter display name') }}" required maxlength="190">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="phone-no">{{ __('Phone Number') }}</label>
                                        </div>

                                        <div class="form-control-wrap">
                                            <input type="text" name="profile_phone" value="{{ user_meta('profile_phone') ?? '' }}" class="form-control form-control-lg" id="phone-no" placeholder="{{ __('Phone Number') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="phone-no">{{ __('Telegram') }}</label>
                                        </div>

                                        <div class="form-control-wrap">
                                            <input type="text" name="profile_telegram" value="{{ user_meta('profile_telegram') ?? '' }}" class="form-control form-control-lg" id="telegram" placeholder="{{ __('Telegram') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="birth-day">{{ __('Date of Birth') }}</label>
                                        <input type="text" name="profile_dob" data-date-start-date="-85y" data-date-end-date="-12y" value="{{ user_meta('profile_dob') ?? '' }}" class="form-control form-control-lg date-picker-alt" id="birth-day" placeholder="{{ __('Enter your date of birth') }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" name="profile_display_full_name" class="custom-control-input" id="latest-sale" @if(user_meta('profile_display_full_name') == 'on') checked @endif>
                                        <label class="custom-control-label" for="latest-sale">{{ __('Use full name to display') }}</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                        <li>
                                            <a href="javascript:void(0)" class="btn btn-lg btn-primary ua-updp" data-action="profile">{{ __('Update Profile') }}</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" data-dismiss="modal" class="link link-light">{{ __('Cancel') }}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="address">
                        <form action="{{ route('admin.profile.update.address') }}" method="POST" class="form-validate is-alter form-profile">
                            @csrf
                            <div class="row gy-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="address-l1">{{ __('Address Line 1') }} <span class="text-danger"> &nbsp;*</span></label>
                                        </div>

                                        <div class="form-control-wrap">
                                            <input type="text" name="profile_address_line_1" class="form-control form-control-lg" id="address-l1" value="{{ user_meta('profile_address_line_1') ?? '' }}" required maxlength="190">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="address-l2">{{ __('Address Line 2') }}</label>
                                        </div>
                                        <div class="form-control-wrap">
                                            <input type="text" name="profile_address_line_2" class="form-control form-control-lg" id="address-l2" value="{{ user_meta('profile_address_line_2') ?? '' }}" maxlength="190">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="address-st">{{ __('State') }} <span class="text-danger"> &nbsp;*</span></label>
                                        </div>

                                        <div class="form-control-wrap">
                                            <input type="text" name="profile_state" class="form-control form-control-lg" id="address-st" value="{{ user_meta('profile_state') ?? '' }}" required maxlength="190">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="address-county">{{ __('Country') }} <span class="text-danger"> &nbsp;*</span></label>
                                        <select name="profile_country" class="form-select" id="address-county" data-ui="lg" data-placeholder="{{ __("Please select") }}" data-search="on">
                                            <option></option>
                                            @foreach(config('countries') as $item)
                                                <option value="{{ $item }}" @if(user_meta('profile_country') == $item) selected @endif>{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                        <li>
                                            <a href="javascript:void(0)" class="btn btn-lg btn-primary ua-updp" data-action="address">{{ __('Update Address') }}</a>
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
    </div>
    </div>
@endpush