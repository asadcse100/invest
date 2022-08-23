@extends('auth.layouts.master')

@section('title', __('Social Signup'))

@section('content')
<div class="card card-bordered">
    <div class="card-inner card-inner-lg">
        <div class="nk-block-head text-center">
            <h4 class="nk-block-title">{{ __('Signup with Social Account') }}</h4>
            <div class="nk-block-des mt-2">
                <p>{{ __('You can sign up with your social account and get started into our platform.') }}</p>
            </div>
        </div>
        <div class="nk-block-content">
            @if (!empty($errors) && $errors->any()) 
                <p class="alert alert-danger p-1 mb-2">{{ $errors->first() }}</p>
            @endif
            <p>{{ __("You are about to register using your :Social account.", ['social' => __($platform)]) }}</p>
            <form action="{{ route('auth.social.confirm', $platform) }}" method="post" class="form-validate is-alter">
                <div class="row gy-4">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">{{ __('Full Name') }}</label>
                            <input class="form-control" type="text" value="{{ data_get($data, 'name') }}" readonly />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">{{ __('Email Address') }}</label>
                            <input class="form-control" type="text" value="{{ data_get($data, 'email') }}" readonly />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="custom-control custom-control-xs custom-checkbox">
                            <input type="checkbox" name="confirmation" class="custom-control-input{{ $errors->has('confirmation') ? ' error' : ''}}" id="checkbox" data-msg-required=" {{ __("You should accept our terms.") }}" required>
                            <label class="custom-control-label" for="checkbox">{!! __('I have agree to the :terms', ['terms' => get_page_link('terms', __("Terms & Condition"), true)]) !!}</label>
                        </div>
                    </div>                    
                    <div class="col-md-12">
                        @csrf
                        <button class="btn btn-block btn-primary">{{ __('Confirm and Signup') }}</button>
                        <a href="{{ $redirect_url ?? route('auth.login.form') }}" class="btn btn-dim btn-block btn-danger">{{ __('Cancel') }}</a>
                    </div>   
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
