<li class="nav-item{{ is_route('account.profile') ? ' active' : '' }}">
    <a class="nav-link" href="{{ route('account.profile') }}">{{ __('Profile') }}</a>
</li>
<li class="nav-item{{ is_route('account.withdraw-accounts') ? ' active' : '' }}">
    <a class="nav-link" href="{{ route('account.withdraw-accounts') }}">{!! __('Accounts') !!}</a>
</li>
<li class="nav-item{{ is_route('account.settings') ? ' active' : '' }}">
    <a class="nav-link" href="{{ route('account.settings') }}">{!! __('Security') !!}</a>
</li>
<li class="nav-item{{ is_route('account.activity') ? ' active' : '' }}">
    <a class="nav-link" href="{{ route('account.activity') }}">{{ __('Activity') }}</a>
</li>
