@if (sys_settings('cookie_consent_text') && user_consent() === null)
<div class="pp-alert pp-{{ sys_settings('cookie_banner_position', 'bottom') }} bg-{{ (sys_settings('cookie_banner_background', 'dark') == 'dark') ? 'dark' : 'lighter' }} cookie-banner">
    <div class="container wide-lg">
        <div class="row align-center justify-between gy-2">
            <div class="{{ (sys_settings('cookie_banner_position', 'bottom') == 'bottom') ? 'col-md-7 col-xl-5 col-sm-11' : 'col-12' }} text-{{ (sys_settings('cookie_banner_background', 'dark') == 'dark') ? 'light' : 'dark' }}">
                {!! __(replace_shortcut(sys_settings('cookie_consent_text'))) !!}
            </div>
            <div class="{{ (sys_settings('cookie_banner_position', 'bottom') == 'bottom') ? 'col-md-4 col-sm-12' : 'col-12' }}">
                <ul class="d-flex g-2 justify-content-md-end">
                    <li>
                        <button class="btn btn-{{ (sys_settings('cookie_banner_background', 'dark') == 'dark') ? 'primary btn-dim' : 'primary' }} btn-xs" data-consent="yes">{{ __(sys_settings('cookie_accept_btn_txt', 'I Agree')) }}</button>
                    </li>
                    @if (sys_settings('cookie_deny_btn') == 'yes')
                    <li>
                        <button class="btn btn-{{ (sys_settings('cookie_banner_background', 'dark') == 'dark') ? 'danger btn-dim' : 'danger' }} btn-xs" data-consent="no">{{ __(sys_settings('cookie_deny_btn_txt', 'Deny')) }}</button>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
@endif
