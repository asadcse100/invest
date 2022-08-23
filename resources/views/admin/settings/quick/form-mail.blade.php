<form action="{{ route('admin.quick-setup.save') }}" class="form-settings" method="POST">
    <h5 class="title mb-3">{{ __('Email Notification') }}</h5>
    <div class="form-sets">
        <div class="form-group">
            <label class="form-label">{{ __('Email Recipient') }} <span>({{ __('Default') }})</span></label>
            <div class="form-control-wrap">
                <input type="text" class="form-control" name="mail_recipient" value="{{ sys_settings('mail_recipient') }}">
            </div>
            <div class="form-note">{{ __('All the email notification sent to this address.') }}</div>
        </div>
    </div>
    <div class="gap gap-md"></div>
    <h6 class="title mb-3">{{ __('Mailing Setting') }}</h6>
    <div class="form-sets">
        <div class="row gy-3">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">{{ __('Mailing Driver') }}</label>
                    <ul class="custom-control-group g-3 align-center">
                        <li class="w-150px">
                            <div class="custom-control custom-checkbox">
                                <input type="radio" class="custom-control-input" id="mailing-driver-mail" value="mail"
                                name="mail_driver"{{ (sys_settings('mail_driver', 'mail') == 'mail') ? ' checked=""' : '' }}>
                                <label class="custom-control-label" for="mailing-driver-mail">{{ __('Mail') }}</label>
                            </div>
                        </li>
                        <li class="w-150px">
                            <div class="custom-control custom-checkbox">
                                <input type="radio" class="custom-control-input" id="mailing-driver-smtp" value="smtp"
                                name="mail_driver"{{ (sys_settings('mail_driver') == 'smtp') ? ' checked=""' : '' }}>
                                <label class="custom-control-label" for="mailing-driver-smtp">{{ __('SMTP') }}</label>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">{{ __('SMTP HOST') }}</label>
                    <div class="form-control-wrap">
                        <input type="text" class="form-control" name="mail_smtp_host" value="{{ sys_settings('mail_smtp_host') }}">
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label">{{ __('SMTP Port') }}</label>
                    <div class="form-control-wrap">
                        <input type="text" class="form-control" placehsys_settingser="465" name="mail_smtp_port" value="{{ sys_settings('mail_smtp_port') }}">
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label">{{ __('SMTP Secure') }}</label>
                    <div class="form-control-wrap">
                        <input type="text" class="form-control" placehsys_settingser="ssl" name="mail_smtp_secure" value="{{ sys_settings('mail_smtp_secure') }}">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">{{ __('SMTP Username') }}</label>
                    <div class="form-control-wrap">
                        <input type="text" class="form-control" name="mail_smtp_user" value="{{ sys_settings('mail_smtp_user') }}">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">{{ __('SMTP Password') }}</label>
                    <div class="form-control-wrap">
                        <input type="password" autocomplete="new-password" class="form-control" placehsys_settingser="********" name="mail_smtp_password" value="{{ sys_settings('mail_smtp_password') }}">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">{{ __('Email From Name') }}</label>
                    <div class="form-control-wrap">
                        <input type="text" class="form-control" name="mail_from_name" value="{{ sys_settings('mail_from_name') }}">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">{{ __('Email From Address') }}</label>
                    <div class="form-control-wrap">
                        <input type="text" class="form-control" name="mail_from_email" value="{{ sys_settings('mail_from_email') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-sets mt-4">
        <div class="d-flex justify-between align-center">
            <div class="action">
                @csrf
                <input type="hidden" name="form_next" value="mailer">
                <input type="hidden" name="form_type" value="mail-setting">
                <input type="submit" class="btn btn-primary submit-settings" value="{{ __('Update & Next') }}">
            </div>
        </div>
    </div>
</form>