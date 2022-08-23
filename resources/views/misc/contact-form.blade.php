@if (session('status'))
<div class="alert alert-pro alert-success mt-3">
    {{ session('status') }}
</div>
@endif
<form method="POST" action="{{ route('contact.form') }}" class="mt-5 form-public form-validate is-alter">
    <h4>{{ __("Support Form") }}</h4>
    <p>{{ __("Contact us using the contact form and we will respond immediately.") }}</p>
    <div class="row g-3">
        @if (!Auth::check())
        <div class="col-12">
            <div class="form-group">
                <label for="name" class="form-label">{{ __("Your Name") }} <span class="text-danger">*</span></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control form-control-lg @error('name') error @enderror" name="name" id="name" value="{{ old('name') }}">
                    @error('name')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                    
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="email" class="form-label">{{ __("Email Address") }} <span class="text-danger">*</span></label>
                <div class="form-control-wrap">
                    <input type="email" class="form-control form-control-lg @error('email') error @enderror" name="email" id="email" value="{{ old('email') }}" required>
                    @error('email')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                    
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="phone" class="form-label">{{ __("Phone Number") }}</label>
                <div class="form-control-wrap">
                     <input type="text" class="form-control form-control-lg @error('phone') error @enderror" name="phone" id="phone" value="{{ old('phone') }}">
                    @error('phone')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                    
                </div>
            </div>
        </div>
        @endif
        <div class="col-12">
            <div class="form-group">
                <label for="subject" class="form-label">{{ __("Subject") }} <span class="text-danger">*</span></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control form-control-lg @error('subject') error @enderror" name="subject" id="subject" value="{{ old('subject') }}" required>
                    @error('subject')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="message" class="form-label">{{ __("Message") }} <span class="text-danger">*</span></label>
                <div class="form-control-wrap">
                    <textarea name="message" class="form-control @error('message') error @enderror" id="message" placeholder="{{ __("Enter your message here...") }}" required>{{ old('message') }}</textarea>
                    @error('message')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group mt-1">
                @if(!Auth::check() && has_recaptcha())
                    <input type="hidden" id="recaptcha" value="" name="recaptcha">
                @endif
                @csrf
                <button class="btn btn-primary btn-lg btn-mw submit-form">
                    <span class="spinner-border spinner-border-sm hide" role="status" aria-hidden="true"></span>
                    <span>{{ __("Submit") }}</span>
                </button>
            </div>
        </div>  
    </div>
</form>

@if (!Auth::check() && has_recaptcha())
@push('scripts')
<script src="https://www.google.com/recaptcha/api.js?render={{recaptcha_key('site')}}"></script>
<script>
    grecaptcha.ready(function() {
        grecaptcha.execute('{{recaptcha_key("site")}}', {action: 'contact'}).then(function(token) {
            document.getElementById('recaptcha').value=token;
        });
    });
</script>
@endpush
@endif