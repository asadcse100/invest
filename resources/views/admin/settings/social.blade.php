<div class="nk-block card card-bordered">
    <div class="card-inner">
        <form action="{{ route('admin.save.app.settings') }}" class="form-settings" method="POST">
            <h5 class="title">{{ __('Social Authentication') }}</h5>
            <div class="form-sets gy-3 wide-md">
                <div class="row g-3 align-center">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="form-label">{{ __('Enable Social Authentication') }}</label>
                            <span class="form-note">{{ __('Allow users to authenticate using social platform.') }}</span>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input class="switch-option-value" type="hidden" name="auth" value="{{ sys_settings('social_auth') ?? 'off' }}">
                                <input id="social-auth" type="checkbox" class="custom-control-input switch-option" data-switch="on" {{ sys_settings('social_auth', 'off') == 'on' ? ' checked=""' : '' }}>
                                <label for="social-auth" class="custom-control-label">{{ __('Enable') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-3 align-start">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="form-label">{{ __('Facebook API Credential') }}</label>
                            <span class="form-note">{{ __('Setup your API credential of app from facebook developer.') }}</span>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                            <div class="form-control-wrap">
                                <input type="text" name="facebook_id" value="{{ gss('social_facebook_id') }}" class="form-control"/>
                            </div>
                            <div class="form-note">
                                <strong>{{ __("API Client ID") }}</strong>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-control-wrap">
                                <input type="text" name="facebook_secret" value="{{ gss('social_facebook_secret') }}" class="form-control"/>
                            </div>
                            <div class="form-note">
                                <strong>{{ __("API Client Secret") }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-3 align-start">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="form-label">{{ __('Google API Credential') }}</label>
                            <span class="form-note">{{ __('Setup your credential from google developer consoles.') }}</span>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                            <div class="form-control-wrap">
                                <input type="text" name="google_id" value="{{ gss('social_google_id') }}" class="form-control"/>
                            </div>
                            <div class="form-note">
                                <strong>{{ __("API Client ID") }}</strong>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-control-wrap">
                                <input type="text" name="google_secret" value="{{ gss('social_google_secret') }}" class="form-control"/>
                            </div>
                            <div class="form-note">
                                <strong>{{ __("API Client Secret") }}</strong>
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
                            <input type="hidden" name="form_type" value="social-settings">
                            <input type="hidden" name="form_prefix" value="social">
                            <button type="button" class="btn btn-primary submit-settings" disabled="">
                                <span class="spinner-border spinner-border-sm hide" role="status"
                                    aria-hidden="true"></span>
                                <span>{{ __('Update') }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>