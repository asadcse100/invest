@extends('auth.layouts.master')

@section('title', __('Reset Password'))

@section('content')
<div class="card card-bordered">
    <div class="card-inner card-inner-lg">
        <div class="nk-block-head">
            <div class="nk-block-head-content">
                <h4 class="nk-block-title">{{ __('Reset Password') }}</h4>
            </div>
        </div>
        @include('auth.partials.error')
        <form method="POST" action="{{ route('auth.reset') }}" class="form-validate is-alter">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <div class="form-label-group">
                    <label class="form-label" for="default-01">{{ __('Email') }}<span class="text-danger"> &nbsp;*</span></label>
                </div>

                <div class="form-control-wrap">
                    <input type="email" name="email" class="form-control form-control-lg" id="default-01"
                           placeholder="{{ __('Enter your email address') }}" value="{{ $email ?? old('email') }}"
                           required autocomplete="email" autofocus>
                </div>
            </div>

            <div class="form-group">
                <div class="form-label-group">
                    <label class="form-label" for="password">{{ __('Passcode') }} <span class="text-danger"> &nbsp;*</span></label>
                </div>
                <div class="form-control-wrap">
                    <a href="#" class="form-icon form-icon-right passcode-switch" data-target="password">
                        <em class="passcode-icon icon-show icon ni ni-eye"></em>
                        <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                    </a>
                    <input type="password" name="password" class="form-control form-control-lg" id="password"
                           placeholder="{{ __('Enter your passcode') }}" required>

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">{{ __("Confirm Passcode") }} <span class="text-danger"> &nbsp;*</span></label>
                <div class="form-control-wrap">
                    <a href="#" class="form-icon form-icon-right passcode-switch" data-target="password_confirm">
                        <em class="passcode-icon icon-show icon ni ni-eye"></em>
                        <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                    </a>
                    <input type="password" name="password_confirmation" class="form-control form-control-lg"
                           id="password_confirm" placeholder="{{ __("Confirm your passcode") }}" required>
                </div>
            </div>

            <div class="form-group">
                <button class="btn btn-lg btn-primary btn-block">{{ __('Reset Password') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
