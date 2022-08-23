<form action="{{ route('admin.quick-setup.save') }}" class="form-settings" method="POST">
    <h5 class="title mb-4">{{ __('Testing Email System') }}</h5>
    <div class="form-sets mt-4">
        <p><strong>{{ __('If you want to check wether your given email configuration is valid or not, then click on "Test Your Email" button to test.') }}</strong></p>
        <p>{{  __('Or else you can skip email testing and move forward.') }}</p>
        <div class="action mt-4">
            @csrf
            <input type="hidden" name="form_next" value="currencies">
            <input type="hidden" name="form_type" value="test-mail-setting">
            <input type="submit" class="btn btn-lg btn-primary submit-settings" value="{{ __('Send Test Email') }}">
        </div>
    </div>
    <div class="form-sets mt-5">
        <div class="d-flex justify-between align-center">
            <div class="action">
                <a class="link link-primary" href="{{ route('admin.quick-setup', ['step' => 'mail']) }}">{{ __("Back to Mail Setting") }}</a>
            </div>
            <div class="action">
                <a class="link link-primary" href="{{ route('admin.quick-setup', ['step' => 'currencies']) }}">{{ __("Skip & Next") }}</a>
            </div>
        </div>
    </div>
</form>