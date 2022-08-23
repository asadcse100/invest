@extends('user.layouts.master')

@section('title', __('Deposit Funds'))

@section('content')
    <div class="nk-content-body">
        <div class="page-dw wide-xs m-auto" id="pms-ajcon">
            @if (!empty($errors) && is_array($errors))
                @include('user.transaction.error-state', $errors)
            @else
            <div class="nk-pps-apps">
                <div class="nk-pps-steps">
                    <span class="step active"></span>
                    <span class="step"></span>
                    <span class="step"></span>
                    <span class="step"></span>
                </div>
                <div class="nk-pps-title text-center">
                    <h3 class="title">{{ __('Deposit Funds') }}</h3>
                    <p class="caption-text">{{ __('Select from payment options below') }}</p>
                    <p class="sub-text-sm">{{ __('Secure and safely deposit money into your account.') }}</p>
                </div>
                <form class="nk-pps-form form-validate is-alter" action="{{ route('deposit.amount.form') }}" id="dpst-pm-frm" data-required_msg="{{ __('To deposit, please select a payment method.') }}">
                    <div class="nk-pps-field form-group">
                        <ul class="nk-pm-list" id="payment-option-list">
                            @foreach($activeMethods as $item)
                            <li class="nk-pm-item">
                                <input class="nk-pm-control" type="radio" name="deposit_method" required value="{{ data_get($item, 'slug') }}" id="{{ data_get($item, 'slug') }}" />
                                <label class="nk-pm-label" for="{{ data_get($item, 'slug') }}">
                                    <span class="pm-name">{{ __(data_get($item, 'name')) }}</span>
                                    <span class="pm-icon"><em class="icon ni {{ data_get($item, 'module_config.icon') }}"></em></span>
                                </label>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="nk-pps-field form-action text-center">
                        <div class="nk-pps-action">
                            <a href="javascript:void(0)" class="btn btn-lg btn-block btn-primary" id="pay-now">
                                <span>{{ __('Deposit Now') }}</span>
                                <span class="spinner-border spinner-border-sm hide" role="status" aria-hidden="true"></span>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>
@endsection
