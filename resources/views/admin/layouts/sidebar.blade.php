<div class="nk-sidebar nk-sidebar-fixed {{ gui_mode(gui('admin', 'sidebar')) }} x-{{ gui('admin', 'sidebar') }}" data-content="sidebarMenu">
    <div class="nk-sidebar-element nk-sidebar-head">

        {{ site_branding('sidebar', ['panel' => 'back', 'class_link' => 'nk-sidebar-logo', 'size' => 24]) }}

        <div class="nk-menu-trigger mr-n2">
            <a href="javascript:void(0)" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="sidebarMenu"><em class="icon ni ni-arrow-left"></em></a>
        </div>
    </div>
    <div class="nk-sidebar-element">
        <div class="nk-sidebar-content">
            <div class="nk-sidebar-menu" data-simplebar>
                <ul class="nk-menu">
                    <li class="nk-menu-item{{ is_route('admin.dashboard') ? ' active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-dashboard"></em></span>
                            <span class="nk-menu-text">{{ __('Dashboard') }}</span>
                        </a>
                    </li>
                    <li class="nk-menu-item{{ is_route('admin.transactions.deposit.all') ? ' active' : '' }}">
                        <a href="{{ route('admin.transactions.deposit.all', ['status' => ($pendingDepositCount > 0) ? 'pending' : 'any']) }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-wallet-in"></em></span>
                            <span class="nk-menu-text">{{ __('Deposit') }}</span>
                            @if($pendingDepositCount > 0)
                                <span class="nk-menu-badge">{{ $pendingDepositCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nk-menu-item{{ is_route('admin.transactions.withdraw.all') ? ' active' : '' }}">
                        <a href="{{ route('admin.transactions.withdraw.all', ['status' => ($pendingWithdrawCount > 0) ? 'pending' : 'any']) }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-wallet-out"></em></span>
                            <span class="nk-menu-text">{{ __('Withdraw') }}</span>
                            @if($pendingWithdrawCount > 0)
                                <span class="nk-menu-badge">{{ $pendingWithdrawCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nk-menu-item{{ is_route('admin.transactions.referral.all') ? ' active' : '' }}">
                        <a href="{{ route('admin.transactions.referral.all', ['status' => ($pendingReferralCount > 0) ? 'pending' : 'any']) }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-percent"></em></span>
                            <span class="nk-menu-text">{{ __('Referral') }}</span>
                            @if($pendingReferralCount > 0)
                                <span class="nk-menu-badge">{{ $pendingReferralCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nk-menu-item has-sub{{ is_route('admin.transactions.list*') ? ' active' : '' }}">
                        <a href="javascript:void(0)" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-tranx"></em></span>
                            <span class="nk-menu-text">{{ __('Transaction') }}</span>
                        </a>
                        <ul class="nk-menu-sub">
                            @if($pendingTransactionCount > 0)
                            <li class="nk-menu-item{{ is_route('admin.transactions.list') && (request('list_type') == 'pending') ? ' active' : '' }}">
                                <a href="{{ route('admin.transactions.list', ['list_type' => 'pending']) }}" class="nk-menu-link"><span class="nk-menu-text">{{ __('Pending') }} <span class="small">({{ $pendingTransactionCount }})</span></span></a>
                            </li>
                            @endif
                            @if($onholdTransactionCount > 0)
                            <li class="nk-menu-item{{ is_route('admin.transactions.list') && (request('list_type') == 'on-hold') ? ' active' : '' }}">
                                <a href="{{ route('admin.transactions.list', ['list_type' => 'on-hold']) }}" class="nk-menu-link"><span class="nk-menu-text">{{ __('On Hold') }} <span class="small">({{ $onholdTransactionCount }})</span></span></a>
                            </li>
                            @endif
                            @if($confirmedTransactionCount > 0)
                            <li class="nk-menu-item{{ is_route('admin.transactions.list') && (request('list_type') == 'confirmed') ? ' active' : '' }}">
                                <a href="{{ route('admin.transactions.list', ['list_type' => 'confirmed']) }}" class="nk-menu-link"><span class="nk-menu-text">{{ __('Confirmed') }} <span class="small">({{ $confirmedTransactionCount }})</span></span></a>
                            </li>
                            @endif
                            <li class="nk-menu-item{{ is_route('admin.transactions.list') && (request('list_type') == 'deposit') ? ' active' : '' }}">
                                <a href="{{ route('admin.transactions.list', ['list_type' => 'deposit']) }}" class="nk-menu-link"><span class="nk-menu-text">{{ __('Deposit') }}</span></a>
                            </li>
                            <li class="nk-menu-item{{ is_route('admin.transactions.list') && (request('list_type') == 'withdraw') ? ' active' : '' }}">
                                <a href="{{ route('admin.transactions.list', ['list_type' => 'withdraw']) }}" class="nk-menu-link"><span class="nk-menu-text">{{ __('Withdrawal') }}</span></a>
                            </li>
                            <li class="nk-menu-item{{ is_route('admin.transactions.list') && is_null(request('list_type')) ? ' active' : '' }}">
                                <a href="{{ route('admin.transactions.list') }}" class="nk-menu-link"><span class="nk-menu-text">{{ __('All Transaction') }}</span></a>
                            </li>
                        </ul>
                    </li>
                    <li class="nk-menu-heading">
                        <h6 class="overline-title text-primary-alt">{{ __('Investment') }}</h6>
                    </li>
                    @if(has_route('admin.investment.dashboard'))
                        <li class="nk-menu-item{{ is_route('admin.investment.dashboard') ? ' active' : '' }}">
                            <a href="{{route('admin.investment.dashboard')}}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-growth"></em></span>
                                <span class="nk-menu-text">{{ __('Invest Dashboard') }}</span>
                            </a>
                        @endif
                    </li>
                    <li class="nk-menu-item has-sub{{ (is_route('admin.investment.list*') || is_route('admin.investment.schemes') || is_route('admin.investment.details*')) ? ' active' : '' }}">
                        <a href="javascript:void(0)" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-invest"></em></span>
                            <span class="nk-menu-text">{{ __('Invested Plans') }}</span>
                        </a>
                        <ul class="nk-menu-sub">
                            @if(has_route('admin.investment.list'))
                            <li class="nk-menu-item{{ (is_route('admin.investment.list') && request()->route('status') == 'active') ? ' active' : '' }}">
                                <a href="{{ route('admin.investment.list', ['status' => 'active']) }}" class="nk-menu-link"><span class="nk-menu-text">{{ __('Actived Invest') }}</span></a>
                            </li>
                            <li class="nk-menu-item{{ (is_route('admin.investment.list') && request()->route('status') == 'pending') ? ' active' : '' }}">
                                <a href="{{ route('admin.investment.list', ['status' => 'pending']) }}" class="nk-menu-link"><span class="nk-menu-text">{{ __('Pending Invest') }}</span></a>
                            </li>
                            <li class="nk-menu-item{{ (is_route('admin.investment.list') && request()->route('status') == 'completed') ? ' active' : '' }}">
                                <a href="{{ route('admin.investment.list', ['status' => 'completed']) }}" class="nk-menu-link"><span class="nk-menu-text">{{ __('Completed Invest') }}</span></a>
                            </li>
                            <li class="nk-menu-item{{ (is_route('admin.investment.list') && empty(request()->route('status'))) ? ' active' : '' }}">
                                <a href="{{ route('admin.investment.list') }}" class="nk-menu-link"><span class="nk-menu-text">{{ __('All Invested Plans') }}</span></a>
                            </li>
                            @endif
                            @if(has_route('admin.investment.schemes'))
                                <li class="nk-menu-item{{ (is_route('admin.investment.schemes')) ? ' active' : '' }}">
                                    <a href="{{ route('admin.investment.schemes') }}" class="nk-menu-link"><span class="nk-menu-text">{{ __('Manage Schemes') }}</span></a>
                                </li>
                            @endif
                        </ul>
                    </li>
                    <li class="nk-menu-item has-sub{{ (is_route('admin.investment.transactions*') || is_route('admin.investment.profits*')) ? ' active' : '' }}">
                        <a href="javascript:void(0)" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-calc"></em></span>
                            <span class="nk-menu-text">{{ __('Invest Statement') }}</span>
                        </a>
                        <ul class="nk-menu-sub">
                            @if(has_route('admin.investment.transactions.list'))
                            <li class="nk-menu-item{{ (is_route('admin.investment.transactions.list') && empty(request()->route('type'))) ? ' active' : '' }}">
                                <a href="{{ route('admin.investment.transactions.list') }}" class="nk-menu-link"><span class="nk-menu-text">{{ __('All Transactions') }}</span></a>
                            </li>
                            @endif
                            @if(has_route('admin.investment.transactions.list'))
                            <li class="nk-menu-item{{ (is_route('admin.investment.transactions.list') && request()->route('type') == 'profit') ? ' active' : '' }}">
                                <a href="{{ route('admin.investment.transactions.list', 'profit') }}" class="nk-menu-link"><span class="nk-menu-text">{{ __('Profit Settlement') }}</span></a>
                            </li>
                            @endif
                            @if(has_route('admin.investment.transactions.list'))
                            <li class="nk-menu-item{{ (is_route('admin.investment.transactions.list') && request()->route('type') == 'transfer') ? ' active' : '' }}">
                                <a href="{{ route('admin.investment.transactions.list', 'transfer') }}" class="nk-menu-link"><span class="nk-menu-text">{{ __('Transferred History') }}</span></a>
                            </li>
                            @endif
                            @if(has_route('admin.investment.profits.list'))
                            <li class="nk-menu-item{{ is_route('admin.investment.profits.list') ? ' active' : '' }}">
                                <a href="{{ route('admin.investment.profits.list') }}" class="nk-menu-link"><span class="nk-menu-text">{{ __('Profit / Interest Logs') }}</span></a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    <li class="nk-menu-heading">
                        <h6 class="overline-title text-primary-alt">{{ __('User Management') }}</h6>
                    </li>
                    <li class="nk-menu-item has-sub{{ is_route('admin.users*') ? ' active' : '' }}">
                        <a href="javascript:void(0)" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-users"></em></span>
                            <span class="nk-menu-text">{{ __('Manage Users') }}</span>
                        </a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item{{ is_route('admin.users') && (request('state') == 'active') ? ' active' : '' }}">
                                <a href="{{ route('admin.users', ['state' => 'active']) }}" class="nk-menu-link"><span class="nk-menu-text">{{ __('Active') }}</span></a>
                            </li>
                            
                            @if(isset($userCount['inactive']) && ($userCount['inactive'] > 0))
                            <li class="nk-menu-item{{ is_route('admin.users') && (request('state') == 'inactive') ? ' active' : '' }}">
                                <a href="{{ route('admin.users', ['state' => 'inactive']) }}" class="nk-menu-link"><span class="nk-menu-text">{{ __('Inactive') }} <span class="small">({{ $userCount['inactive'] ?? 0 }})</span></span></a>
                            </li>
                            @endif

                            @if(isset($userCount['locked']) && ($userCount['locked'] > 0))
                            <li class="nk-menu-item{{ is_route('admin.users') && (request('state') == 'locked') ? ' active' : '' }}">
                                <a href="{{ route('admin.users', ['state' => 'locked']) }}" class="nk-menu-link"><span class="nk-menu-text">{{ __('Locked') }} <span class="small">({{ $userCount['locked'] ?? 0 }})</span></span></a>
                            </li>
                            @endif

                            @if(isset($userCount['suspend']) && ($userCount['suspend'] > 0))
                            <li class="nk-menu-item{{ is_route('admin.users') && (request('state') == 'suspend') ? ' active' : '' }}">
                                <a href="{{ route('admin.users', ['state' => 'suspend']) }}" class="nk-menu-link"><span class="nk-menu-text">{{ __('Suspended') }} <span class="small">({{ $userCount['suspend'] ?? 0 }})</span></span></a>
                            </li>
                            @endif

                            <li class="nk-menu-item{{ (is_route('admin.users') && (request('state') == '')) ? ' active' : '' }}">
                                <a href="{{ route('admin.users') }}" class="nk-menu-link"><span class="nk-menu-text">{{ __('All Users') }}</span></a>
                            </li>

                            @if(isset($adminUserCount) && ($adminUserCount > 0))
                            <li class="nk-menu-item{{ (is_route('admin.users.administrator')) ? ' active' : '' }}">
                                <a href="{{ route('admin.users.administrator') }}" class="nk-menu-link"><span class="nk-menu-text">{{ __('Administrator') }} <span class="small">({{ $adminUserCount ?? 0 }})</span></span></a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @if(module_exist('BasicKYC', 'mod') && has_route('admin.kyc.list'))
                    <li class="nk-menu-item has-sub{{ is_route('admin.kyc*') ? ' active' : '' }}">
                        <a href="{{ route('admin.kyc.list') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-check-circle-cut"></em></span>
                            <span class="nk-menu-text">{{ __('Verification Center') }}</span>
                        </a>
                    </li>
                    @endif
                    <li class="nk-menu-heading">
                        <h6 class="overline-title text-primary-alt">{{ __('Management') }}</h6>
                    </li>
                    <li class="nk-menu-item has-sub{{ (is_route('admin.manage.*')) ? ' active' : '' }}">
                        <a href="javascript:void(0)" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-files"></em></span>
                            <span class="nk-menu-text">{{ __('Manage Content') }}</span>
                        </a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item{{ (is_route('admin.manage.pages*')) ? ' active' : '' }}">
                                <a href="{{ route('admin.manage.pages') }}" class="nk-menu-link"><span class="nk-menu-text">{{ __('Manage Pages') }}</span></a>
                            </li>
                            <li class="nk-menu-item{{ (is_route('admin.manage.email*')) ? ' active' : '' }}">
                                <a href="{{ route('admin.manage.email.template') }}" class="nk-menu-link"><span class="nk-menu-text">{{ __('Email Templates') }}</span></a>
                            </li>
                        </ul>
                    </li>
                    <li class="nk-menu-item has-sub{{ (is_route('admin.settings.*') || is_route('admin.systeminfo')) ? ' active' : '' }}">
                        <a href="javascript:void(0)" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-setting"></em></span>
                            <span class="nk-menu-text">{{ __('Application Settings') }}</span>
                        </a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item{{ is_route('admin.settings.global.*') ? ' active' : '' }}">
                                <a href="{{ route('admin.settings.global.general') }}" class="nk-menu-link"><span class="nk-menu-text">{{ __('Global Settings') }}</span></a>
                            </li>
                            <li class="nk-menu-item{{ (is_route('admin.settings.website*')) ? ' active' : '' }}{{ (is_route('admin.settings.website.*')) ? ' active' : '' }}">
                                <a href="{{ route('admin.settings.website') }}" class="nk-menu-link"><span class="nk-menu-text">{{ __('Website Settings') }}</span></a>
                            </li>
                            <li class="nk-menu-item{{ (is_route('admin.settings.component*')) ? ' active' : '' }}{{ (is_route('admin.settings.component.*')) ? ' active' : '' }}">
                                <a href="{{ route('admin.settings.component.system') }}" class="nk-menu-link"><span class="nk-menu-text">{{ __('Manage Components') }}</span></a>
                            </li>
                            <li class="nk-menu-item{{ is_route('admin.settings.gateway.*') ? ' active' : '' }}">
                                <a href="{{ route('admin.settings.gateway.option') }}" class="nk-menu-link"><span class="nk-menu-text">{{ __('Payment Options') }}</span></a>
                            </li>
                            @if(has_route('admin.settings.investment.apps'))
                            <li class="nk-menu-item{{ is_route('admin.settings.investment.apps') ? ' active' : '' }}">
                                <a href="{{ route('admin.settings.investment.apps') }}" class="nk-menu-link"><span class="nk-menu-text">{{ __('Investment Apps') }}</span></a>
                            </li>
                            @endif
                            <li class="nk-menu-item{{ is_route('admin.settings.email') ? ' active' : '' }}">
                                <a href="{{ route('admin.settings.email') }}" class="nk-menu-link"><span class="nk-menu-text">{{ __('Email Cofiguration') }}</span></a>
                            </li>
                            @if(has_sysinfo())
                            <li class="nk-menu-item{{ is_route('admin.systeminfo') ? ' active' : '' }}">
                                <a href="{{ route('admin.systeminfo') }}" class="nk-menu-link"><span class="nk-menu-text">{{ __('System Status') }}</span></a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    <li class="nk-menu-item{{ (is_route('admin.system.langs')) ? ' active' : '' }}">
                        <a href="{{ route('admin.system.langs') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-text-a"></em></span>
                            <span class="nk-menu-text">{{ __('Manage Language') }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
