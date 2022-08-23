<div class="nk-sidebar nk-sidebar-fat nk-sidebar-fixed {{ gui_mode(gui('user', 'sidebar')) }} x-{{ gui('user', 'sidebar') }}" data-content="sidebarMenu">
    <div class="nk-sidebar-element nk-sidebar-head">

        {{ site_branding('sidebar', ['panel' => 'user', 'size' => 'md', 'class_link' => 'nk-sidebar-logo']) }}

        <div class="nk-menu-trigger mr-n2">
            <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="sidebarMenu"><em class="icon ni ni-arrow-left"></em></a>
        </div>
    </div>{{-- .nk-sidebar-element --}}

    <div class="nk-sidebar-element">
        <div class="nk-sidebar-body" data-simplebar>
            <div class="nk-sidebar-content">
                <div class="nk-sidebar-widget d-none d-xl-block">
                    <div class="user-account-info between-center">
                        <div class="user-account-main">
                            <h6 class="overline-title-alt">{{ __(":Account Balance", ['account' => w2n(AccType('main'))]) }}</h6>
                            <div class="user-balance">{{ account_balance(AccType('main')) }} <small class="currency">{{ base_currency() }}</small></div>
                            <div class="user-balance-alt">{{ account_balance(AccType('main'), 'alter') }} <span class="currency">{{ secondary_currency() }}</span></div>
                        </div>
                    </div>
                    <ul class="user-account-data gy-1">
                        <li>
                            <div class="user-account-label">
                                <span class="sub-text">{{ __('Profits (7d)') }}</span>
                            </div>
                            @php 
                                $profit=user_profit('weekly');
                            @endphp
                            <div class="user-account-value">
                                <span class="lead-text"> {{ to_amount($profit['amount'], base_currency()) }} <span class="currency">{{ base_currency() }}</span></span>
                                @if($profit['percentage']>0)
                                    <span class="text-success ml-2">{{ abs($profit['percentage'])."%" }} <em class="icon ni ni-arrow-long-up"></em></span>
                                @elseif($profit['percentage']<0)
                                    <span class="text-danger ml-2">{{ abs($profit['percentage'])."%" }} <em class="icon ni ni-arrow-long-down"></em></span>
                                @endif
                            </div>
                        </li>
                       
                    </ul>
                    <div class="user-account-actions">
                        <ul class="g-3">
                            <li><a href="{{ route('deposit') }}" class="btn btn-primary"><span>{{ __('Deposit') }}</span></a></li>
                            <li><a href="{{ route('withdraw') }}" class="btn btn-warning"><span>{{ __('Withdraw') }}</span></a></li>
                        </ul>
                    </div>
                </div>{{-- .nk-sidebar-widget --}}

                <div class="nk-sidebar-widget nk-sidebar-widget-full d-xl-none pt-0">
                    <a class="nk-profile-toggle toggle-expand" data-target="sidebarProfile" href="#">
                        <div class="user-card-wrap">
                            <div class="user-card">
                                <div class="user-avatar">
                                    <span>{!! user_avatar(auth()->user()) !!}</span>
                                </div>
                                <div class="user-info">
                                    <span class="lead-text">RRRR</span>
                                    <span class="sub-text">WWW</span>
                                </div>
                                <div class="user-action">
                                    <em class="icon ni ni-chevron-down"></em>
                                </div>
                            </div>
                        </div>
                    </a>
                    <div class="nk-profile-content toggle-expand-content" data-content="sidebarProfile">
                        <div class="user-account-info between-center">
                            <div class="user-account-main">
                                <h6 class="overline-title-alt">{{ __(":Account Balance", ['account' => w2n(AccType('main'))]) }}</h6>
                                <div class="user-balance">{{ account_balance(AccType('main')) }} <small class="currency">{{ base_currency() }}</small></div>
                                <div class="user-balance-alt">{{ account_balance(AccType('main'), 'alter') }} <span class="currency">{{ secondary_currency() }}</span></div>
                            </div>
                        </div>
                        <ul class="user-account-data">
                            <li>
                                <div class="user-account-label">
                                    <span class="sub-text">{{ __('Profits (7d)') }}</span>
                                </div>
                                <div class="user-account-value">
                                    @php 
                                        $profit = user_profit('weekly');
                                    @endphp
                                    <div class="user-account-value">
                                        <span class="lead-text"> {{ to_amount($profit['amount'], base_currency()) }} <span class="currency">{{ base_currency() }}</span></span>
                                        @if($profit['percentage']>0)
                                            <span class="text-success ml-2">{{ abs($profit['percentage'])."%" }} <em class="icon ni ni-arrow-long-up"></em></span>
                                        @elseif($profit['percentage']<0)
                                            <span class="text-danger ml-2">{{ abs($profit['percentage'])."%" }} <em class="icon ni ni-arrow-long-down"></em></span>
                                        @endif
                                    </div>
                                </div>
                            </li>
                            
                        </ul>
                        <ul class="user-account-links">
                            <li><a href="{{ route('withdraw') }}" class="link"><span>{{ __('Withdraw Funds') }}</span> <em class="icon ni ni-wallet-out"></em></a></li>
                            <li><a href="{{ route('deposit') }}" class="link"><span>{{ __('Deposit Funds') }}</span> <em class="icon ni ni-wallet-in"></em></a></li>
                        </ul>
                        <ul class="link-list">
                            <li><a href="{{ route('account.profile') }}"><em class="icon ni ni-user-alt"></em><span>{{ __('View Profile') }}</span></a></li>
                            <li><a href="{{ route('account.settings') }}"><em class="icon ni ni-setting-alt"></em><span>{{ __('Account Setting') }}</span></a></li>
                            <li><a href="{{ route('account.activity') }}"><em class="icon ni ni-activity-alt"></em><span>{{ __('Login Activity') }}</span></a></li>
                        </ul>
                        <ul class="link-list">
                            <li><a href="{{ route('auth.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><em class="icon ni ni-signout"></em><span>{{ __('Sign out') }}</span></a></li>
                        </ul>
                    </div>
                </div> {{-- .nk-sidebar-widget --}}

                <div class="nk-sidebar-menu">
                    <ul class="nk-menu">
                        <li class="nk-menu-heading">
                            <h6 class="overline-title">{{ __('Menu') }}</h6>
                        </li>
                        <li class="nk-menu-item{{ is_route('dashboard') ? ' active' : '' }}">
                            <a href="{{route('dashboard')}}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-dashboard"></em></span>
                                <span class="nk-menu-text">{{ __('Dashboard') }}</span>
                            </a>
                        </li>
                        <li class="nk-menu-item{{ is_route('transaction.list') ? ' active' : '' }}">
                            <a href="{{ route('transaction.list') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-tranx"></em></span>
                                <span class="nk-menu-text">{{ __('Transaction') }}</span>
                            </a>
                        </li>
                        @if(has_route('user.investment.dashboard'))
                        <li class="nk-menu-item{{ (is_route('user.investment.dashboard') || is_route('user.investment.details') || is_route('user.investment.history') || is_route('user.investment.transactions') || is_route('user.invest.*')) ? ' active' : '' }}">
                            <a href="{{ route('user.investment.dashboard') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-invest"></em></span>
                                <span class="nk-menu-text">{{ __('Investment') }}</span>
                            </a>
                        </li>
                        @endif
                        @if(has_route('user.investment.plans'))
                        <li class="nk-menu-item{{ (is_route('user.investment.plans')) ? ' active' : '' }}">
                            <a href="{{ route('user.investment.plans') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-invest"></em></span>
                                <span class="nk-menu-text">{{ __('Our Plans') }}</span>
                            </a>
                        </li>
                        @endif
                        <li class="nk-menu-item{{ is_route('account.*') ? ' active' : '' }}">
                            <a href="{{ route('account.profile') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-account-setting"></em></span>
                                <span class="nk-menu-text">{{ __('My Profile') }}</span>
                            </a>
                        </li>
                        @if (referral_system())
                        <li class="nk-menu-item{{ is_route('referrals') ? ' active' : '' }}">
                            <a href="{{ route('referrals') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-share"></em></span>
                                <span class="nk-menu-text">{{ __('Referrals') }}</span>
                            </a>
                        </li>
                        @endif
                        {!! Panel::navigation('main', ['heading' => true]) !!}
                    </ul>
                </div>{{-- .nk-sidebar-menu --}}

                <div class="nk-sidebar-footer">
                    <ul class="nk-menu nk-menu-footer">
                        @if(sys_settings('page_contact') && get_page_slug(sys_settings('page_contact')))
                            <li class="nk-menu-item">
                                <a href="{{ route('show.page', get_page_slug(sys_settings('page_contact'))) }}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-question"></em></span>
                                    <span class="nk-menu-text">{{ __(get_page_name(sys_settings('page_contact'))) }}</span>
                                </a>
                            </li>
                        @endif
                        {!! Panel::lang_switcher('sidebar', ['class' => 'ml-auto']) !!}
                    </ul>
                </div>
            </div>
        </div>{{-- .nk-sidebar-body --}}
    </div>{{-- .nk-sidebar-element --}}
</div>
