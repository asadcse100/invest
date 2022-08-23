@extends('auth.layouts.master')

@section('title', !empty(sys_settings('registration_seo_title')) ? sys_settings('registration_seo_title') : __('Register'))

@section('content')
<div class="card card-bordered">
    <div class="card-inner card-inner-lg">
        <div class="nk-block-head">
            <div class="nk-block-head-content">
                <h4 class="nk-block-title">{{ __('Create an Account') }}</h4>
                <div class="nk-block-des mt-2">
                    @if($user_counts != 0)
                    <p>{{ __('Sign up with your email and get started with your free account.') }}</p>
                    @endif
                </div>
                @if($user_counts == 0)
                <div class="alert alert-fill alert-primary alert-icon mt-3">
                    <em class="icon ni ni-user"></em> {{ __("Register a regular admin account first.") }}
                </div>
                @endif
            </div>
        </div>
        @include('auth.partials.error')
        <form action="{{ route('auth.register') }}" autocomplete="off" method="POST" id="registerForm" class="form-validate is-alter" autocomplete="off">
            <div class="form-group">
                <label class="form-label" for="full-name">{{ __('Full Name') }}<span class="text-danger"> &nbsp;*</span></label>
                <div class="form-control-wrap">
                    <input type="text" id="full-name" name="name" value="{{ old('name') }}" class="form-control form-control-lg{{ ($errors->has('name')) ? ' error' : '' }}" minlength="3" data-msg-required="{{ __('Required.') }}" data-msg-minlength="{{ __('At least :num chars.', ['num' => 3]) }}" required>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label" for="email-address">{{ __('Email Address') }}<span class="text-danger"> &nbsp;*</span></label>
                <div class="form-control-wrap">
                    <input type="email" id="email-address" name="email" value="{{ old('email') }}" class="form-control form-control-lg{{ ($errors->has('email')) ? ' error' : '' }}" autocomplete="off" data-msg-email="{{ __('Enter a valid email.') }}" data-msg-required="{{ __('Required.') }}" required>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label" for="passcode">{{ __('Password') }}<span class="text-danger"> &nbsp;*</span></label>
                <div class="form-control-wrap">
                    <a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch" data-target="passcode">
                        <em class="passcode-icon icon-show icon ni ni-eye-off"></em>
                        <em class="passcode-icon icon-hide icon ni ni-eye"></em>
                    </a>
                    <input name="password" id="passcode" type="password" autocomplete="new-password" class="form-control form-control-lg" minlength="6" data-msg-required="{{ __('Required.') }}" data-msg-minlength="{{ __('At least :num chars.', ['num' => 6]) }}" required>
                </div>
            </div>
            @if(!empty(sys_settings('signup_form_fields')))
                @if(is_array(sys_settings('signup_form_fields')) && isset(sys_settings('signup_form_fields')['profile_phone']['show']) && sys_settings('signup_form_fields')['profile_phone']['show'] == 'yes')
                    <div class="form-group">
                        <label class="form-label" for="phone-number">{{ __('Phone Number') }} @if(sys_settings('signup_form_fields')['profile_phone']['req'] == 'yes') <span class="text-danger">&nbsp;*</span> @endif</label>
                        <div class="form-control-wrap">
                            <input type="text" name="profile_phone" class="form-control form-control-lg{{ $errors->has('profile_phone') ? ' error' : '' }}" id="phone-number" value="{{ old('profile_phone')  }}" @if(sys_settings('signup_form_fields')['profile_phone']['req'] == 'yes') required @endif>
                        </div>
                    </div>
                @endif
                @if(is_array(sys_settings('signup_form_fields')) && isset(sys_settings('signup_form_fields')['profile_dob']['show']) && sys_settings('signup_form_fields')['profile_dob']['show'] == 'yes')
                    <div class="form-group">
                        <label class="form-label" for="profile-dob">{{ __('Date of Birth') }} @if(sys_settings('signup_form_fields')['profile_dob']['req'] == 'yes') <span class="text-danger">&nbsp;*</span> @endif</label>
                        <div class="form-control-wrap">
                            <input type="text" name="profile_dob" data-date-start-date="-85y" data-date-end-date="-12y" class="form-control form-control-lg date-picker-alt{{ $errors->has('profile_dob') ? ' error' : '' }}" id="profile-dob" value="{{ old('profile_dob') }}" @if(sys_settings('signup_form_fields')['profile_dob']['req'] == 'yes') required @endif>
                        </div>
                    </div>
                @endif
                @if(is_array(sys_settings('signup_form_fields')) && isset(sys_settings('signup_form_fields')['profile_country']['show']) && sys_settings('signup_form_fields')['profile_country']['show'] == 'yes')
                    <div class="form-group">
                        <label class="form-label" for="country">{{ __('Country') }} @if(sys_settings('signup_form_fields')['profile_country']['req'] == 'yes') <span class="text-danger">&nbsp;*</span> @endif</label>
                        <div class="form-control-wrap">
                            <select name="profile_country" class="form-control form-control-lg form-select {{ $errors->has('profile_country') ? ' error' : '' }}" id="country" data-ui="lg" data-placeholder="{{ __('Please select any country') }}" data-search="on" @if(sys_settings('signup_form_fields')['profile_country']['req'] == 'yes') required @endif>
                                <option></option>
                                @foreach($countries as $code => $country)
                                    <option value="{{ $country }}"{{ old('profile_country') == $country ? ' selected' : '' }}>{{ config('countries')[$code] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif
            @endif
            @if ($user_counts != 0 && page_status('terms', true))
            <div class="form-group">
                <div class="custom-control custom-control-xs custom-checkbox">
                    <input type="checkbox" name="confirmation" class="custom-control-input{{ $errors->has('confirmation') ? ' error' : ''}}" id="checkbox" data-msg-required=" {{ __("You should accept our terms.") }}" required>
                    <label class="custom-control-label" for="checkbox">{!! __('I have agree to the :terms', ['terms' => get_page_link('terms', __("Terms & Condition"), true)]) !!}</label>
                </div>
            </div>
            @endif

            @if(has_restriction())
            <div class="text-danger text-center font-italic small mb-1">
                {!! 'You are about to register into demo application to see the platform.' !!}
            </div>
            @endif

            <div class="form-group">
                @csrf
                @if(has_recaptcha())
                    <input type="hidden" id="recaptcha" value="" name="recaptcha">
                @endif
                @if($user_counts == 0)
                <input type="hidden" name="confirmation" value="on">
                @endif
                <button class="btn btn-lg btn-primary btn-block">{{ __('Register') }}</button>
            </div>
        </form>
        @if($user_counts > 0)
        <div class="form-note-s2 text-center pt-4">
            {{ __('Already have an account?') }} <a href="{{ route('auth.login.form') }}"><strong>{{ __('Sign in instead') }}</strong></a>
        </div>
        @endif
        @include('auth.partials.social-auth', ['type' => 'signup'])
        @include('auth.partials.socials')
    </div>
</div>
@endsection

@if (has_recaptcha())
@push('scripts')
<script>
    grecaptcha.ready(function() {
        grecaptcha.execute('{{recaptcha_key("site")}}', {action: 'register'}).then(function(token) {
            document.getElementById('recaptcha').value=token;
        });
    });
</script>
@endpush
@endif
