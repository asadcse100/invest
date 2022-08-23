<div class="nk-footer{{ (gui('admin', 'sidebar')=='lighter') ? ' bg-lighter' : '' }}">
    <div class="container-fluid">
        <div class="nk-footer-wrap">
            <div class="nk-footer-copyright">
                {!! site_info('copyright') !!}
            </div>
            <div class="nk-footer-links">
                <ul class="nav nav-sm">
                    {!! Panel::lang_switcher() !!}
                    @if(has_sysinfo())
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.systeminfo') }}">{{ __("System Info") }}</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
