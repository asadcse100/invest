<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="js">

<head>
    <meta charset="utf-8">
    <meta name="author" content="{{ site_info('author') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="site-token" content="{{ site_token() }}">
    <title>@yield('title') | {{ site_info('name') }}</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    @php $style = (lang_dir() == 'rtl') ? 'apps-admin.rtl' : 'apps-admin'; @endphp
    <link rel="stylesheet" href="{{ asset('assets/css/'.$style.'.css?ver=130') }}">
@if(sys_settings('ui_theme_skin_admin', 'default') != 'default')
    <link rel="stylesheet" href="{{ asset('assets/css/skins/theme-'.sys_settings('ui_theme_skin_admin').'.css?ver=130') }}">
@endif
</head>

<body class="nk-body npc-cryptlite npc-admin"{!! lang_dir() == 'rtl' ? ' dir="rtl"' : '' !!}>
<div class="nk-app-root">
    <div class="nk-main">
        <div class="nk-wrap">

            <div class="nk-header nk-header-fluid nk-header-fixed is-light">
                <div class="container-fluid">
                    <div class="nk-header-wrap">
                        <div class="nk-header-brand">
                            <a href="{{ route('admin.dashboard') }}" class="logo-link">
                                <img class="logo-light logo-img" src="{{ asset('/images/logo.png') }}" srcset="{{ asset('/images/logo2x.png 2x') }}" alt="{{ site_info('name') }}">
                                <img class="logo-dark logo-img" src="{{ asset('/images/logo-dark.png') }}" srcset="{{ asset('/images/logo-dark2x.png 2x') }}" alt="{{ site_info('name') }}">
                            </a>
                        </div>

                        <div class="nk-header-tools">
                            <ul class="nk-quick-nav">
                                <li class="dropdown user-dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <div class="user-toggle">
                                            <div class="user-avatar sm">
                                                <em class="icon ni ni-user-alt"></em>
                                            </div>
                                            <div class="user-info d-none d-md-block">
                                                <div class="user-status">{{ __("Administrator") }}</div>
                                                <div class="user-name dropdown-indicator">{{ auth()->user()->display_name ?? '' }}</div>
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
                                                <li><a href="{{ route('admin.profile.view') }}"><em class="icon ni ni-user-alt"></em><span>{{ __("View Profile") }}</span></a></li>
                                                <li><a href="{{ route('admin.profile.view', ['settings']) }}"><em class="icon ni ni-setting-alt"></em><span>{{ __("Account Setting") }}</span></a></li>
                                                <li><a href="{{ route('admin.profile.view', ['activity']) }}"><em class="icon ni ni-activity-alt"></em><span>{{ __("Login Activity") }}</span></a></li>
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
                        </div>
                    </div>
                </div>
            </div>

            <div class="nk-content">
                <div class="container-xl wide-lg">
                    @yield('content')
                </div>
            </div>

            <div class="nk-footer">
                <div class="container wide-lg">
                    <div class="row g-3">
                        <div class="col-lg-12">
                            <div class="nk-block-content text-center text-lg-center">
                                <p class="text-soft">{!! 'Copyright &copy; Investorm 2021. All Rights Reserved.' !!}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
    const msgwng = "{{ __("Sorry, something went wrong!") }}", msgunp = "{{ __("Unable to process your request.") }}";
</script>
<script src="{{ asset('assets/js/bundle.js?ver=130') }}"></script>
<script src="{{ asset('assets/js/app.js?ver=130') }}"></script>
<script src="{{ asset('assets/js/app.admin.js?ver=130') }}"></script>
@stack('scripts')

</body>
</html>