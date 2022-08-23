@extends('admin.layouts.master')
@section('title', __('Manage Pages'))

@section('content')
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">{{ __('Manage Pages') }}</h3>
                    <p>{{ __('List of pages that you can manage / edit.') }}</p>
                </div>
                <div class="nk-block-head-content">
                    <ul class="nk-block-tools g-3">
                        <li>
                            <a href="{{ route('admin.manage.pages.create') }}" class="btn btn-primary d-none d-sm-inline-flex">
                                <em class="icon ni ni-plus"></em><span>{{ __('Add new') }}</span>
                            </a>
                            <a href="{{ route('admin.manage.pages.create') }}" class="btn btn-icon btn-primary d-inline-flex d-sm-none">
                                <em class="icon ni ni-plus"></em>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="nk-block">
            <div class="card card-bordered card-stretch">
                <div class="card-inner-group">
                    <div class="card-inner pt-3 pb-3">
                        <div class="card-title-group">
                            <div class="card-title">
                                <h6 class="title">
                                    {{ __('All Pages') }}
                                    @if(request()->filled('query'))
                                    <small class="pl-1">
                                    {{ (__('Results for') .' "'. (request()->get('query') ?? ''). '"') }}
                                    </small>
                                    @endif
                                </h6>
                            </div>
                            <div class="card-tools mr-n1">
                                <ul class="btn-toolbar gx-2">
                                    <li>
                                        <a href="#" class="search-toggle toggle-search btn btn-icon" data-target="search"><em class="icon ni ni-search"></em></a>
                                    </li>
                                    <li class="btn-toolbar-sep"></li>
                                    <li>
                                        <div class="dropdown">
                                            <a href="#" class="btn btn-trigger btn-icon dropdown-toggle"
                                               data-toggle="dropdown">
                                                <em class="icon ni ni-setting"></em></a>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-xs dropdown-menu-right">
                                                <ul class="link-check">
                                                    <li><span>{{ __('Show') }}</span></li>
                                                    @foreach(config('investorm.pgtn_pr_pg') as $item)
                                                    <li class="update-meta{{ (user_meta('page_perpage', '10') == $item) ? ' active' : '' }}">
                                                        <a href="#" data-value="{{ $item }}" data-meta="perpage" data-type="page">{{ $item }}</a>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                                <ul class="link-check">
                                                    <li><span>{{ __('Order By') }}</span></li>
                                                    @foreach(config('investorm.pgtn_order') as $item)
                                                    <li class="update-meta{{ (user_meta('page_order', 'asc') == $item) ? ' active' : '' }}">
                                                        <a href="#" data-value="{{ $item }}" data-meta="order" data-type="page">{{ __(strtoupper($item)) }}</a>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                                <ul class="link-check">
                                                    <li><span>{{ __('Sort By') }}</span></li>
                                                    @foreach(config('investorm.pgtn_sort_pg') as $item)
                                                    <li class="update-meta{{ (user_meta('page_sortpg', 'date') == $item) ? ' active' : '' }}">
                                                        <a href="#" data-value="{{ $item }}" data-meta="sortpg" data-type="page">{{ __(ucfirst($item)) }}</a>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="card-search search-wrap" data-search="search">
                                <div class="search-content">
                                    <form action="{{ route('admin.manage.pages') }}" method="GET">
                                    <a href="#" class="search-back btn btn-icon toggle-search" data-target="search"><em class="icon ni ni-arrow-left"></em></a>
                                    <input name="query" type="text" value="{{ request()->get('query') }}" class="form-control border-transparent form-focus-none"
                                           placeholder="{{ __('Search by page title') }}">
                                    <button class="search-submit btn btn-icon"><em class="icon ni ni-search"></em></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(filled($pages))
                        <span id="delete-msg" data-msg='@json(['title' => 'Are you sure to delete page?', 'msg' => 'This change is not reversible !', 'buttonText' => 'Delete' ])'></span>
                        <div class="card-inner p-0">
                            <div class="nk-tb-list nk-tb-pglist is-compact">
                                <div class="nk-tb-item nk-tb-head">
                                    <div class="nk-tb-col"><span>{{ __('Title / Name') }}</span></div>
                                    <div class="nk-tb-col tb-col-mb"><span>{{ __('Menu Name') }}</span></div>
                                    <div class="nk-tb-col tb-col-md"><span>{{ __('SEO Data') }}</span></div>
                                    <div class="nk-tb-col tb-col-md"><span>{{ __('Visibility') }}</span></div>
                                    <div class="nk-tb-col tb-col-sm"><span>{{ __('Status') }}</span></div>
                                    <div class="nk-tb-col nk-tb-col-tools">&nbsp;</div>
                                </div>

                                @foreach($pages as $page)

                                    <div class="nk-tb-item" id="{{ data_get($page, 'slug') }}">
                                        <div class="nk-tb-col">
                                            <a href="{{ route('admin.manage.pages.edit', data_get($page, 'id')) }}"><span
                                                    class="tb-lead">{{ data_get($page, 'name') }}</span></a>
                                        </div>
                                        <div class="nk-tb-col tb-col-mb">
                                            <span class="tb-lead-sub">{{ data_get($page, 'menu_name') }}</span>
                                        </div>
                                        <div class="nk-tb-col tb-col-md">
                                            <span
                                                class="tb-lead-sub">{{ empty(array_filter(data_get($page, 'seo', []))) ? __('No') : __('Yes') }}</span>
                                        </div>
                                        <div class="nk-tb-col tb-col-md">
                                            <span class="tb-status{{ data_get($page, 'public') == $iState->YES ? ' text-info': ' text-warning'}}">
                                                {{ data_get($page, 'public') == $iState->YES ? __('Public') : __('Restrict')}}
                                            </span>
                                        </div>
                                        <div class="nk-tb-col tb-col-sm">
                                            <span class="tb-status{{ data_get($page, 'status') == $iStatus->ACTIVE ? ' text-success': ' text-danger'}}">
                                                {{ data_get($page, 'status') == $iStatus->ACTIVE ? __('Active') : __('Inactive')}}
                                            </span>
                                        </div>
                                        <div class="nk-tb-col nk-tb-col-tools">
                                            <ul class="nk-tb-actions gx-1">
                                                <li class="nk-tb-action-hidden">
                                                    <a href="{{ route('admin.manage.pages.edit', data_get($page, 'id')) }}"
                                                       class="btn btn-trigger btn-icon" data-toggle="tooltip" title="{{ __('Edit') }}">
                                                        <em class="icon ni ni-edit-fill"></em>
                                                    </a>
                                                </li>
                                                <li>
                                                    <div class="drodown">
                                                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                                            <ul class="link-list-opt no-bdr">
                                                                <li>
                                                                    <a href="{{ route('admin.manage.pages.edit', data_get($page, 'id')) }}">
                                                                        <em class="icon ni ni-edit"></em><span>{{ __('Edit Page') }}</span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ route('show.page', [data_get($page, 'slug')]) }}" target="_blank"><em class="icon ni ni-eye"></em><span>{{ __('View Page') }}</span></a>
                                                                </li>
                                                                @if(data_get($page, 'trash') == $iState->YES)
                                                                <li class="divider"></li>
                                                                <li>
                                                                    <a href="javascript:void(0)" class="admin-action" data-action="delete" data-confirm="yes" data-reload="yes" data-uid="{{ data_get($page, 'id') }}">
                                                                        <em class="icon ni ni-trash"></em><span>{{ __('Remove') }}</span>
                                                                    </a>
                                                                </li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @if(filled($pages) && $pages->hasPages())
                        <div class="card-inner pt-3 pb-3">
                            {{ $pages->appends(request()->all())->links('misc.pagination') }}
                        </div>
                        @endif
                    @else
                    <div class="card-inner">
                        @if(request()->filled('query'))
                        <div class="alert alert-light">
                            <div class="alert-text">
                                <em class="icon ni ni-alert fs-22px"></em>
                                <h6 class="pt-1">{{ __('Nothing Found!') }}</h6>
                                <p>{{ __("It seems we can't find what you're looking for. Maybe try another search.") }} </p>
                            </div>
                            <div class="nk-search-box mt-3 w-max-350px">
                                <form action="{{ route('admin.manage.pages') }}" method="GET">
                                    <div class="form-group">
                                        <div class="form-control-wrap">
                                            <input name="query" value="{{ request()->get('query') }}" type="text" class="form-control form-control-lg" placeholder="Search...">
                                            <button class="btn btn-icon form-icon form-icon-right">
                                                <em class="icon ni ni-search"></em>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @else
                        <div class="alert alert-light">
                            <em class="icon ni ni-alert-circle"></em>
                            {{ __('We did not find any page here.') }}
                            <a href="{{ route('admin.manage.pages.create') }}" class="alert-link">{{ __('Add New Page') }}</a>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script type="text/javascript">
    const updateSetting = "{{ route('admin.profile.update') }}", routes = { delete: "{{ route('admin.manage.pages.delete') }}" }, 
          msgs = { delete: { title: "{{ __('Do you want to delete?') }}", btn: {cancel: "{{ __('Cancel') }}", confirm: "{{ __('Delete') }}"}, context: "{!! __("You cannot revert back this action, so please confirm that you want to delete the page.") !!}", custom: "danger", type: "warning" } };
</script>
@endpush