<div class="nk-content-sidebar{{ (gui('admin', 'sidebar')=='lighter') ? ' bg-lighter' : '' }}" data-content="pageSidebar" data-toggle-screen="lg" data-toggle-overlay="true">
    <div class="nk-content-sidebar-inner" data-simplebar>
        <h6>{{ __('Application Settings') }}</h6>
        <ul class="nk-nav-tree">
            <li class="link-item"><a href="{{ route('admin.settings.global.general') }}"> <span>{{ __('Global Settings') }}</span></a>
                <ul>
                    <li class="link-item{{ is_route('admin.settings.global.general') ? ' active' : '' }}"><a href="{{ route('admin.settings.global.general') }}">{{ __('General Settings') }}</a></li>
                    <li class="link-item{{ is_route('admin.settings.global.currency') ? ' active' : '' }}"><a href="{{ route('admin.settings.global.currency') }}">{{ __('Manage Currencies') }}</a></li>
                    <li class="link-item{{ is_route('admin.settings.global.rewards') ? ' active' : '' }}"><a href="{{ route('admin.settings.global.rewards') }}">{{ __('Rewards Program') }}</a></li>
                    <li class="link-item{{ is_route('admin.settings.global.referral') ? ' active' : '' }}"><a href="{{ route('admin.settings.global.referral') }}">{{ __('Referral Settings') }}</a></li>
                    <li class="link-item{{ is_route('admin.settings.global.api') ? ' active' : '' }}"><a href="{{ route('admin.settings.global.api') }}">{{ __('Third-Party API') }}</a></li>
                </ul>
            </li>
            <li class="link-item"><a href="{{ route('admin.settings.component.system') }}"> <span>{{ __('Manage Components') }}</span></a>
                <ul>
                    <li class="link-item{{ is_route('admin.settings.component.system') ? ' active' : '' }}"><a href="{{ route('admin.settings.component.system') }}">{{ __('System Component') }}</a></li>
                    @if(module_exist('BasicKYC', 'mod') && has_route('admin.settings.component.kyc'))
                    <li class="link-item{{ is_route('admin.settings.component.kyc') ? ' active' : '' }}">
                        <a href="{{ route('admin.settings.component.kyc') }}">
                            <span>{{ __('Basic KYC') }}</span> <span class="badge badge-pill badge-xs badge-gray ml-1">{{ 'Module' }}</span>
                        </a>
                    </li>
                    @endif
                    @if(module_exist('FundTransfer', 'mod') && has_route('admin.settings.component.fund-transfer'))
                    <li class="link-item{{ is_route('admin.settings.component.fund-transfer') ? ' active' : '' }}">
                        <a href="{{ route('admin.settings.component.fund-transfer') }}">
                            <span>{{ __('Fund Transfer') }}</span> <span class="badge badge-pill badge-xs badge-gray ml-1">{{ 'Module' }}</span>
                        </a>
                    </li>
                    @endif
                    @if(module_exist('ExtCurrency', 'addon') && has_route('admin.settings.custom.currency'))
                        <li class="link-item{{ is_route('admin.settings.custom.currency') ? ' active' : '' }}">
                            <a href="{{ route('admin.settings.custom.currency') }}">
                                <span>{{ __('Custom Coin') }}</span> <span class="badge badge-pill badge-xs badge-danger ml-1">{{ 'Addon' }}</span>
                            </a>
                        </li>
                    @endif
                    @if(module_exist('NioCron', 'addon') && has_route('admin.settings.component.cron.nio-cron'))
                        <li class="link-item{{ is_route('admin.settings.component.cron.nio-cron') ? ' active' : '' }}">
                            <a href="{{ route('admin.settings.component.cron.nio-cron') }}">
                                <span>{{ __('Cron Service') }}</span> <span class="badge badge-pill badge-xs badge-danger ml-1">{{ 'Addon' }}</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
            <li class="link-item"><a href="{{ route('admin.settings.gateway.option') }}">{{ __('Payment Options') }}</a>
                <ul>
                    <li class="link-item{{ is_route('admin.settings.gateway.option') ? ' active' : '' }}"><a href="{{ route('admin.settings.gateway.option') }}">{{ __('Deposit & Withdraw') }}</a></li>
                    <li class="link-item{{ is_route('admin.settings.gateway.payment.*') ? ' active' : '' }}"><a href="{{ route('admin.settings.gateway.payment.list') }}">{{ __('Payment Method') }}</a></li>
                    <li class="link-item{{ is_route('admin.settings.gateway.withdraw.*') ? ' active' : '' }}"><a href="{{ route('admin.settings.gateway.withdraw.list') }}">{{ __('Withdraw Method') }}</a></li>
                </ul>
            </li>
            @if(has_route('admin.settings.investment.apps'))
            <li class="link-item{{ is_route('admin.settings.investment.apps') ? ' active' : '' }}"><a href="{{ route('admin.settings.investment.apps') }}"> <span>{{ __('Investment Apps') }}</span></a>
            </li>
            @endif
            <li class="link-item"><a href="{{ route('admin.settings.website') }}"> <span>{{ __('Website Settings') }}</span></a>
                <ul>
                    <li class="link-item{{ is_route('admin.settings.website') ? ' active' : '' }}"><a href="{{ route('admin.settings.website') }}">{{ __('Site Information') }}</a></li>
                    <li class="link-item{{ is_route('admin.settings.website.userpanel') ? ' active' : '' }}"><a href="{{ route('admin.settings.website.userpanel') }}">{{ __('User Dashboard') }}</a></li>
                    <li class="link-item{{ is_route('admin.settings.website.appearance') ? ' active' : '' }}"><a href="{{ route('admin.settings.website.appearance') }}">{{ __('Brands & Theming') }}</a></li>
                    <li class="link-item{{ is_route('admin.settings.website.misc') ? ' active' : '' }}"><a href="{{ route('admin.settings.website.misc') }}">{{ __('Miscellaneous') }}</a></li>
                </ul>
            </li>
            <li class="link-item{{ is_route('admin.settings.email') ? ' active' : '' }}"><a href="{{ route('admin.settings.email') }}">{{ __('Email Configuration') }}</a></li>
            <li class="link-item{{ is_route('admin.systeminfo') ? ' active' : '' }}"><a href="{{ route('admin.systeminfo') }}">{{ __('System Status') }}</a></li>

            @if (has_route('admin.quick-setup') && sys_settings('system_super_admin', 0) == auth()->user()->id)
            <li class="link-item{{ is_route('admin.quick-setup') ? ' active' : '' }}"><a href="{{ route('admin.quick-setup') }}">{{ __('Quick Setup') }} <em class="icon ni ni-arrow-long-right ml-2"></em></a></li>
            @endif

            @if (has_route('admin.system.cache') && sys_settings('system_super_admin', 0) == auth()->user()->id)
            <li class="link-item">
                <a href="{{ route('admin.system.cache') }}">{{ __('Clear Cache') }} <em class="icon ni ni-reload ml-2"></em></a>
            </li>
            @endif
        </ul>
    </div>
</div>
