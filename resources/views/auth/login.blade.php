@extends('auth.layouts.master')

@section('title', !empty(sys_settings('login_seo_title')) ? sys_settings('login_seo_title') : __('Login'))

@section('content')
<div class="card card-bordered">
    <div class="card-inner card-inner-lg">
        <div class="nk-block-head">
            <div class="nk-block-head-content">
                <h4 class="nk-block-title">{{ __('Login into Account') }}</h4>
                <div class="nk-block-des mt-2">
                    <p>{{ __('Sign in into your account using your email and passcode.') }}</p>
                </div>
            </div>
        </div>
        @include('auth.partials.error')

        @if(session()->has('mail_sent_success'))
        <div class="alert alert-primary">
            <ul>
                <li class="alert-icon centered"><em class="icon ni ni-mail-fill"></em>{{ session()->get('mail_sent_success') }}</li>
            </ul>
        </div>
        @endif
        <form action="{{ route('auth.login') }}" autocomplete="off" method="POST" id="loginForm" class="form-validate is-alter">
            @csrf
            <div class="form-group">
                <div class="form-label-group">
                    <label class="form-label" for="username">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                </div>
                <div class="form-control-wrap">
                    <input name="email" type="email" autocomplete="new-email" class="form-control form-control-lg" id="username" placeholder="{{ __('Enter your email address') }}" autocomplete="off" data-msg-email="{{ __('Enter a valid email.') }}" data-msg-required="{{ __('Required.') }}" required>
                </div>
            </div>
            <div class="form-group">
                <div class="form-label-group">
                    <label class="form-label" for="passcode">{{ __('Password') }} <span class="text-danger">*</span></label>
                </div>
                <div class="form-control-wrap">
                    <a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch" data-target="passcode">
                        <em class="passcode-icon icon-show icon ni ni-eye-off"></em>
                        <em class="passcode-icon icon-hide icon ni ni-eye"></em>
                    </a>
                    <input name="password" autocomplete="new-password" type="password" class="form-control form-control-lg" id="passcode" placeholder="{{ __('Enter your passcode') }}" minlength="6" data-msg-required="{{ __('Required.') }}" data-msg-minlength="{{ __('At least :num chars.', ['num' => 6]) }}" required>
                </div>
                <div class="form-control-group d-flex justify-between mt-2 mb-gs">
                    <div class="form-control-wrap">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="remember" id="remember-me">
                            <label class="custom-control-label text-soft" for="remember-me">{{ __('Remember Me') }}</label>
                        </div>
                    </div>
                    <div class="form-control-link">
                        <a tabindex="5" class="link link-primary" href="{{ route('auth.forget.form') }}">{{ __('Forgot Code?') }}</a>
                    </div>
                </div>
            </div>
            @if(has_restriction())
            <div class="text-danger text-center font-italic small mb-1">
                {!! 'You are about to login into demo application to see the platform.' !!}
            </div>
            @endif
            <div class="form-group">
                @if(has_recaptcha())
                    <input type="hidden" id="recaptcha" value="" name="recaptcha">
                @endif
                <button class="btn btn-lg btn-primary btn-block">{{ __('Login') }}</button>
            </div>
        </form>
        @if(allowed_signup())
            <div class="form-note-s2 text-center pt-4"> {{ __('New on our platform?') }} <a href="{{ route('auth.register') }}"><strong>{{ __('Create an account') }}</strong></a>
            </div>
        @endif
        @include('auth.partials.social-auth', ['type' => 'login'])
        </div>
        @include('auth.partials.socials')
    </div>
</div>
@endsection


@if (has_recaptcha())
@push('scripts')
<script>
    grecaptcha.ready(function() {
        grecaptcha.execute('{{recaptcha_key("site")}}', {action: 'login'}).then(function(token) {
            document.getElementById('recaptcha').value=token;
        });
    });
</script>
@endpush
@endif
