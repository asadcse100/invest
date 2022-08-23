@extends('auth.layouts.master')

@section('title', __('Email Verification'))

@section('content')
    <div class="card card-bordered">
        <div class="card-inner card-inner-lg">
            <div class="nk-block-head text-center">
                <h4 class="nk-block-title">{{ __('Verify your email address') }}</h4>
            </div>
            <div class="nk-block-content text-center">
                <p>{!! __('Your email address (:mail) has not been verified yet! In order to start using your account, you need to confirm your email address first.', ['mail' => '<strong>'. $email .'</strong>' ]) !!}</p>
                <p>{{ __("If you did not receive the email, click the button to resend.") }}</p>
                <form action="{{ route('auth.email.resend') }}" method="post">
                    <div class="form-group">
                        @csrf
                        <button class="btn btn-block btn-primary" >{{ __('Resend Email') }}</button>
                    </div>
                    <div class="form-note">
                        {{ __("For account security, we required to verified your email address.") }}
                    </div>
                </form>
                <div class="divider"></div>
                <p>{{ __("If you registered with wrong email address, update it now.") }}</p>
                <form action="{{ route('auth.email.change') }}" method="post" class="form-validate is-alter">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            @csrf
                            <input type="email" name="email" class="form-control" placeholder="{{ $email }}" data-msg-email="{{ __('Enter a valid email.') }}" data-msg-required="{{ __('Required.') }}" required>
                            @error('email')
                                <span class="error">{{ $errors->first('email') }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group mb-n3">
                        <ul class="btn-group-vertical align-center gy-3">
                            <li class="w-100"><button class="btn btn-block btn-light">{{ __('Update Email Address') }}</button></li>
                            <li><a class="link link-primary" href="{{ route('auth.logout') }}" onclick="event.preventDefault(); document.getElementById('quick-logout').submit();">{{ __('Sign Out') }}</a></li>
                        </ul>
                    </div>
                </form>
                <form id="quick-logout" action="{{ route('auth.logout') }}" method="POST" style="display: none;">@csrf</form>
            </div>
        </div>
    </div>
@endsection
