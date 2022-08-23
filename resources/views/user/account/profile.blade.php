@extends('user.layouts.master')

@section('title', __('Profile Info'))

@section('content')
    <div class="nk-content-body">
        <div class="nk-block-head">
            <div class="nk-block-head-content">
                <h2 class="nk-block-title fw-normal">{{ __('Profile Info') }}</h2>
                <div class="nk-block-des">
                    <p>{{ __('You have full control to manage your own account setting.') }}</p>
                </div>
            </div>
        </div>
        <ul class="nk-nav nav nav-tabs">
            @include('user.account.nav-tab')
        </ul>
        <div class="nk-block">
            @if(session('email-sent'))
                <div class="alert alert-pro alert-success alert-dismissible alert-icon">
                    <em class="icon ni ni-check-circle"></em>
                    <strong>{{ session('email-sent') }}</strong>
                    <button class="close" data-dismiss="alert"></button>
                </div>
            @endif
            {!! Panel::profile_alerts('verify_email', ['class' => 'alert-plain', 'type' => 'info', 'link_modal' => '#change-unverified-email', 'link_modal_verify' => '#send-verification-link']) !!}
            {!! Panel::profile_alerts('profile', ['class' => 'alert-plain', 'type' => 'primary', 'link_modal' => '#profile-edit']) !!}
            <div class="nk-block-head">
                <div class="nk-block-head-content">
                    <h5 class="nk-block-title">{{ __('Personal Information') }}</h5>
                    <div class="nk-block-des">
                        <p>{{ __('Basic info, like your name and address, that you use on our platform.') }}</p>
                    </div>
                </div>
            </div>
            {{-- .nk-block-head --}}
            <div class="card card-bordered">
                <div class="nk-data data-list">
                    <div class="data-item"{!! (!profile_lockable()) ? ' data-toggle="modal" data-target="#profile-edit"' : '' !!}>
                        <div class="data-col">
                            <span class="data-label">{{ __('Full Name') }}</span>
                            <span class="data-value">{{ auth()->user()->name }}</span>
                        </div>
                        <div class="data-col data-col-end">
                            <span class="data-more{{ (profile_lockable()) ? ' disable' : '' }}"><em class="icon ni ni-{{ (profile_lockable()) ? 'lock-alt' : 'forward-ios' }}"></em></span>
                        </div>
                    </div>
                    <div class="data-item" data-toggle="modal" data-target="#profile-edit">
                        <div class="data-col">
                            <span class="data-label">{{ __('Display Name') }}</span>
                            <span class="data-value">{{ $metas['profile_display_name'] ?? '' }}</span>
                        </div>
                        <div class="data-col data-col-end"><span class="data-more"><em class="icon ni ni-forward-ios"></em></span></div>
                    </div>
                    <div class="data-item">
                        <div class="data-col">
                            <span class="data-label">{{ __('Email') }}</span>
                            <span class="data-value">{{ auth()->user()->email }}</span>
                        </div>
                        <div class="data-col data-col-end"><span class="data-more disable"><em class="icon ni ni-lock-alt"></em></span></div>
                    </div>
                    <div class="data-item" data-toggle="modal" data-target="#profile-edit">
                        <div class="data-col">
                            <span class="data-label">{{ __('Phone Number') }}</span>
                            <span class="data-value{{ (empty($metas['profile_phone'])) ? ' text-soft font-italic' : '' }}">
                                {{ $metas['profile_phone'] ?? __('Not added yet') }}
                            </span>
                        </div>
                        <div class="data-col data-col-end"><span class="data-more"><em class="icon ni ni-forward-ios"></em></span></div>
                    </div>
                    <div class="data-item" data-toggle="modal" data-target="#profile-edit">
                        <div class="data-col">
                            <span class="data-label">{{ __('Telegram') }}</span>
                            <span class="data-value{{ (empty($metas['profile_telegram'])) ? ' text-soft font-italic' : '' }}">
                                {{ empty($metas['profile_telegram']) ? __('Not added yet') : "@".$metas['profile_telegram'] }}
                            </span>
                        </div>
                        <div class="data-col data-col-end"><span class="data-more"><em class="icon ni ni-forward-ios"></em></span></div>
                    </div>
                    <div class="data-item"{!! (!profile_lockable()) ? ' data-toggle="modal" data-target="#profile-edit"' : '' !!}>
                        <div class="data-col">
                            <span class="data-label">{{ __('Gender') }}</span>
                            <span class="data-value{{ (empty($metas['profile_gender'])) ? ' text-soft font-italic' : '' }}">
                                {{ !empty(data_get($metas, 'profile_gender')) ? __(ucfirst($metas['profile_gender'])) : __('Not added yet') }}
                            </span>
                        </div>
                        <div class="data-col data-col-end">
                            <span class="data-more{{ (profile_lockable()) ? ' disable' : '' }}"><em class="icon ni ni-{{ (profile_lockable()) ? 'lock-alt' : 'forward-ios' }}"></em></span>
                        </div>
                    </div>
                    <div class="data-item"{!! (!profile_lockable()) ? ' data-toggle="modal" data-target="#profile-edit"' : '' !!}>
                        <div class="data-col">
                            <span class="data-label">{{ __('Date of Birth') }}</span>
                            <span class="data-value{{ (empty($metas['profile_dob'])) ? ' text-soft font-italic' : '' }}">
                                {{ !empty(data_get($metas, 'profile_dob')) ? show_dob(data_get($metas, 'profile_dob')) : __('Not added yet') }}
                            </span>
                        </div>
                        <div class="data-col data-col-end">
                            <span class="data-more{{ (profile_lockable()) ? ' disable' : '' }}"><em class="icon ni ni-{{ (profile_lockable()) ? 'lock-alt' : 'forward-ios' }}"></em></span>
                        </div>
                    </div>
                    @if(!empty($metas['profile_nationality']) && $metas['profile_nationality'] != 'same')
                    <div class="data-item"{!! (!profile_lockable()) ? ' data-toggle="modal" data-target="#profile-edit" data-tab-target="#address"' : '' !!}>
                        <div class="data-col">
                            <span class="data-label">{{ __('Nationality') }}</span>
                            <span class="data-value">
                                {{ $metas['profile_nationality'] ?? '' }}
                            </span>
                        </div>
                        <div class="data-col data-col-end">
                            <span class="data-more{{ (profile_lockable()) ? ' disable' : '' }}"><em class="icon ni ni-{{ (profile_lockable()) ? 'lock-alt' : 'forward-ios' }}"></em></span>
                        </div>
                    </div>
                    @endif
                    <div class="data-item"{!! (!profile_lockable()) ? ' data-toggle="modal" data-target="#profile-edit" data-tab-target="#address"' : '' !!}>
                        <div class="data-col">
                            <span class="data-label">{{ __('Country') }} <em class="icon ni ni-info" data-toggle="tooltip" data-placement="right" title="{{ __('Your residential country') }}"></em></span>
                            <span class="data-value{{ (empty($metas['profile_country'])) ? ' text-soft font-italic' : '' }}">
                                {{ empty($metas['profile_country']) ? __('Not added yet') : $metas['profile_country'] }}
                            </span>
                        </div>
                        <div class="data-col data-col-end">
                            <span class="data-more{{ (profile_lockable()) ? ' disable' : '' }}"><em class="icon ni ni-{{ (profile_lockable()) ? 'lock-alt' : 'forward-ios' }}"></em></span>
                        </div>
                    </div>
                    <div class="data-item"{!! (!profile_lockable()) ? ' data-toggle="modal" data-target="#profile-edit" data-tab-target="#address"' : '' !!}>
                        <div class="data-col">
                            <span class="data-label">{{ __('Address') }}</span>
                            <span class="data-value">
                                @if (address_lines(auth()->user()->meta('addresses'))) 
                                    {{ address_lines(auth()->user()->meta('addresses')) }}
                                @else
                                    <span class="text-soft font-italic">{{ __('Not added yet') }}</span>
                                @endif
                            </span>
                        </div>
                        <div class="data-col data-col-end">
                            <span class="data-more{{ (profile_lockable()) ? ' disable' : '' }}"><em class="icon ni ni-{{ (profile_lockable()) ? 'lock-alt' : 'forward-ios' }}"></em></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    {{-- Profile Edit Modal --}}
    <div class="modal fade" role="dialog" id="profile-edit">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <a href="javascript:void(0)" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                <div class="modal-body modal-body-md">
                    <h4 class="title">{{ __('Update Profile') }}</h4>
                    <ul class="nk-nav nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#personal">{{ __('Personal') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#address">{{ __('Address') }}</a>
                        </li>
                    </ul>

                    @if (profile_lockable())
                    <div class="alert mt-4 alert-info p-2">
                        <div class="alert-text has-icon">
                            <em class="icon ni ni-info-fill"></em>
                            @if (auth()->user()->kyc_verified)
                            {{ __("You cannot change some of your personal info as your identity is already verified.") }}
                            @else
                            {{ __("You cannot change some of your personal details as your identity verification is under review.") }}
                            @endif
                        </div>
                    </div>
                    @endif

                    <div class="tab-content">
                        <div class="tab-pane active" id="personal">
                            <form action="{{ route('account.profile.personal') }}" method="POST" class="form-validate is-alter form-profile" id="profile-personal-form">
                                @csrf
                                <div class="row gy-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-label-group">
                                                <label class="form-label" for="full-name">{{ __('Full Name') }}  <span class="text-danger">*</span></label>
                                            </div>

                                            <div class="form-control-wrap">
                                                <input type="text" name="name" value="{{ auth()->user()->name }}" class="form-control form-control-lg" id="full-name" placeholder="{{ __('Enter Full name') }}" required maxlength="190"{{ (profile_lockable()) ? ' readonly' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-label-group">
                                                <label class="form-label" for="display-name">{{ __('Nice Name') }} <span class="text-danger">*</span></label>
                                            </div>

                                            <div class="form-control-wrap">
                                                <input type="text" name="profile_display_name" value="{{ $metas['profile_display_name'] ?? '' }}" class="form-control form-control-lg" id="display-name" placeholder="{{ __('Enter display name') }}" required maxlength="190">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-label-group">
                                                <label class="form-label" for="phone-no">{{ __('Phone Number') }} <span class="text-danger">*</span></label>
                                            </div>

                                            <div class="form-control-wrap">
                                                <input type="text" name="profile_phone" value="{{ $metas['profile_phone'] ?? '' }}" class="form-control form-control-lg" id="phone-no" placeholder="{{ __('Phone Number') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-label-group">
                                                <label class="form-label" for="telegram">{{ __('Telegram') }}</label>
                                            </div>

                                            <div class="form-control-wrap">
                                                <input type="text" name="profile_telegram" value="{{ $metas['profile_telegram'] ?? '' }}" class="form-control form-control-lg" id="telegram" placeholder="{{ __('Telegram') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="gender">{{ __('Gender') }}</label>
                                            <select name="profile_gender" class="form-select" id="gender" data-placeholder="{{ __('Please select') }}" data-ui="lg"{{ (profile_lockable()) ? ' disabled' : '' }}>
                                                    <option value=""></option>
                                                    <option value="male" {{ (isset($metas['profile_gender']) && $metas['profile_gender'] == 'male') ? ' selected' : '' }}>{{ __('Male') }}</option>
                                                    <option value="female" {{ (isset($metas['profile_gender']) && $metas['profile_gender'] == 'female') ? ' selected' : '' }}>{{ __('Female') }}</option>
                                                    <option value="other" {{ (isset($metas['profile_gender']) && $metas['profile_gender'] == 'other') ? ' selected' : '' }}>{{ __('Others') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="birth-day">{{ __('Date of Birth') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="profile_dob" value="{{ $metas['profile_dob'] ?? '' }}" data-date-start-date="-85y" data-date-end-date="-12y" class="form-control form-control-lg date-picker-alt" id="birth-day" required placeholder="{{ __('Enter your date of birth') }}"{{ (profile_lockable()) ? ' disabled' : '' }}>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" name="profile_display_full_name" class="custom-control-input" id="display-full-name"{{ (data_get($metas, 'profile_display_full_name') == 'on') ? ' checked' : '' }}>
                                            <label class="custom-control-label" for="display-full-name">{{ __('Use full name to display') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2 pt-2">
                                            <li>
                                                <button type="button" class="btn btn-lg btn-primary ua-updp" data-action="profile">{{ __('Update Profile') }}</button>
                                            </li>
                                            <li>
                                                <a href="#" data-dismiss="modal" class="link link-light">{{ __('Cancel') }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="address">
                            <form action="{{ route('account.profile.address') }}" method="POST" class="form-validate is-alter form-profile" id="profile-address-form">
                                @csrf
                                <div class="row gy-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-label-group">
                                                <label class="form-label" for="address-l1">{{ __('Address Line 1') }} <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="form-control-wrap">
                                                <input type="text" name="profile_address_line_1" class="form-control form-control-lg" id="address-l1" value="{{ $metas['profile_address_line_1'] ?? '' }}" required maxlength="190"{{ (profile_lockable()) ? ' disabled' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-label-group">
                                                <label class="form-label" for="address-l2">{{ __('Address Line 2') }}</label>
                                            </div>
                                            <div class="form-control-wrap">
                                                <input type="text" name="profile_address_line_2" class="form-control form-control-lg" id="address-l2" value="{{ $metas['profile_address_line_2'] ?? '' }}" maxlength="190"{{ (profile_lockable()) ? ' disabled' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-6">
                                        <div class="form-group">
                                            <div class="form-label-group">
                                                <label class="form-label" for="address-city">{{ __('City') }}</label>
                                            </div>
                                            <div class="form-control-wrap">
                                                <input type="text" name="profile_city" class="form-control form-control-lg" id="address-city" value="{{ $metas['profile_city'] ?? '' }}" maxlength="190"{{ (profile_lockable()) ? ' disabled' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-3">
                                        <div class="form-group">
                                            <div class="form-label-group">
                                                <label class="form-label" for="address-st">{{ __('State / Province') }} <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="form-control-wrap">
                                                <input type="text" name="profile_state" class="form-control form-control-lg" id="address-st" value="{{ $metas['profile_state'] ?? '' }}" required maxlength="190"{{ (profile_lockable()) ? ' disabled' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-3">
                                        <div class="form-group">
                                            <div class="form-label-group">
                                                <label class="form-label" for="address-zip">{{ __('Zip / Postal Code') }}</label>
                                            </div>
                                            <div class="form-control-wrap">
                                                <input type="text" name="profile_zip" class="form-control form-control-lg" id="address-zip" value="{{ $metas['profile_zip'] ?? '' }}" maxlength="50"{{ (profile_lockable()) ? ' disabled' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="address-county">{{ __('Country') }} <span class="small">({{ __('Residential') }})</span> <span class="text-danger">*</span></label>
                                            <select name="profile_country" class="form-select" id="address-county" data-ui="lg" data-placeholder="{{ __("Please select") }}" data-search="on"{{ (profile_lockable()) ? ' disabled' : '' }}>
                                                <option></option>
                                                @foreach($countries as $code => $country)
                                                    <option value="{{ $country }}"{{ (isset($metas['profile_country']) && $metas['profile_country'] == $country) ? ' selected' : '' }}>{{ config('countries')[$code] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="address-county">{{ __('Nationality') }} <span class="small">({{ __('Citizenship') }})</span></label>
                                            <select name="profile_nationality" class="form-select" id="nationality" data-ui="lg" data-search="on"{{ (profile_lockable()) ? ' disabled' : '' }}>
                                                <option value="same"{{ (isset($metas['profile_nationality']) && ($metas['profile_nationality'] == 'same' || empty($metas['profile_nationality']))) ? ' selected' : '' }}>{{ __('Same as Country') }}</option>
                                                @foreach(config('countries') as $item)
                                                    <option value="{{ $item }}"{{ (isset($metas['profile_nationality']) && $metas['profile_nationality'] == $item) ? ' selected' : '' }}>{{ $item }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row gy-4">
                                    <div class="col-12">
                                        <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2 pt-2">
                                            <li>
                                                <button type="button" class="btn btn-lg btn-primary ua-updp" data-action="address">{{ __('Update Address') }}</button>
                                            </li>
                                            <li>
                                                <a href="#" data-dismiss="modal" class="link link-light">{{ __('Cancel') }}</a>
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

    {{-- Change Unverified Email Modal --}}
    <div class="modal fade" tabindex="-1" role="dialog" id="change-unverified-email">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                <div class="modal-body modal-body-md">
                    <h5 class="title">{{ __('Enter Your Valid Email Address') }}</h5>
                    <form action="{{ route('account.profile.update-unverified-email') }}" method="POST" class="form-validate is-alter mt-4 form-profile" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label class="form-label" for="email-address">{{ __('Current Email Address') }}</label>
                            <div class="form-control-wrap">
                                <input type="email" class="form-control form-control-lg" id="email-address" readonly value="{{ auth()->user()->email }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="new-unverified-email-address">{{ __('New Email Address') }}  <span class="text-danger">*</span></label>
                            <div class="form-control-wrap">
                                <input type="email" id="new-unverified-email-address" autocomplete="new-email" name="user_new_unverified_email" class="form-control form-control-lg"  placeholder="{{ __('Enter Email Address') }}" required maxlength="190">
                            </div>
                        </div>
                        <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                            <li>
                                <button type="submit" class="btn btn-md btn-primary ua-updp" data-action="email">{{ __('Send Verification Email') }}</button>
                            </li>
                        </ul>
                        <div class="notes mt-gs">
                            <ul>
                                <li class="alert-note is-plain text-danger">
                                    <em class="icon ni ni-alert-circle"></em>
                                    <p>{{ __("Wether you verify your email or not, from next login you have to use your new email address.") }}</p>
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Send Verification Link for Unverified Email --}}
    <div class="modal fade" tabindex="-1" role="dialog" id="send-verification-link">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                <div class="modal-body modal-body-md">
                    <h5 class="title">{{ __('Resend Email Verification Link') }}</h5>
                    <form action="{{ route('account.profile.verify-unverified-email', auth()->user()) }}" method="POST" class="form-validate is-alter mt-4">
                        @csrf
                        <div class="form-group">
                            <p class="text-dark fs-16px"><strong>{{ __('Are you sure to proceed with email verification link for your exisiting email?') }}</strong></p>
                        </div>
                        <div class="form-group">
                            <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                <li>
                                    <button type="submit" class="btn btn-md btn-primary">{{ __('Send Verification Email') }}</button>
                                </li>
                            </ul>
                        </div>
                        <div class="notes mt-gs">
                            <ul>
                                <li class="alert-note is-plain text-danger">
                                    <em class="icon ni ni-alert-circle"></em>
                                    <p>{{ __("After verification, from next login you have to use your new verified email address.") }}</p>
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
<script type="text/javascript">
    const profileSetting = "{{ route('account.settings.save') }}";
</script>
@endpush
