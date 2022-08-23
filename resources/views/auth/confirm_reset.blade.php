@extends('auth.layouts.master')

@section('title', __('Password Reset Request'))

@section('content')
<div class="card card-bordered">
    <div class="card-inner card-inner-lg">
        <div class="nk-block-head">
            <h4 class="nk-block-title">{{ __('Password Recovery Request') }}</h4>
        </div>
        <div class="nk-block-content">
            <p class="lead">{{ __('We have emailed you instruction to reset your password. Please check your email.') }}</p>
            <div class="gap gap-md"></div>
            <a class="btn btn-lg btn-block btn-primary" href="{{ route('auth.login.form') }}">{{ __('Return to Home') }}</a>
        </div>
    </div>
</div>
@endsection
