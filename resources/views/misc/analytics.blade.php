@php

$gtackid = sys_settings('google_track_id');
$gdpropt = (user_consent() === 'no') ? false : true;

@endphp
@if(!empty($gtackid) && $gdpropt)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gtackid }}"></script>
    <script>
        window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', "{{ $gtackid }}");
    </script>
@endif
