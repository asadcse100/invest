@if (session()->get('system_error'))
<div class="alert alert-light bg-white alert-thick">
    <div class="alert-cta flex-wrap flex-md-nowrap g-2">
        <div class="alert-text has-icon">
            <em class="icon ni ni-alert-fill"></em>
            <p>{!! 'Cau' . 'tio'. 'n: Th'. 'e ap'. 'pli' . 'cat' .'ion sh'. 'oul'. 'd be '.'<stro'. 'ng class="text-da'. 'nger">'. 're' . 'gis' .'ter'. 'ed to un'. 'lock'.'</stro'. 'ng>' .' a' . 'll t' . 'he fe'. 'atu' .'res. Pl'. 'ea' .'se re'. 'gis'. 'ter wi'. 'th yo'. 'ur val'. 'id pu'. 'rch' . ''. 'ase in'. 'for'. 'mat'. 'ion.' !!}</p>
        </div>
        <div class="alert-actions my-1 my-md-0">
            <a href="{{ route('admin.quick.register') }}" class="btn btn-primary btn-sm">{{ 'Un'. 'loc'. 'k'. ' th' .'e ap'. 'pli'. 'ca'. 'tio' . 'n' }}</a>
        </div>
    </div>
</div>
@endif

@if (!empty($errors) && !is_array($errors) && $errors->any())
<div class="alert-notices mb-4">
    <ul>
        @foreach ($errors->toArray() as $type => $error)
        <li class="alert alert-{{ (in_array($type, ['warning', 'info', 'success', 'light'])) ? $type : 'danger' }} alert-icon alert-dismissible">
            <em class="icon ni ni-alert-fill"></em> {!! $error[0] ?? '' !!} <button class="close" data-dismiss="alert"></button>
        </li>
        @endforeach
    </ul>
</div>
@endif

@php

$paymentOption = (!active_payment_methods()) ? true : false;
$mailSetting = (empty(gss('mail_recipient', '')) && gss('mail_from_email') == 'noreply@yourdomain.com') ? true : false;

@endphp

@if($paymentOption)
<div class="alert alert-danger bg-white py-2 px-3">
    <div class="alert-cta flex-wrap flex-md-nowrap g-2">
        <div class="alert-text has-icon">
            <em class="icon ni ni-wallet-in"></em>
            <p><strong>{{ __("Important") }}:</strong> {{ __("Setup at least one payment method to active deposit system.") }}</p>
        </div>
        <div class="alert-actions my-1 my-md-0">
            <a href="{{ route('admin.settings.gateway.payment.list') }}" class="link link-danger">{{ __("Payment Method") }}</a>
        </div>
    </div>
</div>
@endif

@if($mailSetting)
<div class="alert alert-light bg-white py-2 px-3">
    <div class="alert-cta flex-wrap flex-md-nowrap g-2">
        <div class="alert-text has-icon text-base">
            <em class="icon ni ni-mail"></em>
            <p><strong>{{ __("Caution") }}:</strong> {{ __("Application will send emails once you setup email configuration.") }}</p>
        </div>
        <div class="alert-actions my-1 my-md-0">
            <a href="{{ route('admin.settings.email') }}" class="link link-primary">{{ __("Mail Setting") }}</a>
        </div>
    </div>
</div>
@endif

@if($updateManager->isUpdateAvailable() || $updateManager->hsaPendingMigration())
<div class="alert alert-danger py-2 px-3">
    <div class="alert-cta flex-wrap flex-md-nowrap g-2">
        <div class="alert-text has-icon">
            <em class="icon ni ni-flag-fill"></em>
            <p><strong>{{ __("Update Required") }}:</strong> {{ __("Application is required to update database, so please review and install the update.") }}</p>
        </div>
        <div class="alert-actions my-1 my-md-0">
            <a href="{{ route('admin.update.systems') }}" class="link link-danger">{{ __("Install Update") }}</a>
        </div>
    </div>
</div>
@endif
