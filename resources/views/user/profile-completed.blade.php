@extends('user.layouts.welcome')

@section('title', __("Congratulations"))

@section('content')
<div class="nk-content-body">
    <div class="nk-block wide-xs mx-auto">
        <div class="text-center">
            <em class="icon icon-circle icon-circle-xxl ni ni-check bg-success mt-md-4"></em>
            <div class="content">
                <span class="h5 fw-normal mb-3 mt-5 text-base d-block">{{ __("Congratulations!") }}</span>
                <h2 class="nk-block-title fw-normal">{{ __('Your profile is complete!', ['fullname' => $user->name]) }}</h2>
                <p class="caption-text w-max-350px mx-auto">{{ __("You have successfully updated your profile. Now you can continue to use our platform.") }}</p>
            </div>
            <ul class="btn-group align-center justify-center gx-2 pt-5">
                <li><a href="{{ route('deposit') }}" class="btn btn-lg btn-primary">{{ __('Deposit Now') }}</a></li>
                <li><a href="{{ route('account.withdraw-accounts') }}" class="btn btn-lg btn-white btn-outline-primary">{{ __('Add Account') }}</a></li>
            </ul>
            <ul class="btn-group-vertical align-center pt-4">
                <li><a href="{{ route('dashboard') }}" class="link link-primary">{{ __('Go to Dashboard') }}</a></li>
            </ul>
        </div>
    </div>
</div>
@endsection
