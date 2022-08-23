@extends('admin.layouts.minimal')
@section('title', __('Setup Completed'))

@section('content')
<div class="nk-content-body">
    <div class="nk-block wide-xs mx-auto">
        <div class="text-center">
            <em class="icon icon-circle icon-circle-xxl ni ni-check bg-success mt-md-4"></em>
            <div class="content">
                <span class="h5 fw-normal mb-3 mt-5 text-base d-block">{{ __("Completed Quick Setup!") }}</span>
                <h2 class="nk-block-title fw-normal">{{ __('Application setup completed!') }}</h2>
                <p class="caption-text mt-4">{{ __("You have successfully updated all the mendatory settings. You  should review your payment & withdraw methods.") }}</p>
            </div>
            <ul class="btn-group align-center justify-center gx-2 pt-5">
                <li><a href="{{ route('admin.settings.gateway.payment.list') }}" class="btn btn-lg btn-primary">{{ __('Payment Method') }}</a></li>
                <li><a href="{{ route('admin.settings.gateway.withdraw.list') }}" class="btn btn-lg btn-white btn-outline-primary">{{ __('Withdraw Method') }}</a></li>
            </ul>
            <ul class="btn-group-vertical align-center pt-4">
                <li><a href="{{ route('admin.dashboard') }}" class="link link-primary">{{ __('Go to Dashboard') }}</a></li>
            </ul>
        </div>
    </div>
</div>
@endsection