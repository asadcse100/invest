@php

$header     = isset($header) ? $header : false;
$hd_wide    = (isset($header['wide'])) ? $header['wide'] : '';
$hd_style   = (isset($header['style'])) ? $header['style'] : 'regular';
$hd_class   = (isset($header['class'])) ? ' '.$header['class'] : '';

@endphp

<div class="nk-header{{ $hd_class }} nk-header-fluid nk-header-fixed {{ (gui('user', 'sidebar')=='lighter') ? ' is-lighter' : ' is-light' }}">
    <div class="{{ (($hd_wide) ? 'container wide-'.$hd_wide : 'container-fluid') }}">
        <div class="nk-header-wrap">
            @if($hd_style!='welcome')
            <div class="nk-menu-trigger d-xl-none ml-n1">
                <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
            </div>
            @endif

            {{ site_branding('header', ['panel' => 'user', 'class' => (($hd_style != 'welcome') ? 'd-xl-none' : '') ]) }}

            @if(Panel::news())
            <div class="nk-header-news d-none d-xl-block">
                {!! Panel::news() !!}
            </div>
            @elseif(Panel::rates_ticker())
            <div class="nk-header-marque d-none d-md-block pr-md-3 pr-lg-4 pl-md-4 pl-xl-0 flex-grow-0 overflow-hidden">
                {!! Panel::rates_ticker() !!}
            </div>
            @endif
            <div class="nk-header-tools">
                <ul class="nk-quick-nav">
                    <li class="dropdown user-dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <div class="user-toggle">
                                <div class="user-avatar sm">
                                    <em class="icon ni ni-user-alt"></em>
                                </div>
                                <div class="user-info d-none d-md-block">
                                    <div class="user-status user-status-verified">{{ __('Verified') }}</div>
                                
                                    <div class="user-name dropdown-indicator">EEEE</div>
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
                                        <span class="lead-text">EEEE</span>
                                        <span class="sub-text">WWW</span>
                                    </div>
                                </div>
                            </div>
                            @if($hd_style!='welcome')
                            <div class="dropdown-inner user-account-info">
                                <h6 class="overline-title-alt">{{ __('Account Balance') }}</h6>
                                <div class="user-balance">{{ account_balance(AccType('main')) }} <small class="currency">{{ base_currency() }}</small></div>
                                <div class="user-balance-alt">{{ account_balance(AccType('main'), 'alter') }} <span class="currency">{{ secondary_currency() }}</span></div>
                                <ul class="user-account-links">
                                    <li>
                                        <a href="{{ route('deposit') }}" class="link"><span>{{ __('Deposit Funds') }}</span> <em class="icon ni ni-wallet-in"></em></a>
                                    </li>
                                    <li>
                                        <a href="{{ route('withdraw') }}" class="link"><span>{{ __('Withdraw Funds') }}</span> <em class="icon ni ni-wallet-out"></em></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="dropdown-inner">
                                <ul class="link-list">
                                    <li><a href="{{ route('account.profile') }}"><em class="icon ni ni-user-alt"></em><span>{{ __('View Profile') }}</span></a></li>
                                    @if(kyc_enabled())
                                        <li><a href="{{ route('user.kyc.verify') }}"><em class="icon ni ni-check-circle-cut"></em><span>{{ __('KYC Verification') }}</span></a></li>
                                    @endif
                                    <li><a href="{{ route('account.settings') }}"><em class="icon ni ni-setting-alt"></em><span>{{ __('Security Setting') }}</span></a></li>
                                    <li><a href="{{ route('account.activity') }}"><em class="icon ni ni-activity-alt"></em><span>{{ __('Login Activity') }}</span></a></li>
                                    @if (gss('ui_theme_mode') == 'both')
                                    <li><a href="javascript:void(0)" class="dark-switch{{ (user_theme() == 'dark') ? ' active' : '' }}"><em class="icon ni ni-moon"></em><span>{{ __("Dark Mode") }}</span></a></li>
                                    @endif
                                </ul>
                            </div>
                            @endif
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
