@extends('admin.layouts.master')
@section('title', __('Third-Party API Setting'))

@section('has-content-sidebar', 'has-content-sidebar')

@section('content-sidebar')
    @include('admin.settings.content-sidebar')
@endsection

@section('content')

	<div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">{{ __('Third-Party API') }}</h3>
                    <p>{{ __('Set third-party API credential to enable relevent feature.') }}</p>
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
        <div class="nk-block card card-bordered nk-block-mh">
            <div class="card-inner">
                <form action="{{ route('admin.save.app.settings') }}" class="form-settings" method="POST">
                    <div class="form-sets gy-3 wide-md">
                        <div class="row g-3 align-start">
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <label class="form-label" for="tawk-api">{{ __('Tawk API Key for Live Chat') }}</label>
                                    <span class="form-note">
                                    	{{ __('You can add the Tawk.to live chat widget to your website.') }} <br>
                                    	{!! __("If you don't have, get your API ID from :external website.", ['external' => '<a href="https://www.tawk.to/" target="_blank">Tawk.to</a>']) !!}
                                    </span>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" id="tawk-api" name="tawk_api_key" value="{{ sys_settings('tawk_api_key') }}">
                                    </div>
                                    <div class="form-note">
                                    	{{ __('Tawk Widget Key/ID. Example - 60e36b2...8649e0/1f9...4la') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="form-sets gy-3 wide-md">
                        <div class="row g-3 align-start">
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <label class="form-label" for="exratesapi-access-key">{{ __('ExRatesApi Access Key') }}</label>
                                    <span class="form-note">
                                        {{ __('To get live exchange rate from ExRatesApi.com.') }} <br><span class="text-primary">{{ __("Application has build in access key that integrate with API.") }}</span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" id="exratesapi-access-key" name="exratesapi_access_key" value="{{ sys_settings('exratesapi_access_key') }}" placeholder="{{ str_compact(get_ex_apikey(), '-xx-xx-', 8) }}">
                                    </div>
                                    <div class="form-note">
                                        {{ __('ExRatesAPI Access Key') }} / <span class="text-danger">{{ __("Do not change the key unless you've correct access key.") }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="form-sets gy-3 wide-md">
                        <div class="row g-3 align-start">
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <label class="form-label" for="recaptcha-sitekey">{{ __('Google reCaptcha v3') }}</label>
                                    <span class="form-note">
                                    	{{ __('To enable captcha on login, registration page.') }} <br>
                                    	{!! __('Get the API Key :external', ['external' => '<a href="https://www.google.com/recaptcha/admin" target="_blank">https://www.google.com/recaptcha/admin</a>']) !!}
                                    </span>

                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="form-group">
                                    <div class="form-control-wrap w-max-200px">
                                        <input type="number" class="form-control" id="recaptcha-score" min="2" max="10" name="recaptcha_score" value="{{ sys_settings('recaptcha_score', '6') }}">
                                    </div>
                                    <div class="form-note">
                                        {{ __('Score of bot (between 2 to 10).') }}</span>
                                    </div>
                                    <div class="form-control-wrap mt-2">
                                        <input type="text" class="form-control" id="recaptcha-sitekey" name="recaptcha_site_key" value="{{ sys_settings('recaptcha_site_key') }}">
                                    </div>
                                    <div class="form-note">
                                    	{{ __('reCaptcha Site Key') }}
                                    </div>
                                    <div class="form-control-wrap mt-2">
                                        <input type="text" class="form-control" id="recaptcha-seckey" name="recaptcha_secret_key" value="{{ sys_settings('recaptcha_secret_key') }}">
                                    </div>
                                    <div class="form-note">
                                    	{{ __('reCaptcha Secret Key') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-sets gy-3 wide-md">
                        <div class="row g-3">
                            <div class="col-lg-7 offset-lg-5">
                                <div class="form-group mt-2">
                                    @csrf
                                    <input type="hidden" name="form_type" value="api-credential">
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
