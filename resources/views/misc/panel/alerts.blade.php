@php
$user = auth()->user();

$alert_class = (isset($attr['class']) && !empty($attr['class'])) ? ' '.$attr['class'] : '';
$alert_type = (isset($attr['type']) && !empty($attr['type'])) ? $attr['type'] : '';

$wd_account = true;
$is_profile = true;

$is_email_verified = "verified";

$link_modal = (isset($attr['link_modal']) && !empty($attr['link_modal'])) ? $attr['link_modal'] : false;
$link_modal_verify = (isset($attr['link_modal_verify']) && !empty($attr['link_modal_verify'])) ? $attr['link_modal_verify'] : false;

$kyc_alert = Panel::profile_module_alert('BasicKYC', ['type' => 'warning', 'class' => 'alert-thick alert-plain']);

@endphp

@if(($wd_account || $is_profile || $kyc_alert) && in_array($alert, ['any', 'account', 'profile']))
<div class="nk-block">
    @if($wd_account && in_array($alert, ['any', 'account']))
    <div class="alert alert-{{ ($alert_type) ? $alert_type : 'warning' }} alert-thick{{ $alert_class }}">
        <div class="alert-cta flex-wrap flex-md-nowrap g-2">
            <div class="alert-text has-icon">
                <em class="icon ni ni-alert-fill text-{{ ($alert_type) ? $alert_type : 'warning' }}"></em>
                <p>{{ __('Add an account that you’d like to receive payment or withdraw fund.') }}</p>
            </div>
            <div class="alert-actions my-1 my-md-0">
                <a href="{{ ($link_modal) ? $link_modal : route('account.withdraw-accounts') }}"{!! ($link_modal) ? ' data-toggle="modal"' : '' !!} class="btn btn-sm btn-{{ ($alert_type) ? $alert_type : 'warning' }}">{{ __('Add Account') }}</a>
            </div>
        </div>
    </div>
    @endif

    @if($is_profile && in_array($alert, ['any', 'profile']))
    <div class="alert alert-{{ ($alert_type) ? $alert_type : 'primary' }} alert-thick{{ $alert_class }}">
        <div class="alert-cta flex-wrap flex-md-nowrap g-2">
            <div class="alert-text has-icon">
                <em class="icon ni ni-info-fill text-{{ ($alert_type) ? $alert_type : 'primary' }}"></em>
                <p>{{ __('Update your account information from your profile to complete account setup.') }}</p>
            </div>
            <div class="alert-actions my-1 my-md-0">
                <a href="{{ ($link_modal) ? $link_modal : route('account.profile') }}"{!! ($link_modal) ? ' data-toggle="modal"' : '' !!} class="link link-{{ ($alert_type) ? $alert_type : 'primary' }}">{{ __('Update Profile') }}</a>
            </div>
        </div>
    </div>
    @endif


</div>
@endif

@if(!$is_email_verified && in_array($alert, ['any', 'verify_email']))
<div class="nk-block">
    <div class="alert alert-{{ ($alert_type) ? $alert_type : 'info' }} alert-thick{{ $alert_class }}">
        <div class="alert-cta flex-wrap flex-md-nowrap g-2">
            <div class="alert-text has-icon">
                <em class="icon ni ni-alert-fill text-{{ ($alert_type) ? $alert_type : 'info' }}"></em>
                <p><strong>{{ __('Please verify your email address to deposit funds or to achieve bonuses.') }}</strong></p>
            </div>
            <div class="alert-actions my-1 my-md-0">
                <a href="{{ ($link_modal) ? $link_modal : route('account.profile') }}"{!! ($link_modal) ? ' data-toggle="modal"' : '' !!} class="btn btn-sm btn-{{ ($alert_type) ? $alert_type : 'info' }}">{{ __('Change Email') }}</a>
                <a href="{{ ($link_modal_verify) ? $link_modal_verify : route('account.profile') }}"{!! ($link_modal_verify) ? ' data-toggle="modal"' : '' !!} class="btn btn-sm btn-{{ ($alert_type) ? $alert_type : 'info' }}">{{ __('Resend Verification Link') }}</a>
            </div>
        </div>
    </div>
</div>
@endif
