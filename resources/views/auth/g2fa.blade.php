@extends('auth.layouts.master')

@section('title', __('Two Factor Verification'))

@section('content')
    <div class="card card-bordered">
        <div class="card-inner card-inner-lg">
            <div class="nk-block-head text-center">
                <h4 class="nk-block-title">{{ __('Two Factor Verification') }}</h4>
            </div>
            <div class="nk-block-content text-center">
                @if (!empty($errors) && $errors->any()) 
                    <p class="alert alert-danger p-1 mb-2">{{ $errors->first() }}</p>
                @endif

                <p>{{ __("Your account has been configured to use two-factor authentication using google authenticator app.") }}</p>
                <p><strong>{{ __("Please enter your 6-digit code below to verify.") }}</strong></p>

                <form action="{{ route('auth.2fa') }}" method="post" class="form-validate is-alter">
                    <div class="form-group">
                        <div class="form-control-wrap mb-3">
                            <input type="text" name="g2fa_code" class="form-control" minlength="6" maxlength="8" placeholder="{{ __("Authentication code") }}" data-msg-required="{{ __('Required.') }}" data-msg-minlength="{{ __('Code must be 6 digit.') }}" data-msg-maxlength="{{ __('Code less then 8 digit.') }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        @csrf
                        <button class="btn btn-block btn-primary" >{{ __('Verify Code') }}</button>
                    </div>
                </form>
                <div class="gap gap-lg"></div>
                <p class="text-soft mx-auto w-max-250px">{!! __("Lost access to your authenticator app? Please feel free to :contact our team.", ['contact' => get_page_link('contact', __('contact')), 'email' => get_mail_link()]) !!}</p>
            </div>
        </div>
    </div>
@endsection
