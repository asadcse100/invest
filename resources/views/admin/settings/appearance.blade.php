@extends('admin.layouts.master')
@section('title', __('Theme Customize'))

@section('has-content-sidebar', 'has-content-sidebar')

@section('content-sidebar')
    @include('admin.settings.content-sidebar')
@endsection

@section('content')
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between gx-2">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">{{ __('Theme Customize') }}</h3>
                    <p>{{ __('Customize website appearance such as color, theme & layout.') }}</p>
                </div>
                <div class="nk-block-head-content">
                    <ul class="nk-block-tools gx-1">
                        <li class="d-lg-none">
                            <a href="#" class="btn btn-icon btn-trigger toggle" data-target="pageSidebar"><em class="icon ni ni-menu-right"></em></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="nk-block card card-bordered">
            <div class="card-inner">
                    <h5 class="title">{{ __('Website Branding') }}</h5>
                    <p>{{ __("Upload your website logo that will show into user dashboard and public pages.") }}</p>

                    <div class="form-sets gy-3 wide-md h-150px">
                        <div class="row g-3 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Website Logo - Dark') }}</label>
                                    <span class="form-note">{{ __('The logo will display on light background.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-5">
                                @if(!empty(gss('website_logo_dark')) && !empty(preview_media(gss('website_logo_dark'))))
                                <div class="form-group mb-0 logo-preview">
                                    <label class="form-label overline-title">{{ __('Logo Preview') }}</label>
                                    <div class="d-flex p-3 bg-lighter align-center justify-center round-lg" data-height="70">
                                        <img class="img-fluid logo-img" src="{{ preview_media(gss('website_logo_dark')) }}" height="40">
                                    </div>
                                    <a href="javascript:void(0);" class="remove-logo mt-1 d-inline-block" >{{ __("Change") }}</a>
                                </div>
                                @endif
                                <div class="form-group mb-0 logo-upfile{{ empty(gss('website_logo_dark')) || empty(preview_media(gss('website_logo_dark')))  ? '' : ' collapse' }} ">
                                    <label class="form-label overline-title">{{ __('Upload Logo') }}</label>
                                    <div class="form-control-wrap">
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="logo_dark">
                                                <label class="custom-file-label" for="logo-dark">{{ __("Choose file") }}</label>
                                            </div>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-light btn-dim upload-logo" type="button">{{ __("Upload") }}</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-note">{{ __("Recomended dimensions 180x40 px.") }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-sets gy-3 wide-md h-150px">
                        <div class="row g-3 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Website Logo - Light') }}</label>
                                    <span class="form-note">{{ __('The logo will display on dark background.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-5">
                                @if(!empty(gss('website_logo_light')) && !empty(preview_media(gss('website_logo_light'))))
                                <div class="form-group mb-0 logo-preview">
                                    <label class="form-label overline-title">{{ __('Logo Preview') }}</label>
                                    <div class="d-flex p-3 bg-dark align-center justify-center round-lg" data-height="70">
                                        <img class="img-fluid logo-img" src="{{ preview_media(gss('website_logo_light')) }}" height="40">
                                    </div>
                                    <a href="javascript:void(0);"  class="remove-logo mt-1 d-inline-block" >{{ __("Change") }}</a>
                                </div>
                                @endif
                                <div class="form-group mb-0 logo-upfile{{ empty(gss('website_logo_light')) || empty(preview_media(gss('website_logo_light')))  ? '' : ' collapse' }} ">
                                    <label class="form-label overline-title">{{ __('Upload Logo') }}</label>
                                    <div class="form-control-wrap">
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="logo_light" id="logo-light">
                                                <label class="custom-file-label" for="logo-light">{{ __("Choose file") }}</label>
                                            </div>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-light btn-dim upload-logo" type="button">{{ __("Upload") }}</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-note">{{ __("Recomended dimensions 180x40 px.") }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-sets gy-3 wide-md h-150px">
                        <div class="row g-3 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Mail Template Logo - Dark') }}</label>
                                    <span class="form-note">{{ __('The logo will use in email template.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-5">
                                @if(!empty(gss('website_logo_mail')) && !empty(preview_media(gss('website_logo_mail'))))
                                <div class="form-group mb-0 logo-preview">
                                    <label class="form-label overline-title">{{ __('Logo Preview') }}</label>
                                    <div class="d-flex p-3 bg-lighter align-center justify-center round-lg" data-height="70">
                                        <img class="img-fluid logo-img" src="{{ preview_media(gss('website_logo_mail')) }}" height="40">
                                    </div>
                                    <a href="javascript:void(0);" class="remove-logo mt-1 d-inline-block" >{{ __("Change") }}</a>
                                </div>
                                @endif
                                <div class="form-group mb-0 logo-upfile{{ empty(gss('website_logo_mail')) || empty(preview_media(gss('website_logo_mail')))  ? '' : ' collapse' }} ">
                                    <label class="form-label overline-title">{{ __('Upload Logo') }}</label>
                                    <div class="form-control-wrap">
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="logo_mail" id="logo-mail">
                                                <label class="custom-file-label" for="logo-mail">{{ __("Choose file") }}</label>
                                            </div>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-light btn-dim upload-logo" type="button">{{ __("Upload") }}</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-note">{{ __("Recomended dimensions 180x40 px.") }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-sets gy-3 wide-md h-150px">
                        <div class="row g-3 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Website Retina Logo - Dark') }}</label>
                                    <span class="form-note">{{ __('The logo will display on light background.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-5">
                                @if(!empty(gss('website_logo_dark2x')) && !empty(preview_media(gss('website_logo_dark2x'))))
                                <div class="form-group mb-0 logo-preview">
                                    <label class="form-label overline-title">{{ __('Logo Preview') }}</label>
                                    <div class="d-flex p-3 bg-lighter align-center justify-center round-lg" data-height="70">
                                        <img class="img-fluid logo-img" src="{{ preview_media(gss('website_logo_dark2x')) }}" height="40">
                                    </div>
                                    <a href="javascript:void(0);" data-remove="dark" class="remove-logo mt-1 d-inline-block">{{ __("Change") }}</a>
                                </div>
                                @endif
                                <div class="form-group mb-0 logo-upfile{{ empty(gss('website_logo_dark2x')) || empty(preview_media(gss('website_logo_dark2x')))  ? '' : ' collapse' }} ">
                                    <label class="form-label overline-title">{{ __('Upload Logo') }}</label>
                                    <div class="form-control-wrap">
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="logo_dark2x" id="logo-dark2x">
                                                <label class="custom-file-label" for="logo-dark">{{ __("Choose file") }}</label>
                                            </div>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-light btn-dim upload-logo" type="button" >{{ __("Upload") }}</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-note">{{ __("Recomended dimensions 360x80 px.") }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-sets gy-3 wide-md h-150px">
                        <div class="row g-3 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Website Retina Logo - Light') }}</label>
                                    <span class="form-note">{{ __('The logo will display on dark background.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-5">
                                @if(!empty(gss('website_logo_light2x')) && !empty(preview_media(gss('website_logo_light2x'))))
                                <div class="form-group mb-0 logo-preview">
                                    <label class="form-label overline-title">{{ __('Logo Preview') }}</label>
                                    <div class="d-flex p-3 bg-dark align-center justify-center round-lg" data-height="70">
                                        <img class="img-fluid logo-img" src="{{ preview_media(gss('website_logo_light2x')) }}" height="40">
                                    </div>
                                    <a href="javascript:void(0)" data-remove="dark" class="remove-logo mt-1 d-inline-block">{{ __("Change") }}</a>
                                </div>
                                @endif
                                <div class="form-group mb-0 logo-upfile{{ empty(gss('website_logo_light2x')) || empty(preview_media(gss('website_logo_light2x')))  ? '' : ' collapse' }} ">
                                    <label class="form-label overline-title">{{ __('Upload Logo') }}</label>
                                    <div class="form-control-wrap">
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="logo_light2x" id="logo-light2x">
                                                <label class="custom-file-label" for="logo-light2x">{{ __("Choose file") }}</label>
                                            </div>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-light btn-dim upload-logo" type="button">{{ __("Upload") }}</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-note">{{ __("Recomended dimensions 360x80 px.") }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="nk-block card card-bordered">
            <div class="card-inner">
                <form action="{{ route('admin.save.app.settings') }}" class="form-settings" method="POST">
                    <h5 class="title">{{ __('Layout & Appearance') }}</h5>
                    <div class="form-sets gy-3 wide-md">
                        <div class="row g-3 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Public/Home Pages Layout') }}</label>
                                    <span class="form-note">{{ __('Set appearance of home and public pages.') }}</span>
                                </div>
                            </div>

                            <div class="col-md-7">
                                <div class="row gx-2 gy-2">
                                    <div class="col-6 col-md-5 col-xxl-4">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <select class="form-select" name="page_skin">
                                                    <option value="light"{{ (sys_settings('ui_page_skin', 'dark')=='light') ? ' selected' : '' }}>{{ __("Lighten") }}</option>
                                                    <option value="dark"{{ (sys_settings('ui_page_skin', 'dark')=='dark') ? ' selected' : '' }}>{{ __("Darken") }}</option>
                                                </select>
                                            </div>
                                            <div class="form-note mt-1 fw-bold text-secondary">{{ __("Color Mode") }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Auth Pages Layout') }}</label>
                                    <span class="form-note">{{ __('Set appearance of auth pages such as login/register.') }}</span>
                                </div>
                            </div>

                            <div class="col-md-7">
                                <div class="row gx-2 gy-2">
                                    <div class="col-6 col-md-5 col-xxl-4">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <select class="form-select" name="auth_skin">
                                                    <option value="light"{{ (sys_settings('ui_auth_skin', 'light')=='light') ? ' selected' : '' }}>{{ __("Lighten") }}</option>
                                                    <option value="dark"{{ (sys_settings('ui_auth_skin', 'light')=='dark') ? ' selected' : '' }}>{{ __("Darken") }}</option>
                                                    <option value="dark-alter"{{ (sys_settings('ui_auth_skin', 'light')=='dark-alter') ? ' selected' : '' }}>{{ __("Colored") }}</option>
                                                </select>
                                            </div>
                                            <div class="form-note mt-1 fw-bold text-secondary">{{ __("Color Mode") }}</div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-5 col-xxl-4">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <select class="form-select" name="auth_layout">
                                                    <option value="default"{{ (sys_settings('ui_auth_layout', 'default')=='default') ? ' selected' : '' }}>{{ __("Default - Centered") }}</option>
                                                    <option disabled value="aside"{{ (sys_settings('ui_auth_layout', 'default')=='aside') ? ' selected' : '' }}>{{ __("Aside - Regular") }}</option>
                                                    <option disabled value="aside-alt"{{ (sys_settings('ui_auth_layout', 'default')=='aside-alt') ? ' selected' : '' }}>{{ __("Aside - Alter") }}</option>
                                                </select>
                                            </div>
                                            <div class="form-note mt-1 fw-bold text-secondary">{{ __("Layout") }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="divider"></div>
                    <div class="form-sets gy-3 wide-md">
                        <div class="row g-3 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Admin Panel Theme') }}</label>
                                    <span class="form-note">{{ __('Set primary color skin of admin panel appearance.') }}</span>
                                </div>
                            </div>

                            <div class="col-md-7">
                                <div class="row gx-2 gy-2">
                                    <div class="col-6 col-md-5 col-xxl-4">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <select class="form-select" name="theme_mode_admin">
                                                    <option value="light"{{ (sys_settings('ui_theme_mode_admin', 'light')=='light') ? ' selected' : '' }}>{{ __("Light Only") }}</option>
                                                    <option{{ (!dark_theme('exist')) ? ' disabled' : '' }} value="dark"{{ (sys_settings('ui_theme_mode_admin')=='dark') ? ' selected' : '' }}>{{ __("Dark Only") }}</option>
                                                    <option{{ (!dark_theme('exist')) ? ' disabled' : '' }} value="both"{{ (sys_settings('ui_theme_mode_admin')=='both') ? ' selected' : '' }}>{{ __("Both Mode") }}</option>
                                                </select>
                                            </div>
                                            <div class="form-note mt-1 fw-bold text-secondary">{{ __("Color Mode") }}</div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-5 col-xxl-4">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <select class="form-select" name="theme_skin_admin">
                                                    <option value="default"{{ (sys_settings('ui_theme_skin_admin', 'default')=='default') ? ' selected' : '' }}>{{ __("Default Blue") }}</option>
                                                    <option value="royal"{{ (sys_settings('ui_theme_skin_admin')=='royal') ? ' selected' : '' }}>{{ __("Royal Blue") }}</option>
                                                    <option value="jade"{{ (sys_settings('ui_theme_skin_admin')=='jade') ? ' selected' : '' }}>{{ __("Jade Green") }}</option>
                                                    <option value="crimson"{{ (sys_settings('ui_theme_skin_admin')=='crimson') ? ' selected' : '' }}>{{ __("Crimson Red") }}</option>
                                                    <option value="tangerine"{{ (sys_settings('ui_theme_skin_admin')=='tangerine') ? ' selected' : '' }}>{{ __("Tangerine Orange") }}</option>
                                                    <option value="violet"{{ (sys_settings('ui_theme_skin_admin')=='violet') ? ' selected' : '' }}>{{ __("Violet Blue") }}</option>
                                                    <option value="tealblue"{{ (sys_settings('ui_theme_skin_admin')=='tealblue') ? ' selected' : '' }}>{{ __("Teal Blue") }}</option>
                                                    <option value="tealgreen"{{ (sys_settings('ui_theme_skin_admin')=='tealgreen') ? ' selected' : '' }}>{{ __("Teal Green") }}</option>
                                                    <option value="dodger"{{ (sys_settings('ui_theme_skin_admin')=='dodger') ? ' selected' : '' }}>{{ __("Dodger Blue") }}</option>
                                                    <option value="prussian"{{ (sys_settings('ui_theme_skin_admin')=='prussian') ? ' selected' : '' }}>{{ __("Prussian Blue") }}</option>
                                                    <option value="goldenrod"{{ (sys_settings('ui_theme_skin_admin')=='goldenrod') ? ' selected' : '' }}>{{ __("Goldenrod Brown") }}</option>
                                                    <option value="charcoal"{{ (sys_settings('ui_theme_skin_admin')=='charcoal') ? ' selected' : '' }}>{{ __("Charcoal Grey") }}</option>
                                                    <option value="cobalt"{{ (sys_settings('ui_theme_skin_admin')=='cobalt') ? ' selected' : '' }}>{{ __("Cobalt Blue") }}</option>
                                                    <option value="rosepink"{{ (sys_settings('ui_theme_skin_admin')=='rosepink') ? ' selected' : '' }}>{{ __("Rose Pink") }}</option>
                                                </select>
                                            </div>
                                            <div class="form-note mt-1 fw-bold text-secondary">{{ __("Color Theme") }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('User Panel Theme') }}</label>
                                    <span class="form-note">{{ __('Set primary color skin of user dashboard appearance.') }}</span>
                                </div>
                            </div>

                            <div class="col-md-7">
                                <div class="row gx-2 gy-2">
                                    <div class="col-6 col-md-5 col-xxl-4">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <select class="form-select" name="theme_mode">
                                                    <option value="light"{{ (sys_settings('ui_theme_mode', 'light')=='light') ? ' selected' : '' }}>{{ __("Light Only") }}</option>
                                                    <option{{ (!dark_theme('exist')) ? ' disabled' : '' }} value="dark"{{ (sys_settings('ui_theme_mode')=='dark') ? ' selected' : '' }}>{{ __("Dark Only") }}</option>
                                                    <option{{ (!dark_theme('exist')) ? ' disabled' : '' }} value="both"{{ (sys_settings('ui_theme_mode')=='both') ? ' selected' : '' }}>{{ __("Both Mode") }}</option>
                                                </select>
                                            </div>
                                            <div class="form-note mt-1 fw-bold text-secondary">{{ __("Color Mode") }}</div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-5 col-xxl-4">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <select class="form-select" name="theme_skin">
                                                    <option value="default"{{ (sys_settings('ui_theme_skin', 'default')=='default') ? ' selected' : '' }}>{{ __("Default Blue") }}</option>
                                                    <option value="royal"{{ (sys_settings('ui_theme_skin')=='royal') ? ' selected' : '' }}>{{ __("Royal Blue") }}</option>
                                                    <option value="jade"{{ (sys_settings('ui_theme_skin')=='jade') ? ' selected' : '' }}>{{ __("Jade Green") }}</option>
                                                    <option value="crimson"{{ (sys_settings('ui_theme_skin')=='crimson') ? ' selected' : '' }}>{{ __("Crimson Red") }}</option>
                                                    <option value="tangerine"{{ (sys_settings('ui_theme_skin')=='tangerine') ? ' selected' : '' }}>{{ __("Tangerine Orange") }}</option>
                                                    <option value="violet"{{ (sys_settings('ui_theme_skin')=='violet') ? ' selected' : '' }}>{{ __("Violet Blue") }}</option>
                                                    <option value="tealblue"{{ (sys_settings('ui_theme_skin')=='tealblue') ? ' selected' : '' }}>{{ __("Teal Blue") }}</option>
                                                    <option value="tealgreen"{{ (sys_settings('ui_theme_skin')=='tealgreen') ? ' selected' : '' }}>{{ __("Teal Green") }}</option>
                                                    <option value="dodger"{{ (sys_settings('ui_theme_skin')=='dodger') ? ' selected' : '' }}>{{ __("Dodger Blue") }}</option>
                                                    <option value="prussian"{{ (sys_settings('ui_theme_skin')=='prussian') ? ' selected' : '' }}>{{ __("Prussian Blue") }}</option>
                                                    <option value="goldenrod"{{ (sys_settings('ui_theme_skin')=='goldenrod') ? ' selected' : '' }}>{{ __("Goldenrod Brown") }}</option>
                                                    <option value="charcoal"{{ (sys_settings('ui_theme_skin')=='charcoal') ? ' selected' : '' }}>{{ __("Charcoal Grey") }}</option>
                                                    <option value="cobalt"{{ (sys_settings('ui_theme_skin')=='cobalt') ? ' selected' : '' }}>{{ __("Cobalt Blue") }}</option>
                                                    <option value="rosepink"{{ (sys_settings('ui_theme_skin')=='rosepink') ? ' selected' : '' }}>{{ __("Rose Pink") }}</option>
                                                </select>
                                            </div>
                                            <div class="form-note mt-1 fw-bold text-secondary">{{ __("Color Theme") }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Sidebar Color Mode') }}</label>
                                    <span class="form-note">{{ __('Set main sidebar color mode of your dashboard.') }}</span>
                                </div>
                            </div>

                            <div class="col-md-7">
                                <div class="row gx-2 gy-2">
                                    <div class="col-6 col-md-5 col-xxl-4">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <select class="form-select" name="sidebar_user">
                                                    <option value="white"{{ (sys_settings('ui_sidebar_user', 'white')=='white') ? ' selected' : '' }}>{{ __("White") }}</option>
                                                    <option value="darker"{{ (sys_settings('ui_sidebar_user', 'white')=='darker') ? ' selected' : '' }}>{{ __("Darker") }}</option>
                                                    <option value="lighter"{{ (sys_settings('ui_sidebar_user', 'white')=='lighter') ? ' selected' : '' }}>{{ __("Lighter") }}</option>
                                                    <option value="colored"{{ (sys_settings('ui_sidebar_user', 'white')=='colored') ? ' selected' : '' }}>{{ __("Colored") }}</option>
                                                </select>
                                            </div>
                                            <div class="form-note mt-1 fw-bold text-secondary">{{ __("User Panel") }}</div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-5 col-xxl-4">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <select class="form-select" name="sidebar_admin">
                                                    <option value="white"{{ (sys_settings('ui_sidebar_admin', 'darker')=='white') ? ' selected' : '' }}>{{ __("White") }}</option>
                                                    <option value="darker"{{ (sys_settings('ui_sidebar_admin', 'darker')=='darker') ? ' selected' : '' }}>{{ __("Darker") }}</option>
                                                    <option value="lighter"{{ (sys_settings('ui_sidebar_admin', 'darker')=='lighter') ? ' selected' : '' }}>{{ __("Lighter") }}</option>
                                                    <option value="colored"{{ (sys_settings('ui_sidebar_admin', 'darker')=='colored') ? ' selected' : '' }}>{{ __("Colored") }}</option>
                                                </select>
                                            </div>
                                            <div class="form-note mt-1 fw-bold text-secondary">{{ __("Admin Panel") }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-sets gy-3 wide-md">
                        <div class="row g-3">
                            <div class="col-md-7 offset-lg-5">
                                <div class="form-group mt-2">
                                    @csrf
                                    <input type="hidden" name="form_type" value="theme-customize">
                                    <input type="hidden" name="form_prefix" value="ui">
                                    <button type="button" class="btn btn-primary submit-settings" disabled="">
                                        <span class="spinner-border spinner-border-sm hide" role="status" aria-hidden="true"></span>
                                        <span>{{ __('Update') }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script type="text/javascript">

    $('.remove-logo').on('click',function(e){
        $(this).parent().hide().next().show();
        e.preventDefault();
    })

    $('.upload-logo').on('click',function(){
        let formData = new FormData();
        let parent = $(this).parent();
        let file_name = parent.prev().find('input')[0].name;
        let file = parent.prev().find('input')[0].files;
        if (file.length > 0) {
            formData.append(file_name, file[0]); 
            new Promise(() => {
                file_upload(formData);
            })
            .then(() => {
                formData.delete(file_name);
            });          
        } else {
            NioApp.Toast("{{ __("Please choose a file to upload.") }}", 'warning');
        }
    });
    

    function file_upload(formData){
         $.ajax({
            url : "{{ route('admin.save.website.brands') }}",
            type : 'POST', data : formData, processData: false, contentType: false,
            success : function(res) {
                if(res.success){
                    NioApp.Toast(res.success, 'success');
                    if(res.reload) {
                        setTimeout(function(){ location.reload(); }, 900);
                    }
                }
                else if(res.error){
                    NioApp.Toast(res.error, 'warning');
                    if (res.reload) {
                        setTimeout(function(){ location.reload(); }, 900);
                    }
                }
                else if (res.errors) {
                    NioApp.Form.errors(res, true);
                }
            },
            error: function(data) {
                NioApp.Toast("{{ __("Sorry, something went wrong! Please reload the page and try again.") }}", 'warning');
            }
         })
    }

</script>
@endpush