@extends('admin.layouts.master')
@section('title', __('Manage Language'))

@section('content')
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">{{ __('Languages') }}</h3>
                    <p>{{ __('Manage application localization at your ease.') }}</p>
                </div>
                <div class="nk-block-head-content">
                    <ul class="nk-block-tools g-3">
                        <li>
                            <a href="{{ route('admin.settings.global.general') }}#language-settings"
                                class="btn btn-primary d-none d-md-inline-flex">
                                <em class="icon ni ni-setting"></em><span>{{ __('Language Settings') }}</span>
                            </a>
                            <a href="{{ route('admin.settings.global.general') }}#language-settings"
                                class="btn btn-icon btn-primary d-inline-flex d-md-none">
                                <em class="icon ni ni-setting"></em>
                            </a>
                        </li>
                        <li class="nk-block-tools-opt">
                            <div class="btn-group">
                                <button class="qma-lang btn btn-primary" data-method="new"><em class="icon ni ni-text-a"></em> <span class="d-none d-md-inline-block">{{ __("Add Language") }}</span></button>
                                <div class="btn-group dropdown">
                                    <button class="dropdown-toggle btn btn-icon btn-primary" data-toggle="dropdown"><em class="icon ni ni-chevron-down"></em></button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <ul class="link-list-opt no-bdr">
                                            <li><a href="javascript:void(0)" class="qma-lang" data-method="new">
                                                <em class="icon ni ni-plus"></em><span>{{ __('Add Language') }}</span></a>
                                            </li>
                                            <li><a href="javascript:void(0)" class="qma-lang" data-method="sync">
                                                <em class="icon ni ni-reload"></em><span>{{ __('Sync to Database') }}</span></a>
                                            </li>
                                            <li><a href="javascript:void(0)" class="qma-lang" data-method="regenerate">
                                                <em class="icon ni ni-files"></em><span>{{ __('Regenerate Files') }}</span></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="nk-block">
            @if (filled($langs))
                <div class="card card-bordered card-stretch">
                    <div class="nk-tb-list nk-tb-langlist is-compact">
                        <div class="nk-tb-item nk-tb-head">
                            <div class="nk-tb-col"><span>{{ __('Name') }}</span></div>
                            <div class="nk-tb-col tb-col-sm"><span>{{ __('Label / Short') }}</span></div>
                            <div class="nk-tb-col"><span>{{ __('Status') }}</span></div>
                            <div class="nk-tb-col text-right">&nbsp;</div>
                            <div class="nk-tb-col tb-col-md"><span>{{ __('Relevent File') }}</span></div>
                            <div class="nk-tb-col nk-tb-col-tools">&nbsp;</div>
                        </div>
                        @foreach ($langs as $lang)
                        <div class="nk-tb-item">
                            <div class="nk-tb-col">
                                <span class="tb-lead">{{ $lang->name }} [{{ strtoupper($lang->code) }}]</span>
                            </div>
                            <div class="nk-tb-col tb-col-sm">
                                <span class="tb-lead-sub">{{ $lang->label }} / {{ ($lang->short) ? ucfirst($lang->short) : ucfirst($lang->code) }}</span>
                            </div>
                            <div class="nk-tb-col">
                                <span class="tb-status{{ $lang->status == 1 ? ' text-success' : ' text-danger' }}">{{ ($lang->status) ? __('Active') : __('Inactive') }}</span>
                            </div>
                            <div class="nk-tb-col text-right">
                                @if(!file_exists(resource_path('/lang/'.$lang->code.'.json'))) 
                                    <em class="ml-1 ni ni-alert-fill fs-13px text-danger" data-toggle="tooltip" title="{{ __("Missing or not exist the file.") }}"></em>
                                @else
                                &nbsp;
                                @endif
                            </div>
                            <div class="nk-tb-col tb-col-md">
                                <code>{{ 'resources/lang/'.data_get($lang, 'file', $lang->code.'.json') }}</code>
                            </div>
                            <div class="nk-tb-col nk-tb-col-tools">
                                <ul class="nk-tb-actions gx-1">
                                    <li class="nk-tb-action-hidden">
                                        <a href="javascript:void(0)" data-method="edit" data-lang="{{ the_hash($lang->id) }}" class="btn btn-trigger btn-icon qma-lang" data-toggle="tooltip" data-placement="top" title="{{ __('Edit') }}">
                                            <em class="icon ni ni-edit-fill"></em>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown">
                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-sm">
                                                <ul class="link-list-opt no-bdr">
                                                    <li>
                                                        <a href="javascript:void(0)" data-method="edit" data-lang="{{ the_hash($lang->id) }}" class="qma-lang">
                                                            <em class="icon ni ni-edit"></em><span>{{ __('Edit') }}</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0)" data-method="remove" data-lang="{{ the_hash($lang->id) }}" class="qma-lang">
                                                            <em class="icon ni ni-trash"></em> <span>{{ __("Delete") }}</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if (filled($langs) && $langs->hasPages())
                    <div class="card-inner pt-3 pb-3">
                        {{ $langs->appends(request()->all())->links('misc.pagination') }}
                    </div>
                    @endif
                </div>
            @else
                <div class="alert alert-primary">
                    <div class="alert-cta flex-wrap flex-md-nowrap">
                        <div class="alert-text">
                            <p>{{ __('No language found in application.') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            <div class="notes mt-4">
                <ul>
                    <li class="alert-note is-plain">
                        <em class="icon ni ni-info"></em>
                        <p>{{ __("After add any language into application, please duplicate the base translation (en.json) file to new name and start translation on language.") }}</p>
                    </li>
                    <li class="alert-note is-plain text-danger">
                        <em class="icon ni ni-info"></em>
                        <p>{{ __("If you change the json file, please sync the language files and add into database, so you can regenerate the file any time or while updated the application.") }}</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    <div class="modal fade" role="dialog" id="ajax-modal"></div>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {
            const routes = {new: "{{ route('admin.system.langs.action', 'new') }}", edit : "{{ route('admin.system.langs.action', 'edit') }}", remove: "{{ route('admin.system.langs.delete') }}", sync: "{{ route('admin.system.langs.process.action', 'sync') }}", regenerate: "{{ route('admin.system.langs.process.action', 'regenerate') }}"},
                msgs = {
                    remove: { title: "{{ __('Do you want to delete?') }}", btn: {cancel: "{{ __('Cancel') }}", confirm: "{{ __('Delete') }}"}, context: "{!! __("You cannot revert back this action, so please confirm that you want to delete the :type.", ['type' => __("Language")]) !!}", custom: "danger", type: "warning" },
                    sync: { title: "{{ __('Do you want to sync with database?')  }}", btn: {cancel: "{{ __('Cancel') }}", confirm: "{{ __('Sync Now') }}"}, context: "{!! __("Are you sure that you want to sync translation string from json file to database for all the languages? Please confirm as cannot revert back this action.") !!}", custom: "danger", type: "warning" },
                    regenerate: { title: "{{ __('Do you want to regenerate?')  }}", btn: {cancel: "{{ __('Cancel') }}", confirm: "{{ __('Regenerate') }}"}, context: "{!! __("Are you sure that you want to regenerate all language files in your application from last synced data. You cannot undo this action, so please confirm that you want to regenerate and replace existing files.") !!}", custom: "danger", type: "warning" },
                };

            let qmlBtn = '.qma-lang', modal = '#ajax-modal';

            $(qmlBtn).on('click', function(e) {
                e.preventDefault();
                let $this = $(this),
                    method = $this.data('method'), lang = $this.data('lang'),
                    url = routes[method], qmsg = msgs[method],
                    data = (lang) ? { uid: lang } : {};

                if (url) {
                    if ((method == 'remove' || method == 'sync' || method == 'regenerate') && qmsg) {
                        NioApp.Ask(qmsg.title, qmsg.context, qmsg.btn, '', 'info').then(function(confirm){
                            if(confirm) {
                                NioApp.Form.toAjax(url, data);
                            }
                        });
                    } else {
                        NioApp.Form.toModal(url, data, { modal: $(modal) });
                    }
                }
            });

            $(document).on('click', qmlBtn + '-save', function(e) {
                let $self = $(this), $form = $self.parents('form'), url = $form.attr('action'), data = $form.serialize(), opt = { btn: $self };
                if(url && data) { NioApp.Form.toPost(url, data, opt); }
            });
        })

    </script>
@endpush
