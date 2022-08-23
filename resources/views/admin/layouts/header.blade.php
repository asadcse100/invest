<div class="nk-header nk-header-fixed{{ (gui('admin', 'sidebar')=='lighter') ? ' bg-lighter' : ' bg-white' }}">
    <div class="container-fluid">
        <div class="nk-header-wrap">
            <div class="nk-menu-trigger d-xl-none ml-n1">
                <a href="javascript:void(0)" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em
                        class="icon ni ni-menu"></em></a>
            </div>

            {{ site_branding('header', ['panel' => 'back', 'class' => 'd-xl-none']) }}

            <div class="nk-header-news d-none d-xl-block">
                <div class="nk-news-list">
                    <a class="nk-news-item" href="javascript:void(0)">
                        <div class="nk-news-icon ml-n1">
                            <em class="icon ni ni-task-c"></em>
                        </div>
                        <div class="nk-news-text">
                            @if (!empty(fun_facts()))
                                <p>{{ __('Fun Fact!') }}<span> {{ __('You have :num :text.', fun_facts()) }}</span>
                                </p>
                            @endif
                        </div>
                    </a>
                </div>
            </div>
            
            <div class="nk-header-tools">
                <ul class="nk-quick-nav">
                    <li><a href="{{ url('/') }}" target="_blank" class="nk-quick-nav-icon btn-tooltip" title="{{ __("Go Home Page") }}"><em class="icon ni ni-external-alt"></em></a></li>
                    <li class="dropdown user-dropdown">
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                            <div class="user-toggle">
                                <div class="user-avatar sm">
                                    <em class="icon ni ni-user-alt"></em>
                                </div>
                                <div class="user-info d-none d-md-block">
                                    <div class="user-status">{{ __('Admin') }}</div>
                                    <div class="user-name dropdown-indicator">{{ auth()->user()->display_name ?? '' }}
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right dropdown-menu-s1">
                            <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                <div class="user-card">
                                    {!! user_avatar(auth()->user()) !!}
                                    <div class="user-info">
                                        <span class="lead-text">{{ auth()->user()->display_name }}</span>
                                        <span class="sub-text">{{ auth()->user()->email }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-inner">
                                <ul class="link-list">
                                    <li><a href="{{ route('admin.profile.view') }}"><em class="icon ni ni-user-alt"></em><span>{{ __('View Profile') }}</span></a></li>
                                    <li><a href="{{ route('admin.profile.view', ['settings']) }}"><em class="icon ni ni-setting-alt"></em><span>{{ __('Account Setting') }}</span></a></li>
                                    <li><a href="{{ route('admin.profile.view', ['activity']) }}"><em class="icon ni ni-activity-alt"></em><span>{{ __('Login Activity') }}</span></a></li>
                                    @if (gss('ui_theme_mode_admin') == 'both')
                                    <li><a href="javascript:void(0)" class="dark-switch{{ (user_theme() == 'dark') ? ' active' : '' }}"><em class="icon ni ni-moon"></em><span>{{ __("Dark Mode") }}</span></a></li>
                                    @endif
                                </ul>
                            </div>
                            <div class="dropdown-inner">
                                <ul class="link-list">
                                    <li><a href="{{ route('auth.logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <em class="icon ni ni-signout"></em>
                                            <span>{{ __('Sign out') }}</span>
                                        </a>
                                    </li>
                                </ul>
                                <form id="logout-form" action="{{ route('auth.logout') }}" method="POST"
                                    style="display: none;">
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
