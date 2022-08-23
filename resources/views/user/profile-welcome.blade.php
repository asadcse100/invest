@extends('user.layouts.welcome')

@section('title', __("Welcome"))

@section('content')
<div class="nk-content-body">
    <div class="nk-block-head nk-block-head-lg wide-xs mx-auto">
        <div class="nk-block-head-content text-center">
            <h2 class="nk-block-title fw-normal">{{ __('Nice, :fullname!', ['fullname' => $user->name]) }}</h2>
            <div class="nk-block-des">
                <p>{{ __("You are almost done, just few steps away to complete your profile.") }}<br class="d-none d-sm-block">
                    <strong>{{ __("Please complete your profile and continue!") }}</strong></p>
            </div>
        </div>
    </div>

    <div class="nk-block wide-xs mx-auto">
        <div class="card card-bordered">
            <div class="card-inner card-inner-lg">
                <form action="{{ route('account.profile.complete') }}" method="POST" class="form-validate is-alter form-profile" id="profile-update-form">  
                    <div class="row gy-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label" for="user-name">{{ __('Your Username') }}</label>
                                <div class="form-control-wrap">
                                    <div class="form-icon form-icon-right">
                                        <span class="spinner-border spinner-border-sm validate-username-loader hide" role="status"></span>
                                        <em class="icon ni validate-username-error hide" data-toggle="tooltip" title="{{ __('Invalid') }}"></em>
                                    </div>
                                    <input type="text" name="username" class="form-control form-control-lg validate-username{{ $errors->has('username') ? ' error' : '' }}" 
                                        id="user-name" value="{{ $user->username ? $user->username : old('username') }}" required>
                                        @error('username') 
                                            <span class="invalid">{{ $errors->first('username') }}</span>
                                        @enderror
                                </div>
                                <div class="form-note">{{ __("Set your username that you can use to login.") }}</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label" for="display-name">{{ __('Your Nick Name') }}</label>
                                <div class="form-control-wrap">
                                    <input type="text" name="profile_display_name" class="form-control form-control-lg 
                                        {{ $errors->has('profile_display_name') ? 'error' : '' }}" id="display-name" 
                                        value="{{ $user->meta('profile_display_name') ? $user->meta('profile_display_name') : old('profile_display_name')  }}" required>
                                     @error('profile_display_name')
                                        <span class="invalid">{{ $errors->first('profile_display_name') }}</span>
                                     @enderror
                                </div>
                            </div>
                            <div class="form-group mt-n2">
                                <div class="custom-control custom-control-sm custom-switch">
                                    <input type="checkbox" name="profile_display_full_name" class="custom-control-input" id="display-fullname"{{ ($user->meta('profile_display_full_name')=='off') ? ' checked' : '' }}>
                                    <label class="custom-control-label" for="display-fullname">{{ __('Use the nick name to display') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label" for="phone-number">{{ __('Phone Number') }}</label>
                                <div class="form-control-wrap">
                                    <input type="text" name="profile_phone" class="form-control form-control-lg{{ $errors->has('profile_phone') ? ' error' : '' }}" 
                                        id="phone-number" value="{{ $user->meta('profile_phone') ? $user->meta('profile_phone') :  old('profile_phone') }}" required>
                                     @error('profile_phone')
                                        <span class="invalid">{{ $errors->first('profile_phone') }}</span>
                                     @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row gy-3">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label" for="profile-dob">{{ __('Date of Birth') }}</label>
                                        <div class="form-control-wrap">
                                            <input type="text" name="profile_dob" data-date-start-date="-85y" data-date-end-date="-12y" class="form-control form-control-lg date-picker-alt{{ $errors->has('profile_dob') ? ' error' : '' }}" 
                                                id="profile-dob" value="{{ $user->meta('profile_dob') ? $user->meta('profile_dob') : old('profile_dob') }}" required>
                                            @error('profile_dob')
                                                <span class="invalid">{{ $errors->first('profile_dob') }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label" for="country">{{ __('Country') }} <em class="icon ni ni-info" data-toggle="tooltip" data-placement="right" title="{{ __('Your residential country') }}"></em></label>
                                        <div class="form-control-wrap">
                                            <select name="profile_country" class="form-select" id="country" data-ui="lg" data-placeholder="{{ __("Please select") }}" data-search="on" required>
                                                <option></option>
                                                @foreach($countries as $code => $country)
                                                    <option value="{{ $country }}"{{ ($user->meta('profile_country') == $country) ? ' selected' : (old('profile_country')==$country ? 'selected' : '')}}>{{ config('countries')[$code] }}</option>
                                                @endforeach
                                            </select>
                                            @error('profile_country')
                                                <span class="invalid">{{ $errors->first('profile_country') }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <ul class="gy-3 text-center pt-2">
                                <li>
                                    @csrf
                                    <button type="submit" class="btn btn-lg btn-block btn-primary">{{ __("Complete My Profile") }}</button>
                                </li>
                                <li class="mb-n3">
                                    <a href="{{ route('dashboard') }}" class="link d-block link-btn link-primary">{{ __("Go to Dashboard") }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    const routes = { validate: "{{ route('validate.username') }}" };
    @if (user_consent() === null)
    const consentURI = "{{ route('gdpr.cookie') }}";
    @endif
</script>
@endpush