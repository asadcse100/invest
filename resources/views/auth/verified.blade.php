@extends('auth.layouts.master')

@section('title', __('Verification Completed'))

@section('content')
<div class="card card-bordered">
    <div class="card-inner card-inner-lg">
        <div class="nk-block-head text-center">
            <h4 class="nk-block-title">{{ __('Congrats! You are Verified!') }}</h4>
        </div>
        <div class="nk-block-content text-center">
            <p><strong>{{ __('Thank you for your confirmation.') }}</strong></p>
            <p>{{ __("You have successfully verified your email address. Now you can login into your account and continue to use our platform.") }}
            <div class="gap gap-md"></div>
            <a class="btn btn-lg btn-block btn-primary" href="{{ route('auth.login.form') }}">{{ __('Login into Account') }}</a>
        </div>
    </div>
</div>
@endsection
