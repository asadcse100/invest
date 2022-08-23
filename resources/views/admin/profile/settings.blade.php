<div class="nk-block-head">
    <h4 class="nk-block-title">{{ __('Security Setting') }}</h4>
    <div class="nk-block-des">
        <p>{{ __('These settings are helps you keep your account secure.') }}</p>
    </div>
</div>
<div class="nk-block">
    <div class="card-inner-group mt-n3">
        <div class="card-inner px-0">
            <div class="between-center flex-wrap flex-md-nowrap g-3">
                <div class="nk-block-text">
                    <h6>{{ __('Save my Activity Logs') }}</h6>
                    <p>{!! __('Save your all :link including unusual activity detected.', ['link' => '<a href="'.route('admin.profile.view', ['activity']).'" class="link link-primary">'.__('activity logs').'</a>']) !!}</p>
                </div>
                <div class="nk-block-actions">
                    <ul class="align-center gx-3">
                        <li class="order-md-last">
                            <div class="custom-control custom-switch mr-n2">
                                <input type="checkbox" name="activity_log" class="custom-control-input qup-profile"{{ ((user_meta('setting_activity_log')=="on") ? ' checked' : '') }} id="activity-log" data-key="setting">
                                <label class="custom-control-label" for="activity-log"></label>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-inner px-0">
            <div class="between-center flex-wrap flex-md-nowrap g-3">
                <div class="nk-block-text">
                    <h6>{{ __('Email me if encounter unusual activity') }}</h6>
                    <p>{{ __('You will get email notification whenever encounter invalid login activity.') }}</p>
                </div>
                <div class="nk-block-actions">
                    <ul class="align-center gx-3">
                        <li class="order-md-last">
                            <div class="custom-control custom-switch mr-n2">
                                <input type="checkbox" name="unusual_activity"{{ ((user_meta('setting_unusual_activity')=="on") ? ' checked' : '') }} class="custom-control-input qup-profile" id="unusual-activity" data-key="setting">
                                <label class="custom-control-label" for="unusual-activity"></label>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-inner px-0">
            <div class="between-center flex-wrap flex-md-nowrap g-3">
                <div class="nk-block-text">
                    <h6>{{ __('Change Email Address') }}</h6>
                    <p>{{ __('Update your current email address to new email address.') }}</p>
                </div>
                <div class="nk-block-actions">
                    <div class="nk-block-actions">
                        @if(user_meta('user_new_email', false))
                        <button type="button" data-toggle="modal" data-target="#confirm-email" class="btn btn-sm btn-secondary" id="email-modal-tgl">{{ __('Verify New Email') }}</button>
                        @else
                        <button type="button" data-toggle="modal" data-target="#change-email" class="btn btn-sm btn-primary" id="email-modal-tgl">{{ __('Change Email') }}</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card-inner px-0">
            <div class="between-center flex-wrap flex-md-nowrap g-3">
                <div class="nk-block-text">
                    <h6>{{ __('Change Password') }}</h6>
                    <p>{{ __('Set a unique password to protect your account.') }}</p>
                </div>
                <div class="nk-block-actions flex-shrink-sm-0">
                    <ul class="align-center flex-wrap flex-sm-nowrap gx-3 gy-2">
                        <ul class="align-center flex-wrap flex-sm-nowrap gx-3 gy-2">
                            <li class="order-md-last">
                                <a href="javascript:void(0)" id="settings-change-password" data-toggle="modal" data-target="#change-password" class="btn btn-sm btn-primary">{{ __('Change Password') }}</a>
                            </li>
                            <li>
                                <em class="text-soft text-date fs-12px">
                                    {!! __('Last changed: :date', ['date' => '<span>'.((user_meta('last_password_changed')) ? show_date(user_meta('last_password_changed')) : __('N/A')).'</span>' ]) !!}
                                </em>
                            </li>
                        </ul>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-inner px-0">
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

@push('modal')
    {{--  Change Email Modal --}}
    <div class="modal fade" tabindex="-1" role="dialog" id="change-email">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                <div class="modal-body modal-body-md">
                    <h5 class="title">{{ __('Change Email') }}</h5>
                    <form action="{{ route('admin.profile.settings.change.email') }}" method="POST" class="form-validate is-alter mt-4 form-authentic" id="change-email-form" autocomplete="off">
                        <div class="form-group">
                            <label class="form-label" for="email-address">{{ __('Current Email Address') }}</label>
                            <div class="form-control-wrap">
                                <input type="email" class="form-control form-control-lg" id="email-address" readonly value="{{ auth()->user()->email }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="new-email-address">{{ __('New Email Address') }}  <span class="text-danger">*</span></label>
                            <div class="form-control-wrap">
                                <input type="email" autocomplete="new-email" name="user_new_email" value="{{ user_meta('user_new_email', '') }}" class="form-control form-control-lg" id="new-email-address" placeholder="{{ __('Enter Email Address') }}" required maxlength="190">
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
                        @if(user_meta('user_new_email', false))
                            {!! __('Now we need to verify your new email address. We have sent an email to new email (:new_email) to verify your address. Please check your inbox (including spam folder) for the verification link.', ['new_email' => '<strong>'.user_meta('user_new_email', false).'</strong>']) !!}
                        @endif
                    </p>
                    <p class="mt-4 font-italic">{{ __('If you have not received a verification email, you can resend the verification mail or cancel the request.') }}
                    <ul class="align-center flex-wrap flex-sm-nowrap g-2">
                        <li>
                            <button class="btn btn-primary email-rq-verify" data-action="resend" data-url="{{ route('admin.profile.settings.change.email.resend') }}">{{ __('Resend Email') }}</button>
                        </li>
                        <li>
                            <button class="btn btn-dim btn-danger email-rq-verify" data-action="cancel" data-url="{{ route('admin.profile.settings.change.email.cancel') }}">{{ __('Cancel Request') }}</button>
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
                    <form action="{{ route('admin.profile.settings.change.password') }}" method="POST" class="form-validate is-alter mt-4 form-authentic" id="change-password-form">
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

    @if (data_get(auth()->user(), '2fa'))
    {{-- 2fa disable modal --}}
    <div class="modal fade" id="disable-2fa" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                <div class="modal-body modal-body-md">
                    <h5 class="modal-title">{{ __('Disable 2FA Authentication') }}</h5>
                    <form action="{{ route('admin.profile.settings.2fa', 'disable') }}" method="POST" class="form-validate is-alter mt-3">
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
                    <form action="{{ route('admin.profile.settings.2fa', 'enable') }}" method="POST" class="form-validate is-alter mt-3" autocomplete="off">
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

@endpush

@push('scripts')
<script type="text/javascript">
    const profileSetting = "{{ route('admin.profile.settings.save') }}";
</script>
@endpush