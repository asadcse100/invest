<div class="nk-content-sidebar" data-content="pageSidebar" data-toggle-screen="lg" data-toggle-overlay="true">
    <div class="nk-content-sidebar-inner" data-simplebar>
        <h6>{{ __('Quick Edit Template') }}</h6>
        <ul class="nk-nav-tree">

            @foreach($templateList as $group => $groupItems)
            <li><span>{{ strtoupper(str_replace(['_', '-'], ' ', $group)) }}</span>
                @foreach($groupItems as $template)
                <ul>
                    <li class="link-item{{ data_get($templateDetails, 'slug') == data_get($template, 'slug') ? ' active' : '' }}">
                        <a href="{{ route('admin.manage.email.template.edit', data_get($template, 'slug')) }}">{{ data_get($template, 'name') }} - {{ ucfirst(data_get($template, 'recipient')) }}</a>
                    </li>
                </ul>
                @endforeach
            </li>
            @endforeach
        </ul>
    </div>
</div>
