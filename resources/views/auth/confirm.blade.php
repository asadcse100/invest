@extends('auth.layouts.master')

@section('title', __('Registration Complete'))

@section('content')
<div class="card card-bordered">
    <div class="card-inner card-inner-lg">
        <div class="nk-block-head text-center">
            <h4 class="nk-block-title">{{ __('Registration completed successfully.') }}</h4>
        </div>
        <div class="nk-block-content text-center">
            <p><strong>{{ __('Thank you for signing up!') }}</strong></p>
            <p>{{ __('We just need you to verify your email address. Please check your inbox including spam folder for a verification mail.') }}</p>
            <div class="gap gap-md"></div>
            <a class="btn btn-lg btn-block btn-primary" href="{{ route('auth.login.form') }}">{{ __('Return to Login') }}</a>
        </div>
    </div>
</div>
@endsection
