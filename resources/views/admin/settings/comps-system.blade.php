@extends('admin.layouts.master')
@section('title', __('Components / System'))

@section('has-content-sidebar', 'has-content-sidebar')

@section('content-sidebar')
    @include('admin.settings.content-sidebar')
@endsection

@section('content')
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">{{ __('Components / System') }}</h3>
                    <p>{{ __('Manage your additional components on application.') }}</p>
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
                <form action="{{ route('admin.save.app.settings') }}" class="form-settings" method="POST" autocomplete="off">
                    <h5 class="title">{{ __('GDPR Compliance') }}</h5>
                    <p>{{ __("Add GDPR Compliance into your application and make your website compatible to GDPR related regulation.") }}
                    <div class="form-sets gy-3 wide-md">
                        <div class="row g-3 align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label" for="gdpr-enable">{{ __('Enable GDPR Compliance') }}</label>
                                    <span class="form-note">{{ __('Enable and display notice into website.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input class="switch-option-value" type="hidden" name="gdpr_enable" value="{{ sys_settings('gdpr_enable') ?? 'no' }}">
                                        <input id="gdpr-enable" type="checkbox" class="custom-control-input switch-option" 
                                               data-switch="yes"{{ (sys_settings('gdpr_enable', 'no') == 'yes') ? ' checked=""' : ''}}>
                                        <label for="gdpr-enable" class="custom-control-label">{{ __('Enable') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-top">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label" for="cookie-consent-text">{{ __('Cookie Consent Text') }}</label>
                                    <span class="form-note">
                                    	{{ __('You can customize the text of cookie consent banner.') }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <textarea class="form-control textarea-sm" id="cookie-consent-text" name="cookie_consent_text">{{ sys_settings('cookie_consent_text', "This website uses cookies. By continuing to use this website, you agree to their use. For details, please check our [[privacy]].") }}</textarea>
                                        <div class="form-note fs-12px font-italic mt-1">
                                            <p class="text-soft">{{ __('You can use these shortcut: :words', ['words' => '[[privacy]], [[terms]]']) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Cookie Banner Style') }}</label>
                                    <span class="form-note">{{ __('Set the cookie banner style and background color.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="row gy-3">
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <select name="cookie_banner_position" class="form-select">
                                                    <option value="bottom"{{ sys_settings('cookie_banner_position', 'bottom') == 'bottom' ? ' selected' : '' }}>{{ __('Bottom bar full width') }}</option>
                                                    <option value="bbox-left"{{ sys_settings('cookie_banner_position', 'bottom') == 'bbox-left' ? ' selected' : '' }}>{{ __('Bottom box on left') }}</option>
                                                    <option value="bbox-right"{{ sys_settings('cookie_banner_position', 'bottom') == 'bbox-right' ? ' selected' : '' }}>{{ __('Bottom box on right') }}</option>
                                                </select>
                                            </div>
                                            <div class="form-note"><strong>{{ __("Style & Position") }}</strong></div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <select name="cookie_banner_background" class="form-select">
                                                    <option value="dark" {{ sys_settings('cookie_banner_background', 'dark') == 'dark' ? 'selected' : '' }}>{{ __('Dark') }}</option>
                                                    <option value="light" {{ sys_settings('cookie_banner_background', 'dark') == 'light' ? 'selected' : '' }}>{{ __('Light') }}</option>
                                                </select>
                                            </div>
                                            <div class="form-note"><strong>{{ __("BG Color") }}</strong></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label" for="cookie-accept-btn-txt">{{ __('Button Text') }}</label>
                                    <span class="form-note">
                                    	{{ __('Set the label for accept and deny button.') }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <input class="form-control" id="cookie-accept-btn-txt" name="cookie_accept_btn_txt" value="{{ sys_settings('cookie_accept_btn_txt', 'I Agree') }}" type="text">
                                            </div>
                                            <div class="form-note"><strong>{{ __("Agree Button") }}</strong></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <input class="form-control" id="cookie-deny-btn-txt" name="cookie_deny_btn_txt" value="{{ sys_settings('cookie_deny_btn_txt', __('Deny')) }}" type="text">
                                            </div>
                                            <div class="form-note"><strong>{{ __("Deny Button") }}</strong></div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label" for="cookie-deny-btn">{{ __('Show Deny Button') }}</label>
                                    <span class="form-note">{{ __('Enable to allow users to deny the cookie consent.') }}<br>{{ __("Note: Analytics code block will be removed if user denied.") }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input class="switch-option-value" type="hidden" name="cookie_deny_btn" value="{{ sys_settings('cookie_deny_btn', 'no') ?? 'yes' }}">
                                        <input id="cookie-deny-btn" type="checkbox" class="custom-control-input switch-option" data-switch="yes"{{ (sys_settings('cookie_deny_btn', 'no') == 'yes') ? ' checked=""' : ''}}>
                                        <label for="cookie-deny-btn" class="custom-control-label">{{ __('Show') }}</label>
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
                                    <input type="hidden" name="form_type" value="{{ 'system-component-settings' }}">
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
        @include('admin.settings.social')
    </div>
@endsection
