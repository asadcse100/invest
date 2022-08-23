@extends('user.layouts.master')

@section('title', __('Withdraw Funds'))

@section('content')
    <div class="nk-content-body">
        <div class="page-dw wide-xs m-auto" id="wds-ajcon">
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
                        <h3 class="title">{{ __('Withdraw Funds') }}</h3>
                        <p class="caption-text">{{ __('Select from withdraw options below') }}</p>
                        <p class="sub-text-sm">{{ __('Withdraw funds from your account directly.') }}</p>
                    </div>
                    <form class="nk-pps-form" action="{{ route('withdraw.amount.form') }}" id="wd-method-frm" data-required_msg="{{ __('Please choose your withdraw method.') }}">
                        <div class="nk-pps-field form-group">
                            <ul class="nk-pm-list" id="wd-option-list">
                                @foreach($activeMethods as $item)
                                    <li class="nk-pm-item">
                                        <input class="nk-pm-control" type="radio" name="withdraw_method" required value="{{ data_get($item, 'slug') }}" id="{{ data_get($item, 'slug') }}" />
                                        <label class="nk-pm-label" for="{{ data_get($item, 'slug') }}">
                                            <span class="pm-name">{{ __(data_get($item, 'name')) }}</span>
                                            <span class="pm-icon"><em class="icon ni {{ data_get($item, 'module_config.icon') }}"></em></span>
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="nk-pps-field form-group">
                            <div class="form-label-group">
                                <label class="form-label">{{ __('Withdraw From')  }}</label>
                            </div>
                            <input type="hidden" value="{{ AccType('main') }}" name="wd_source" id="nk-pps-source-wdm">
                            <div class="dropdown nk-pps-dropdown">
                                <a href="#" class="dropdown-indicator is-single">
                                    <div class="nk-cm-item">
                                        <div class="nk-cm-text">
                                            <span class="label fw-bold">{{ w2n(AccType('main')) }}</span>
                                            <span class="desc">{{ __('Available Balance (:amount)', [ 'amount' => money($balance, base_currency()) ]) }}</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="nk-pps-field form-action text-center">
                            <div class="nk-pps-action">
                                <a href="javascript:void(0)" class="btn btn-lg btn-block btn-primary" id="withdraw-now">
                                    <span>{{ __('Withdraw Now') }}</span>
                                    <span class="spinner-border spinner-border-sm hide" role="status" aria-hidden="true"></span>
                                </a>
                            </div>
                        </div>
                        {{-- <div class="form-note text-base text-center">{!! __('Check out our withdraw :page.', ['page' => '<a href="#">'.__('processing fees').'</a>']) !!}</div> --}}
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('modal')
    <div class="modal fade" role="dialog" id="withdraw-account-modal">
    </div>
@endpush
