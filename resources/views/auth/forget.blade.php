@extends('auth.layouts.master')

@section('title', __('Forget Password'))

@section('content')
<div class="card card-bordered">
    <div class="card-inner card-inner-lg">
        <div class="nk-block-head">
            <div class="nk-block-head-content">
                <h4 class="nk-block-title">{{ __('Reset Password') }}</h4>
                <div class="nk-block-des">
                    <p>{{ __('If you forgot your password, well, then we’ll email you instructions to reset your password.') }}</p>
                </div>
            </div>
        </div>
        @include('auth.partials.error')
        <form action="{{ route('auth.forget') }}" method="POST" id="forgotPassword" class="form-validate is-alter">
            @csrf
            <div class="form-group">
                <div class="form-label-group">
                    <label class="form-label" for="user-email">{{ __('Email') }}<span class="text-danger"> &nbsp;*</span></label>
                </div>
                <div class="form-control-wrap">
                    <input name="email" value="{{ old('email') }}" type="text" class="form-control form-control-lg" id="user-email" placeholder="{{ __('Enter your email address') }}" required>
                </div>
            </div>
            @if(has_recaptcha())
                <input type="hidden" id="recaptcha" value="" name="recaptcha">
            @endif
            <div class="form-group">
                <button class="btn btn-lg btn-primary btn-block">{{ __('Send Reset Link') }}
                </button>
            </div>
        </form>
        <div class="form-note-s2 text-center pt-4">
            <a href="{{ route('auth.login') }}"><strong>{{ __('Return to login') }}</strong></a>
        </div>
    </div>
</div>
@endsection

@if (has_recaptcha())
@push('scripts')
<script>
    grecaptcha.ready(function() {
        grecaptcha.execute('{{recaptcha_key("site")}}', {action: 'password'}).then(function(token) {
            document.getElementById('recaptcha').value=token;
        });
    });
</script>
@endpush
@endif
