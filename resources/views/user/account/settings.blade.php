@extends('user.layouts.master')

@section('title', __('Security Settings'))

@section('content')
    <div class="nk-content-body">
        <div class="nk-block-head">
            <div class="nk-block-head-content">
                <h2 class="nk-block-title fw-normal">{{ __('Security Settings') }}</h2>
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
            <div class="nk-block-head">
                <div class="nk-block-head-content">
                    <h5 class="nk-block-title">{{ __('Settings') }}</h5>
                    <div class="nk-block-des">
                        <p>{{ __('These settings are helps you keep your account secure.') }}</p>
                    </div>
                </div>
            </div>
            <div class="card card-bordered">
                <div class="card-inner-group">
                    <div class="card-inner">
                        <div class="between-center flex-wrap flex-md-nowrap g-3">
                            <div class="nk-block-text">
                                <h6>{{ __('Save my Activity Logs') }}</h6>
                                <p>{!! __('Save your all :page including unusual activity detected.', ['page' => '<a href="'.route('account.activity').'" class="link link-primary">'.__('activity logs').'</a>' ]) !!}</p>
                            </div>
                            <div class="nk-block-actions">
                                <ul class="align-center gx-3">
                                    <li class="order-md-last">
                                        <div class="custom-control custom-switch mr-n2">
                                            <input type="checkbox" name="activity_log" class="custom-control-input qup-profile"{{ (isset($metas["setting_activity_log"]) && ($metas["setting_activity_log"] == "on")) ? ' checked' : '' }} id="activity-log" data-key="setting">
                                            <label class="custom-control-label" for="activity-log"></label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-inner">
                        <div class="between-center flex-wrap flex-md-nowrap g-3">
                            <div class="nk-block-text">
                                <h6>{{ __('Email me if encounter unusual activity') }}</h6>
                                <p>{{ __('You will get email notification whenever encounter invalid login activity.') }}</p>
                            </div>
                            <div class="nk-block-actions">
                                <ul class="align-center gx-3">
                                    <li class="order-md-last">
                                        <div class="custom-control custom-switch mr-n2">
                                            <input type="checkbox" name="unusual_activity" class="custom-control-input qup-profile"{{ (isset($metas["setting_unusual_activity"]) && ($metas["setting_unusual_activity"] == "on")) ? ' checked' : '' }} id="unusual-activity" data-key="setting">
                                            <label class="custom-control-label" for="unusual-activity"></label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-inner">
                        <div class="between-center flex-wrap flex-md-nowrap g-3">
                            <div class="nk-block-text">
                                <h6>{{ __('Change Email Address') }}</h6>
                                <p>{{ __('Update your current email address to new email address.') }}</p>
                            </div>
                            <div class="nk-block-actions">
                                @if(auth()->user()->is_verified)
                                    @if(data_get($metas, 'user_new_email', false))
                                        <button type="button" data-toggle="modal" data-target="#confirm-email" class="btn btn-sm btn-secondary" id="email-modal-tgl">{{ __('Verify New Email') }}</button>
                                    @else
                                        <button type="button" data-toggle="modal" data-target="#change-email" class="btn btn-sm btn-primary" id="email-modal-tgl">{{ __('Change Email') }}</button>
                                    @endif
                                @else
                                    <ul class="align-center flex-wrap flex-sm-nowrap gx-3 gy-2">
                                        <li>
                                            <a href="javascript:void(0)" type="button" data-toggle="modal" data-target="#change-unverified-email" class="btn btn-sm btn-primary" id="email-modal-tgl">{{ __('Change Email') }}</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" type="button" data-toggle="modal" data-target="#send-verification-link" class="btn btn-sm btn-primary" id="email-modal-tgl">{{ __('Verify Your Email') }}</a>
                                        </li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-inner">
                        <div class="between-center flex-wrap flex-md-nowrap g-3">
                            <div class="nk-block-text">
                                <h6>{{ __('Change Password') }}</h6>
                                @if(data_get($metas, 'registration_method') !== 'social' || data_get($metas, 'last_password_changed'))
                                    <p>{{ __('Set a unique password to protect your account.') }}</p>
                                @else
                                    <p>
                                    {!! __('You must logout from your account and go to :page page.', ['page' => "<a href='".route('auth.forget.form')."'>". __('Forgot Password') ."</a>"
                                    ]) !!}
                                    </p>
                                @endif
                            </div>
                            <div class="nk-block-actions flex-shrink-sm-0">
                                @if(auth()->user()->is_verified)
                                    @if(data_get($metas, 'registration_method') !== 'social' || data_get($metas, 'last_password_changed'))
                                    <ul class="align-center flex-wrap flex-sm-nowrap gx-3 gy-2">
                                        <li class="order-md-last">
                                            <a href="javascript:void(0)" id="settings-change-password" data-toggle="modal" data-target="#change-password" class="btn btn-sm btn-primary">{{ __('Change Password') }}</a>
                                        </li>
                                        <li>
                                            <em class="text-soft text-date fs-12px">{!! __('Last changed: :date', ['date' => '<span>'.((data_get($metas, 'last_password_changed', false)) ? show_date(data_get($metas, 'last_password_changed', false)) : __('N/A')).'</span>' ]) !!}</em>
                                        </li>
                                    </ul>
                                    @endif
                                @else
                                    <ul class="align-center flex-wrap flex-sm-nowrap gx-3 gy-2">
                                        <li>
                                            <em class="text-danger text-date fs-13px">{{ __('You have to verify your email first.') }}</em>
                                        </li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-inner">
                        <div class="between-center flex-wrap flex-md-nowrap g-3">
                            <div class="nk-block-text">
                                <h6>
                                    {{ __('2FA Authentication') }}
                                    @if (data_get(auth()->user(), '2fa'))
                                        <span class="badge badge-success">{{ __('Enabled') }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ __('Disabled') }}</span>
                                    @endif
                                </h6>
                                <p>{{ __('Secure your account with 2FA security. When it is activated you will need to enter not only your password, but also a special code using your mobile.') }}</p>
                            </div>
                            <div class="nk-block-actions">
                                @if (data_get(auth()->user(), '2fa'))
                                    <a href="javascript:void(0)" id="settings-disable-2fa" data-toggle="modal" data-target="#disable-2fa" class="btn btn-sm btn-danger">{{ __('Disable') }}</a>
                                @else
                                    <a href="javascript:void(0)" id="settings-enable-2fa" data-toggle="modal" data-target="#enable-2fa" class="btn btn-sm btn-primary">{{ __('Enable') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(social_auth())
                @if(auth()->user()->has_social_auth)
                <div class="nk-block-head">
                    <div class="nk-block-head-content">
                        <h5 class="nk-block-title">{{ __('Social Account Linked') }}</h5>
                        <div class="nk-block-des">
                            <p>{{ __('Your account already connected with a social account. You can use your social account to login into your account.') }}</p>
                        </div>
                    </div>
                </div>
                @else
                <div class="nk-block-head">
                    <div class="nk-block-head-content">
                        <h5 class="nk-block-title">{{ __('Social Account') }}</h5>
                        <div class="nk-block-des">
                            <p>{{ __('You can connect with a social account from below social network to access your account using social login.') }}</p>
                        </div>
                    </div>
                </div>
                @endif
                @if(social_auth('facebook') && (!auth()->user()->has_social_auth || data_get($metas, 'social_account_facebook')))
                <h6 class="lead-text">{{ data_get($metas, 'social_account_facebook') ? __('Connected to :Network', ['network' => __('facebook')]) : __('Connect to :Network', ['network' => __('facebook')]) }}</h6>
                <div class="card card-bordered">
                    <div class="card-inner">
                        <div class="between-center flex-wrap flex-md-nowrap g-3">
                            <div class="media media-center gx-3 wide-xs">
                                <div class="media-object">
                                    <em class="icon icon-circle icon-circle-lg ni ni-facebook-f"></em>
                                </div>
                                <div class="media-content">
                                    @if(data_get($metas, 'social_account_facebook'))
                                    <p>{{ __('You have connected with your :social account.', ['social' => __('facebook')]) }}</p>
                                    @else
                                    <p>{{ __('You can connect with your :Social account.', ['social' => __('facebook')]) }} <em class="d-block text-soft">{{ __('Not connected yet') }}</em></p>
                                    @endif
                                </div>
                            </div>
                            <div class="nk-block-actions flex-shrink-0">
                                @if(data_get($metas, 'social_account_facebook'))
                                    <a href="javascript:void(0)" data-toggle="modal" data-platform="facebook" data-action="revoke" data-target="{{ empty(data_get($metas,'last_password_changed')) && data_get($metas, 'registration_method') === 'social' ? '#add-password' : '#social-account-modal' }}" class="btn btn-sm social-btn btn-danger">{{ __('Revoke Access') }}</a>
                                @else
                                    <a href="javascript:void(0)" data-toggle="modal" data-platform="facebook" data-action="link" data-target="#social-account-modal" class="btn btn-sm social-btn btn-primary">{{ __('Connect') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @if(social_auth('google') && (!auth()->user()->has_social_auth || data_get($metas, 'social_account_google')))
                <h6 class="lead-text">{{ data_get($metas, 'social_account_google') ? __('Connected to :Network', ['network' => __('google')]) : __('Connect to :Network', ['network' => __('google')]) }}</h6>
                <div class="card card-bordered">
                    <div class="card-inner">
                        <div class="between-center flex-wrap flex-md-nowrap g-3">
                            <div class="media media-center gx-3 wide-xs">
                                <div class="media-object">
                                    <em class="icon icon-circle icon-circle-lg ni ni-google"></em>
                                </div>
                                <div class="media-content">
                                    @if(data_get($metas, 'social_account_google'))
                                    <p>{{ __('You have successfully connected with your :social account, you can easily log in using your account too.', ['social' => __('google')]) }}</p>                                    
                                    @else
                                    <p>{{ __('You can connect with your :social account.', ['social' => __('google')]) }} <em class="d-block text-soft">{{ __('Not connected yet') }}</em></p>
                                    @endif
                                </div>
                            </div>
                            <div class="nk-block-actions flex-shrink-0">
                                @if(data_get($metas, 'social_account_google'))
                                    <a href="javascript:void(0)" data-toggle="modal" data-platform="google" data-action="revoke" data-target="{{ empty(data_get($metas,'last_password_changed')) && data_get($metas, 'registration_method') === 'social' ? '#add-password' : '#social-account-modal' }}" class="btn btn-sm btn-danger social-btn">{{ __('Revoke Access') }}</a>
                                @else
                                    <a href="javascript:void(0)" data-toggle="modal" data-platform="google" data-action="link" data-target="#social-account-modal" class="btn btn-sm social-btn btn-primary">{{ __('Connect') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @endif
        </div>
    </div>
@endsection
@push('modal')
    {{--  Change Email Modal --}}

    <div class="modal fade" tabindex="-1" role="dialog" id="change-email">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                <div class="modal-body modal-body-md">
                    <h5 class="title">{{ __('Change Email') }}</h5>
                    <form action="{{ route('account.settings.change.email') }}" method="POST" class="form-validate is-alter mt-4 form-authentic" id="change-email-form" autocomplete="off">
                        <div class="form-group">
                            <label class="form-label" for="email-address">{{ __('Current Email Address') }}</label>
                            <div class="form-control-wrap">
                                <input type="email" class="form-control form-control-lg" id="email-address" readonly value="{{ auth()->user()->email }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="new-email-address">{{ __('New Email Address') }}  <span class="text-danger">*</span></label>
                            <div class="form-control-wrap">
                                <input type="email" autocomplete="new-email" name="user_new_email" value="{{ $metas['user_new_email'] ?? '' }}" class="form-control form-control-lg" id="new-email-address" placeholder="{{ __('Enter Email Address') }}" required maxlength="190">
                            </div>
                            <div class="form-note">{{ __('New email address only updated once you verified.') }}</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="password">{{ __('Current Password') }}  <span class="text-danger">*</span></label>
                            <div class="form-control-wrap">
                                <input type="password" autocomplete="new-password" name="password" class="form-control form-control-lg" id="password" placeholder="{{ __('Enter current password') }}" required maxlength="190">
                            </div>
                        </div>
                        <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                            <li>
                                <button type="button" id="update-email" class="btn btn-md btn-primary">{{ __('Change Email') }}</button>
                            </li>
                            <li>
                                <a href="#" data-dismiss="modal" class="link link-light">{{ __('Cancel') }}</a>
                            </li>
                        </ul>
                        <div class="notes mt-gs">
                            <ul>
                                <li class="alert-note is-plain text-danger">
                                    <em class="icon ni ni-alert-circle"></em>
                                    <p>{{ __("We will send you a link to your new email address to confirm the change.") }}</p>
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- Email Confirmation Modal --}}
    <div class="modal fade" tabindex="-1" role="dialog" id="confirm-email">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                <div class="modal-body modal-body-lg">
                    <h5 class="title mb-3">{{ __('Verify Your Email') }}</h5>
                    <p id="email-confirm-msg">
                        @if(data_get($metas, 'user_new_email', false))
                            {!! __('Now we need to verify your new email address. We have sent an email to new email (:new_email) to verify your address. Please check your inbox (including spam folder) for the verification link.', ['new_email' => '<strong>'.data_get($metas, 'user_new_email', false).'</strong>']) !!}
                        @endif
                    </p>
                    <p class="mt-4 font-italic">{{ __('If you have not received a verification email, you can resend the verification mail or cancel the request.') }}
                    <ul class="align-center flex-wrap flex-sm-nowrap g-2">
                        <li>
                            <button class="btn btn-primary email-rq-verify" data-action="resend" data-url="{{ route('account.settings.change.email.resend') }}">{{ __('Resend Email') }}</button>
                        </li>
                        <li>
                            <button class="btn btn-dim btn-danger email-rq-verify" data-action="cancel" data-url="{{ route('account.settings.change.email.cancel') }}">{{ __('Cancel Request') }}</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    {{--  Change Password Modal --}}
    <div class="modal fade" tabindex="-1" role="dialog" id="change-password">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                <div class="modal-body modal-body-md">
                    <h5 class="title">{{ __('Change Password') }}</h5>
                    <form action="{{ route('account.settings.change.password') }}" method="POST" class="form-validate is-alter mt-4 form-authentic" id="change-password-form">
                        <div class="form-group">
                            <label class="form-label" for="current-password">{{ __('Current Password') }}  <span class="text-danger">*</span></label>
                            <div class="form-control-wrap">
                                <input type="password" name="current_password" class="form-control form-control-lg" id="current-password" placeholder="{{ __('Enter Current Password') }}" required maxlength="190">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="new-password">{{ __('New Password') }} <span class="text-danger">*</span></label>
                            <div class="form-control-wrap">
                                <input type="password" name="new_password" class="form-control form-control-lg" id="new-password" placeholder="{{ __('Enter new password') }}" required maxlength="190">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="new-password">{{ __('Retype New Password') }} <span class="text-danger">*</span></label>
                            <div class="form-control-wrap">
                                <input type="password" name="new_password_confirmation" class="form-control form-control-lg" id="new-password-confirmation" placeholder="{{ __('Retype new password') }}" required maxlength="190">
                            </div>
                        </div>
                        <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                            <li>
                                <button type="button" id="update-password" class="btn btn-primary">{{ __('Update Password') }}</button>
                            </li>
                            <li>
                                <a href="javascript:void(0)" data-dismiss="modal" class="link link-light">{{ __('Cancel') }}</a>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{--  Add Password Modal --}}
    @if (social_auth() && auth()->user()->has_social_auth && empty(data_get($metas, 'last_password_changed')))
    <div class="modal fade" tabindex="-1" role="dialog" id="add-password">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                <div class="modal-body modal-body-md">
                    <h5 class="title">{{ __('Set your password') }}</h5>
                    <p class="text">{{ __('In order to unlink your social account you need to set a password into your account.') }}</p>
                    <form action="{{ route('account.settings.add.password') }}" method="POST" class="form-validate is-alter mt-3 form-authentic" id="add-password-form">
                        <div class="form-group">
                            <label class="form-label" for="new-password">{{ __('New Password') }} <span class="text-danger">*</span></label>
                            <div class="form-control-wrap">
                                <input type="password" name="new_password" class="form-control form-control-lg" id="add-new-password" placeholder="{{ __('Enter new password') }}" required maxlength="190">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="new-password">{{ __('Retype New Password') }} <span class="text-danger">*</span></label>
                            <div class="form-control-wrap">
                                <input type="password" name="new_password_confirmation" class="form-control form-control-lg" id="add-new-password-confirmation" placeholder="{{ __('Retype new password') }}" required maxlength="190">
                            </div>
                        </div>
                        <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                            <li>
                                <button id="add-password-confirm" class="btn btn-primary ajax-submit">{{ __('Confirm') }}</button>
                            </li>
                            <li>
                                <a href="javascript:void(0)" data-dismiss="modal" class="link link-light">{{ __('Cancel') }}</a>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
    {{-- Password Change Confirmation Modal --}}
    <div class="modal fade" tabindex="-1" role="dialog" id="change-password-success">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body modal-body-lg text-center">
                    <div class="nk-modal">
                        <em class="nk-modal-icon icon icon-circle icon-circle-xxl ni ni-check bg-success"></em>
                        <h4 class="nk-modal-title title">{{ __("Password changed successfully!") }}</h4>
                        <div class="nk-modal-text">
                            <p class="caption-text">{{ __("Your password for your account has been successfully changed. You will need to sign in with your new password next time.") }}</p>
                            <p class="sub-text-sm">{{ __("Reset your password, if you forgot or lost.") }}</p>
                        </div>
                        <div class="nk-modal-action-lg">
                            <ul class="btn-group gx-4">
                                <li><a href="#" data-dismiss="modal" class="btn btn-mw btn-primary">{{ __("Return") }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Change Unverified Email Modal--}}
    <div class="modal fade" tabindex="-1" role="dialog" id="change-unverified-email">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                <div class="modal-body modal-body-md">
                    <h5 class="title">{{ __('Enter Your Valid Email Address') }}</h5>
                    <form action="{{ route('account.profile.update-unverified-email') }}" method="POST" class="form-validate is-alter mt-4 form-profile" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label class="form-label" for="current-email-address">{{ __('Current Email Address') }}</label>
                            <div class="form-control-wrap">
                                <input type="email" class="form-control form-control-lg" id="current-email-address" readonly value="{{ auth()->user()->email }}">
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

    @if (data_get(auth()->user(), '2fa'))
    {{-- 2fa disable modal --}}
    <div class="modal fade" id="disable-2fa" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                <div class="modal-body modal-body-md">
                    <h5 class="modal-title">{{ __('Disable 2FA Authentication') }}</h5>
                    <form action="{{ route('account.settings.2fa', 'disable') }}" method="POST" class="form-validate is-alter mt-3">
                        <p>{{ __("If you want to disable 2FA authentication from your account then enter your the current code.") }}</p>

                        <div class="form-group">
                            <label class="form-label" for="google2fa-code">{{ __('Enter Authenticator Code') }}</label>
                            <div class="form-control-wrap">
                                <input type="text" name="google2fa_code" class="form-control" id="google2fa-code" placeholder="{{ __('Enter the code to verify') }}" required>
                            </div>
                        </div>
                        <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                            <li>
                                @csrf
                                <button class="btn btn-danger ajax-submit">{{ __('Disable 2FA') }}</button>
                            </li>
                            <li>
                                <a href="javascript:void(0)" data-dismiss="modal" class="link link-light">{{ __('Cancel') }}</a>
                            </li>
                        </ul>
                        <div class="divider stretched md"></div>
                        <p class="text-danger"><strong>{{ __("Attention") }}:</strong> {{ __("If you disable the 2FA authentication then it won't ask you again the code when you login.") }}</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @else
    {{-- 2fa enable modal --}}
    <div class="modal fade" id="enable-2fa" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                <div class="modal-body modal-body-md">
                    <h5 class="modal-title">{{ __("Enable 2FA Authentication") }}</h5>
                    <form action="{{ route('account.settings.2fa', 'enable') }}" method="POST" class="form-validate is-alter mt-3" autocomplete="off">
                        <p>{!! __("Step 1: Install the Google Authenticator app from :google or :apple.", ['google' => '<a target="_blank" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2">'. __("Google Play") .'</a>', 'apple' => '<a target="_blank" href="https://apps.apple.com/us/app/google-authenticator/id388497605">'. __("App Store") .'</a>']) !!}</p>
                        <p>{{ __("Step 2: Scan below QR code by your Google Authenticator app, or you can add account manually into the app.") }}</p>

                        <div class="divider stretched sm"></div>

                        <div class="row align-center">
                            <div class="col-sm-8">
                                <p class="mb-1"><strong class="text-dark">{{ __("Manually add account:") }}</strong></p>
                                <p class="mb-1">{{ __("Account Name:") }}<br><strong class="text-dark">{{ site_info('name') }}</strong></p>
                                <p class="mb-1">{{ __("Your Key:") }}<br><strong class="text-dark">{{ $secret2fa }}</strong></p>
                            </div>
                            <div class="col-sm-4">
                                <div class="qr-media my-1 ml-sm-auto w-max-120px">
                                    {!! NioQR::generate($qrcode2fa, 200) !!}
                                </div>
                            </div>
                        </div>
                        <div class="divider stretched sm"></div>

                        <label class="form-label" for="google2fa-code">{{ __("Enter Authenticator Code") }}</label>
                        <div class="row g-2 align-center">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <input id="google2fa-code" type="text" class="form-control" name="google2fa_code" placeholder="{{ __("Enter the code to verify") }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    @csrf
                                    <input type="hidden" name="google2fa_secret" value="{{ $secret2fa }}">
                                    <button type="submit" class="btn btn-primary btn-block ajax-submit">{{ __("Confirm & Enable") }}</button>
                                </div>
                            </div>
                        </div>

                        <div class="divider stretched md"></div>
                        <p class="text-danger"><strong>{{ __("Attention") }}:</strong> {{ __("You will lose access of your account, if you lost your phone or uninstall the Google Authenticator app.") }}</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
    {{-- Social Account Modal --}}
    <div class="modal fade" tabindex="-1" role="dialog" id="social-account-modal">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                <div class="modal-body modal-body-md">
                    <h5 class="title"></h5>
                    <p class="text"></p>
                    <form action="" method="POST" class="form-validate is-alter mt-4 form-authentic" id="social-form">
                        <div class="form-group">
                            <label class="form-label" for="confirm-password">{{ __('Current Password') }} <span class="text-danger">*</span></label>
                            <div class="form-control-wrap">
                                <input type="password" name="password" class="form-control form-control-lg" id="confirm-password" placeholder="{{ __('Enter your password') }}" required maxlength="190">
                            </div>
                        </div>
                        <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                            <li>
                                @csrf
                                <button class="btn btn-primary ajax-submit">{{ __('Confirm & Authenticate') }}</button>
                            </li>
                            <li>
                                <a href="javascript:void(0)" data-dismiss="modal" class="link link-light">{{ __('Cancel') }}</a>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
@endpush

@push('scripts')
<script type="text/javascript">

    const profileSetting = "{{ route('account.settings.save') }}";

    const socialRoutes = {
        facebook:{
            revoke : "{{ route('account.settings.social',['facebook','revoke']) }}",
            link : "{{ route('account.settings.social',['facebook','link']) }}",
        },
        google:{
            revoke :"{{ route('account.settings.social',['google','revoke']) }}",
            link :"{{ route('account.settings.social',['google','link']) }}"
        }
    };

    const msg = {
        link: "{{ __('To link your social account, you must enter your current password to verify. You can login using your social account once it authenticated.') }}",
        revoke: "{{ __('To revoke your social account, you must enter your current password to verify. You can not login using your social account anymore once you revoke.') }}"
    }

    const title = {
        link: "{{ __('Link Social Account') }}",
        revoke: "{{ __('Revoke Social Account') }}"
    }

    $('.social-btn').on('click',function(){
        let platform = $(this).data('platform');
        let action = $(this).data('action');
        $('#social-form').prev().prev().text(title[action]);
        $('#social-form').prev().text(msg[action]);
        $('#social-form').attr('action',socialRoutes[platform][action]);
    });

</script>
@endpush