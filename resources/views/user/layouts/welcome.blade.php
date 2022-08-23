<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="js">
<head>
    <meta charset="utf-8">
    <meta name="author" content="{{ site_info('author') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="site-token" content="{{ site_token() }}">
    <title>@yield('title', 'Welcome') | {{ site_info('name') }}</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    @php $style = (lang_dir() == 'rtl') ? 'apps.rtl' : 'apps'; @endphp
    <link rel="stylesheet" href="{{ asset('assets/css/'.$style.'.css?ver=130') }}">
@if(sys_settings('ui_theme_skin', 'default')!='default')
    <link rel="stylesheet" href="{{ asset('assets/css/skins/theme-'.sys_settings('ui_theme_skin').'.css?ver=130') }}">
@endif
@if(sys_settings('google_track_id'))
<script async src="https://www.googletagmanager.com/gtag/js?id={{ sys_settings('google_track_id') }}"></script>
<script>
    window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', "{{ sys_settings('google_track_id') }}");
</script>
@endif
@if(sys_settings('header_code'))
    {{ html_string(sys_settings('header_code')) }}
@endif
</head>
<body class="nk-body npc-cryptlite has-sidebar has-sidebar-fat"{!! lang_dir() == 'rtl' ? ' dir="rtl"' : '' !!}>
<div class="nk-app-root">

    <div class="nk-wrap">
        @php 
            $header = ['wide' => 'lg', 'style' => 'welcome']; 
            $footer = ['wide' => 'lg', 'style' => 'plain'];
        @endphp

        @include('user.layouts.header', compact('header'))

        <div class="nk-content">
            <div class="container-xl wide-lg">
                @yield('content')
            </div>
        </div>

        @include('user.layouts.footer', compact('footer'))

    </div>
</div>

@stack('modal')
@if(sys_settings('custom_stylesheet')=='on')
    <link rel="stylesheet" href="{{ asset('/css/custom.css') }}">
@endif
<script type="text/javascript">
    const updateSetting = "{{ route('update.setting') }}", getTnxDetails = "{{ route('transaction.details') }}", msgwng = "{{ __("Sorry, something went wrong!") }}", msgunp = "{{ __("Unable to process your request.") }}";
</script>
<script src="{{ asset('/assets/js/bundle.js?ver=130') }}"></script>
<script src="{{ asset('/assets/js/app.js?ver=130') }}"></script>
@stack('scripts')
@if(sys_settings('tawk_api_key'))
<script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date(); (function(){ var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0]; s1.async=true; s1.src='https://embed.tawk.to/{{ str_replace(['https://tawk.to/chat/', 'http://tawk.to/chat/'], '', sys_settings('tawk_api_key')) }}'; s1.charset='UTF-8'; s1.setAttribute('crossorigin','*'); s0.parentNode.insertBefore(s1,s0); })();
</script>
@endif
@if(sys_settings('footer_code'))
    {{ html_string(sys_settings('footer_code')) }}
@endif
</body>
</html>