<div class="nk-content-sidebar" data-content="pageSidebar" data-toggle-screen="lg" data-toggle-overlay="true">
    <div class="nk-content-sidebar-inner" data-simplebar>
        <h6>{{ __('Quick Edit Page') }}</h6>
        <ul class="nk-nav-tree">
            @foreach($pages as $page)
            <li class="link-item{{ data_get($content, 'id') == $page->id ? ' active' : '' }}">
            	<a href="{{ route('admin.manage.pages.edit', $page->id) }}"><em class="icon ni ni-file"></em> <span>{{ $page->name }}</span></a>
            </li>
            @endforeach
            <li class="link-item{{ is_route('admin.manage.pages.create') ? ' active' : '' }}">
            	<a href="{{ route('admin.manage.pages.create') }}"><em class="icon ni ni-plus"></em> <span>{{ __('Add New Page') }}</span></a>
            </li>
        </ul>
    </div>
</div>
