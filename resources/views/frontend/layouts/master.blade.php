@php
use App\Enums\UserRoles;
$style = (lang_dir() == 'rtl') ? 'apps.rtl' : 'apps';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="js" id="{{ ghp() }}">
<head>
    <meta charset="utf-8">
    <meta name="author" content="{{ site_info('author') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="@yield('desc')">
    <meta name="keywords" content="@yield('keyword')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="site-token" content="{{ site_token() }}">
    <title>@yield('title') | {{ site_info('name') }}</title>
    <link rel="shortcut icon" href="{{ asset('public/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/'.$style.'.css?ver=130') }}">
@if(sys_settings('ui_theme_skin', 'default')!='default')
    <link rel="stylesheet" href="{{ asset('public/assets/css/skins/theme-'.sys_settings('ui_theme_skin').'.css?ver=130') }}">
@endif
    @include('misc.share')    
    @include('misc.analytics')
@if(sys_settings('header_code'))
    {{ html_string(sys_settings('header_code')) }}
@endif
</head>
<body class="nk-body npc-cryptlite bg-white{{ ($admins) ? ' admin-logged' : '' }}{{ dark_theme('active') }}"{!! lang_dir() == 'rtl' ? ' dir="rtl"' : '' !!} data-theme="{{ dark_theme('mode') }}">
<div class="nk-app-root">
    <div class="nk-main">
        <header class="header {{ (gss('ui_page_skin', 'dark')=='dark') ? 'bg-gray-900' : 'bg-white border-bottom border-bottom-light ' }}">
            <div class="header-main {{ (gss('ui_page_skin', 'dark')=='dark') ? 'header-dark bg-gray-900 on-dark' : 'header-light bg-white on-light' }} is-sticky is-transparent">
                <div class="container header-container wide-lg">
                    <div class="header-wrap">

                        {{ site_branding('header', ['panel' => 'public', 'size' => 'md']) }}

                        <div class="header-toggle">
                            <button class="menu-toggler" data-target="main-hmenu">
                                <em class="menu-on icon ni ni-menu"></em>
                                <em class="menu-off icon ni ni-cross"></em>
                            </button>
                        </div>

                        <nav class="header-menu"  data-content="main-hmenu">
                            <ul class="menu-list ml-lg-auto">
                                @if(gss('front_page_enable', 'yes')=='yes')
                                <li class="menu-item"><a href="{{ url('/') }}" class="menu-link nav-link">{{ __("Home") }}</a></li>
                                @elseif (!empty(gss('main_website')))
                                <li class="menu-item">
                                    <a href="{{ gss('main_website') }}" target="_blank" class="menu-link nav-link">
                                        <span>{{ __("Main Website") }}</span>
                                        <em class="icon ni ni-external pl-1"></em>
                                    </a>
                                </li>
                                @endif
                                @if(gss('invest_page_enable', 'yes')=='yes')
                                <li class="menu-item"><a href="{{ route('investments') }}" class="menu-link nav-link">{{ __("Investment") }}</a></li>
                                @endif
                                {!! Panel::navigation('mainnav') !!}

                                @if (!auth()->check() && gss('signup_allow', 'enable') == 'enable')
                                <li class="menu-item"><a href="{{ route('auth.register.form') }}" class="menu-link nav-link">{{ __("Register") }}</a></li>
                                @endif
                            </ul>

                            @if (auth()->check())
                            <ul class="nk-quick-nav ml-1">
                                <li class="dropdown user-dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <div class="user-toggle">
                                            <div class="user-avatar sm">
                                                <em class="icon ni ni-user-alt"></em>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-md dropdown-menu-right dropdown-menu-s1">
                                        <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                            <div class="user-card">
                                                <div class="user-avatar">
                                                    <span>{!! user_avatar(auth()->user()) !!}</span>
                                                </div>
                                                <div class="user-info">
                                                    <span class="lead-text">{{ auth()->user()->display_name }}</span>
                                                    <span class="sub-text">{{ auth()->user()->email }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dropdown-inner">
                                            <ul class="link-list">
                                                <li><a href="{{ (auth()->user()->role==UserRoles::USER) ? route('dashboard') : route('admin.dashboard') }}"><em class="icon ni ni-dashboard"></em><span>{{ __('Go to Dashboard') }}</span></a></li>
                                                <li><a href="{{ (auth()->user()->role==UserRoles::USER) ? route('account.profile') : route('admin.profile.view')  }}"><em class="icon ni ni-user-alt"></em><span>{{ __('View Profile') }}</span></a></li>
                                            </ul>
                                        </div>
                                        <div class="dropdown-inner">
                                            <ul class="link-list">
                                                <li>
                                                    <a href="{{ route('auth.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                    <em class="icon ni ni-signout"></em><span>{{ __('Sign out') }}</span></a>
                                                </li>
                                            </ul>
                                            <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            @else
                            <ul class="menu-btns">
                                <li>
                                    <a href="{{ route('auth.login.form') }}" class="btn btn-round btn-primary"><em class="icon ni ni-user-alt"></em> <span>{{ __("Login") }}</span></a>
                                </li>
                            </ul>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </header>

        <div class="content-pg bg-lighter">
            @if(is_route('welcome'))
                @yield('content')
            @else
            <section class="section section-lg section-page">
                <div class="container wide-lg">
                    @yield('content')
                </div>
            </section>
            @endif
        </div>

        @include('frontend.layouts.footer')

    </div>
    
</div>

@include('misc.gdpr')

@stack('modal')
@if(sys_settings('custom_stylesheet')=='on')
    <link rel="stylesheet" href="{{ asset('public/css/custom.css') }}">
@endif
@if(dark_theme('exist') && dark_theme('css'))
    <style type="text/css">{{ dark_theme('css') }}</style> 
@endif
<script type="text/javascript">
    const msgwng = "{{ __("Sorry, something went wrong!") }}", msgunp = "{{ __("Unable to process your request.") }}";
@if (user_consent() === null)
    const consentURI = "{{ route('gdpr.cookie') }}";
@endif
</script>
<script src="{{ asset('public/assets/js/bundle.js?ver=130') }}"></script>
<script src="{{ asset('public/assets/js/app.js?ver=130') }}"></script>
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
