<form action="{{ route('admin.quick-setup.save') }}" class="form-settings" method="POST">
    <h5 class="title mb-3">{{ __('Website Information') }}</h5>
    <div class="form-sets">
        <div class="form-group">
            <label class="form-label">{{ __('Site Name') }}</label>
            <div class="form-control-wrap">
                <input type="text" class="form-control" name="site_name" value="{{ sys_settings('site_name') }}">
            </div>
            <span class="form-note">{{ __('Specify the name of your website.') }}</span>
        </div>
        <div class="form-group">
            <label class="form-label">{{ __('Site Email') }}</label>
            <div class="form-control-wrap">
                <input type="text" class="form-control" name="site_email" value="{{ sys_settings('site_email') }}">
            </div>
            <span class="form-note">{{ __('Specify the email address of your website.') }}</span>
        </div>
        <div class="form-group mt-2">
            <div class="d-flex justify-between align-center">
                <div class="action">
                    @csrf
                    <input type="hidden" name="form_next" value="mail">
                    <input type="hidden" name="form_type" value="website-setting">
                    <input type="submit" class="btn btn-primary submit-settings" value="{{ __('Update & Next') }}">
                </div>
            </div>
        </div>
    </div>
</form>