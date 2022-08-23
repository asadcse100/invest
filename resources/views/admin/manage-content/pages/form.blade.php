@php
    $content = $pageDetails ?? collect([]);
    $activeAdvance  = ( filled(data_get($content, 'menu_link')) || (data_get($content, 'public', 1) == 0) || (data_get($content, 'params.is_html') == 'on') );
    $activeSEO      = ( !empty(array_filter(data_get($content, 'seo', []))) );
    $slug = $slug ?? null;
    $pg_pid = data_get($content, 'pid') ?? request()->route('id');
@endphp

@extends('admin.layouts.master')
@section('title', __('Quick Edit Page'))

@section('has-content-sidebar', 'has-content-sidebar')

@section('content-sidebar')
    @include('admin.manage-content.pages.content-sidebar', compact('pages', 'content'))
@endsection

@section('content')
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">
                        <a href="{{ route('admin.manage.pages') }}">{{ __('Page') }}</a> / 
                        <span class="text-primary small">{{ data_get($content, 'name', __('New Page')) }}</span>
                    </h3>
                </div>
                <div class="nk-block-head-content">
                    <ul class="nk-block-tools gx-2">
                        <li class="dropdown">
                            <a href="javascript:void(0)" class="btn btn-outline-light bg-white{{ (request()->route()->named('admin.manage.pages.edit')) ? ' dropdown-toggle dropdown-indicator has-indicator' : ''}} lang-switch-btn toggle-tigger" data-toggle="dropdown">
                                {{ __($lName) }}
                            </a>
                            @if (request()->route()->named('admin.manage.pages.edit'))
                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                <ul class="link-list-opt">
                                    @foreach ($langs as $lang)
                                    <li class="list-opt-item{{ (request()->get('lang', 'en') == data_get($lang, 'code')) ? ' active' : '' }}">
                                        <a href="{{ route('admin.manage.pages.edit', ['id' => data_get($content, 'pid') == 0 ? request()->route('id') : data_get($content, 'pid'), 'lang' => data_get($lang, 'code')]) }}" class="">{{ data_get($lang, 'name') }}</a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </li>

                        <li>
                            <a href="{{ route('admin.manage.pages') }}" class="btn btn-outline-light bg-white d-none d-sm-inline-flex">
                                <em class="icon ni ni-arrow-left"></em><span>{{ __('Back') }}</span>
                            </a>
                            <a href="{{ route('admin.manage.pages') }}" class="btn btn-icon btn-trigger d-inline-flex d-sm-none">
                                <em class="icon ni ni-arrow-left"></em>
                            </a>
                        </li>
                        <li class="d-lg-none">
                            <a href="#" class="btn btn-icon btn-trigger toggle" data-target="pageSidebar">
                                <em class="icon ni ni-menu-right"></em>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="nk-block">
            <div class="card card-bordered card-stretch">
                <div class="card-inner">
                    <form action="{{ route('admin.manage.pages.save') }}" method="POST" class="gy-2 form-settings">
                        <input type="hidden" name="id" value="{{ data_get($content, 'id') }}">
                        <input type="hidden" name="pid" value="{{ $pg_pid }}">
                        <input type="hidden" name="lang" value="{{ request('lang') ?? data_get($content, 'lang') }}">
                        <div class="row gy-3">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Page Name / Title') }}</label>
                                    <div class="form-control-wrap">
                                        <input name="name" type="text" class="form-control" value="{{ data_get($content, 'name') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Page Slug') }}</label>
                                    <div class="form-control-wrap">
                                        <div class="form-icon form-icon-right">
                                            <span class="spinner-border spinner-border-sm validate-slug-loader hide" role="status"></span>
                                            <em class="icon ni validate-slug-error hide" data-toggle="tooltip" title="{{ __('Invalid') }}"></em>
                                        </div>
                                        <input name="slug" type="text" class="form-control validate-slug" data-uid="{{ data_get($content, 'id') }}" value="{{ data_get($content, 'slug') ?? $slug }}" @if (request('lang') && request('lang') != 'en') readonly @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Menu Name') }}</label>
                                    <div class="form-control-wrap">
                                        <input name="menu_name" type="text" class="form-control"
                                               value="{{ data_get($content, 'menu_name') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Main Heading / Title') }}</label>
                                    <div class="form-control-wrap">
                                        <input name="title" type="text" class="form-control" value="{{ data_get($content, 'title') }}">
                                    </div>
                                    <div
                                        class="form-note">{{ __('If you leave blank then it use page title as heading.') }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Sub Heading') }}</label>
                                    <div class="form-control-wrap">
                                        <input name="subtitle" type="text" class="form-control" value="{{ data_get($content, 'subtitle') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="meta mt-n1 mb-n1">
                                    <ul class="btn-group gx-4">
                                        <li><a href="#" class="link toggle-expand{{ ($activeSEO==true) ? ' active' : '' }}" data-target="seo-data">{{ __('SEO Data') }}</a></li>
                                        <li><a href="#" class="link toggle-expand{{ ($activeAdvance==true) ? ' active' : '' }}" data-target="advanced">{{ __('Advanced') }}</a></li>
                                    </ul>
                                </div>
                                <div class="toggle-expand-content{{ ($activeSEO==true) ? ' expanded' : '' }}" data-content="seo-data">
                                    <div class="overflow-hidden mt-3">
                                        <div class="row gy-3">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="form-label">{{ __('SEO Page Title') }}</label>
                                                    <div class="form-control-wrap">
                                                        <input name="seo[title]" type="text" class="form-control" value="{{ data_get($content, 'seo.title') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{ __('SEO Page Description') }}</label>
                                                    <div class="form-control-wrap">
                                                        <input name="seo[description]" type="text" class="form-control" value="{{ data_get($content, 'seo.description') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{ __('SEO Keyword') }}</label>
                                                    <div class="form-control-wrap">
                                                        <input name="seo[keyword]" type="text" class="form-control" value="{{ data_get($content, 'seo.description') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="toggle-expand-content{{ ($activeAdvance==true) ? ' expanded' : '' }}" data-content="advanced">
                                    <div class="overflow-hidden mt-3">
                                        <div class="row gy-3">
                                            @if ($pg_pid == 0)
                                            <div class="col-12 col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{ __('Custom Page Link') }}</label>
                                                    <div class="form-control-wrap">
                                                        <input name="menu_link" type="text" class="form-control" value="{{ data_get($content, 'menu_link') }}">
                                                    </div>
                                                    <div class="form-note">{{ __('Set a custom link (with http://) to redirect external link.') }}</div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-4">
                                                <div class="form-group">
                                                    <label class="form-label"
                                                           for="page-public">{{ __('Page Restriction') }}</label>
                                                    <div class="form-control-wrap w-max-175px">
                                                        <select name="public" class="form-select" id="page-public">
                                                            <option value="{{ $iState->YES }}"{{ (data_get($content, 'public', 1) == $iState->YES) ? ' selected' : ''}}>{{ __('Public Access') }}</option>
                                                            <option value="{{ $iState->NO }}"{{ (data_get($content, 'public', 1) == $iState->NO) ? ' selected' : ''}}>{{ __('Login Required') }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="col-12">
                                                <div class="custom-control-group">
                                                    <div class="custom-control custom-switch">
                                                        <input class="switch-option-value" type="hidden" name="params[is_html]" value="{{ data_get($content, 'params.is_html', 'off') }}">
                                                        <input id="page-custom-html" type="checkbox" class="custom-control-input switch-option switch-editor-plain"
                                                               data-switch="on"{{ (data_get($content, 'params.is_html', 'off') == 'on') ? ' checked=""' : ''}}>
                                                        <label class="custom-control-label" for="page-custom-html">{{ __('Use as full custom html content') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description" class="form-label">{{ __('Page Content') }}</label>
                                    <div class="form-control-wrap">
                                        <textarea name="content" class="form-control tinymce-editor" id="page-content">{{ data_get($content, 'content') }}</textarea>
                                    </div>
                                    <div class="form-note fs-12px font-italic">
                                        <p class="text-soft mb-1">{{ __('Supported shortcut:'). ' [[site_name]], [[site_email]]' }} </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="custom-control-group">
                                    <div class="custom-control custom-switch">
                                        <input name="status" class="switch-option-value" type="hidden" value="{{ data_get($content, 'status', $iStatus->ACTIVE) }}">
                                        <input id="page-status" type="checkbox" class="custom-control-input switch-option"
                                               data-switch="{{ $iStatus->ACTIVE }}"{{ (data_get($content, 'status', $iStatus->ACTIVE) == $iStatus->ACTIVE) ? ' checked=""' : ''}}>
                                        <label class="custom-control-label" for="page-status">{{ __('Active Page') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <ul class="form-group mt-3 btn-group gx-2">
                                    <li>
                                        <button type="button" class="btn btn-lg btn-primary submit-settings" disabled="">
                                            <span class="spinner-border spinner-border-sm hide" role="status" aria-hidden="true"></span>
                                            <span>{{ blank($content) ? __('Save') : __('Update') }}</span>
                                        </button>
                                    </li>
                                    @if (data_get($content, 'trash')==1)
                                        <li><a href="javascript:void(0)" class="btn btn-lg btn-outline-danger btn-trans admin-action" data-action="delete" data-confirm="yes" data-redirect="yes">{{ __('Delete Page') }}</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                            @if (filled(data_get($content, 'translatedPage')) && data_get($content, 'trash') == 1)
                                <div class="col-12">
                                    <div class="divider md stretched"></div>
                                    <div class="notes">
                                        <ul>
                                            <li class="alert-note is-plain text-danger">
                                                <em class="icon ni ni-alert"></em>
                                                <p>{{ __("Deleting this page will cause in deleting all the translated versions of this page.") }}</p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="{{ asset('assets/css/editors/tinymce.css') }}">
    <script src="{{ asset('assets/js/libs/tinymce.js') }}"></script>
    <script type="text/javascript">
        const routes = { validate: "{{ route('admin.manage.pages.validate.slug') }}", delete: "{{ route('admin.manage.pages.delete', data_get($content, 'id')) }}" };
        const msgs = { delete: { title: "{{ __('Do you want to delete?') }}", btn: {cancel: "{{ __('Cancel') }}", confirm: "{{ __('Delete') }}"}, context: "{!! __("You cannot revert back this action, so please confirm that you want to delete the page.") !!}", custom: "danger", type: "warning" } };

        !(function (NioApp, $) {
            var $switch = $('.switch-editor-plain'), _editor = '.tinymce-editor';

            $switch.on('change', function () {
                if ($(this).is(':checked')) {
                    tinymce.EditorManager.remove();
                } else {
                    NioApp.Tinymce();
                }
            });
            
            NioApp.Tinymce = function () {
                if ($(_editor).exists()) {
                    tinymce.init({
                        selector: _editor,
                        content_css: true,
                        skin: false,
                        branding: false,
                        menubar: false,
                        height: 350,
                        plugins: ['lists table link media fullscreen code image'],
                        toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | styleselect fontsizeselect | table bullist numlist | outdent indent | forecolor backcolor | link image | fullscreen code removeformat',
                        setup: function (editor) {
                            editor.on('change', function () {
                                editor.save();
                                NioApp.Form.button($('.submit-settings'), true, false);
                            })
                        }
                    });
                }
            }
            NioApp.coms.docReady.push(NioApp.Tinymce);
        })(NioApp, jQuery);
    </script>
@endpush
