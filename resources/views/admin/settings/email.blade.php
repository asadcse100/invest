@extends('admin.layouts.master')
@section('title', __('Email Configuration'))

@section('has-content-sidebar', 'has-content-sidebar')

@section('content-sidebar')
    @include('admin.settings.content-sidebar')
@endsection

@section('content')
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">{{ __('Email Configuration') }}</h3>
                    <p>{{ __('Setup your email system that used in application.') }}</p>
                </div>
                <div class="nk-block-head-content">
                    <ul class="nk-block-tools gx-1">
                        <li class="d-lg-none">
                            <a href="#" class="btn btn-icon btn-trigger toggle" data-target="pageSidebar"><em class="icon ni ni-menu-right"></em></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="nk-block card card-bordered nk-block-mh">
            <div class="card-inner">
                <form action="{{ route('admin.save.app.settings') }}" class="form-settings" method="POST">
                    <div class="form-sets wide-sm">
                        <div class="card-head">
                            <h5 class="card-title">{{ __('Email Notification') }}</h5>
                        </div>
                        <div class="row gy-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Email Recipient') }} <span>({{ __('Default') }})</span></label>
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" name="mail_recipient" value="{{ sys_settings('mail_recipient') }}">
                                    </div>
                                    <div class="form-note">{{ __('By default, all the email notification sent to this address.') }} <br>{{ __('If leave blank then notification sent to site email.') }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Email Recipient') }} <span>({{ __('Alternet') }})</span></label>
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" name="mail_recipient_alter" value="{{ sys_settings('mail_recipient_alter') }}">
                                    </div>
                                    <div class="form-note">{{ __('You can specify this email optionally on each email notification.') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="form-sets wide-sm">
                        <div class="card-head">
                            <h5 class="card-title">{{ __('Mailing Setting') }}</h5>
                        </div>
                        <div class="row gy-3">
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
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Email Global Footer') }}</label>
                                    <div class="form-control-wrap">
                                        <textarea class="form-control" name="mail_global_footer">{{ sys_settings('mail_global_footer') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="form-sets wide-sm">
                        <div class="card-head">
                            <h5 class="card-title">{{ __('Configuration') }}</h5>
                        </div>
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
                                        <input type="text" class="form-control" placeholder="465" name="mail_smtp_port" value="{{ sys_settings('mail_smtp_port') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">{{ __('SMTP Secure') }}</label>
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" placeholder="ssl" name="mail_smtp_secure" value="{{ sys_settings('mail_smtp_secure') }}">
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
                                        <input type="password" autocomplete="new-password" class="form-control" placeholder="********" name="mail_smtp_password" value="{{ sys_settings('mail_smtp_password') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="form-group">
                                    @csrf
                                    <input type="hidden" name="form_type" value="email-settings">
                                    <button type="button" class="btn btn-primary submit-settings" disabled="">
                                        <span class="spinner-border spinner-border-sm hide" role="status" aria-hidden="true"></span>
                                        <span>{{ __('Update') }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="divider"></div>
                <form action="{{ route('admin.settings.email.test') }}" class="form-settings wide-sm">
                    <label class="form-label" for="email-to-test">{{ __('Test Email Address') }}</label>
                    <div class="row mt-4 gy-2">
                        <div class="col-sm-8 col-md-6">
                            <div class="form-control-wrap">
                                <input type="text" name="send_to" class="form-control">
                                <input type="hidden" name="slug" value="users-welcome-email">
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-6">
                            <div class="form-control-wrap">
                                <button type="button" class="btn btn-primary send-test-mail" disabled="">
                                    <span class="spinner-border spinner-border-sm hide" role="status" aria-hidden="true"></span>
                                    <span>{{ __('Send Test Email') }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script type="text/javascript">
    const mail_sent_url = "{{ route('admin.settings.email.test') }}";
</script>
@endpush