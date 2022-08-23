@section('title', __("Personal Information"))

<div class="nk-block">
    <div class="nk-block-head nk-block-head-lg">
        <h5 class="title">{{ __('Personal Information') }}</h5>
        <p>{{ __('Basic info, like name and address etc that used on platform.') }}</p>
    </div>
    <div class="profile-ud-list">
        <div class="profile-ud-item">
            <h6 class="title overline-title text-base">{{ __('Basic Information') }}</h6>
            <div class="profile-ud wider">
                <span class="profile-ud-label">{{ __('Username') }}</span>
                <span class="profile-ud-value">{{ $user->username ?? '' }}</span>
            </div>
            <div class="profile-ud wider">
                <span class="profile-ud-label">{{ __('Email Address') }}</span>
                <span class="profile-ud-value">{{ str_protect($user->email) }}</span>
            </div>
            <div class="profile-ud wider">
                <span class="profile-ud-label">{{ __('Full Name') }}</span>
                <span class="profile-ud-value">{{ $user->name ?? '' }}</span>
            </div>
            <div class="profile-ud wider">
                <span class="profile-ud-label">{{ __('Display Name') }}</span>
                <span class="profile-ud-value">{!! ($user->meta('profile_display_name')) ? $user->meta('profile_display_name') : '<em class="text-soft small">'.__('Not updated yet').'</em>' !!}</span>
            </div>
            <div class="profile-ud wider">
                <span class="profile-ud-label">{{ __('Mobile Number') }}</span>
                <span class="profile-ud-value">{!! ($user->meta('profile_phone')) ? $user->meta('profile_phone') : '<em class="text-soft small">'.__('Not updated yet').'</em>' !!}</span>
            </div>
            <div class="profile-ud wider">
                <span class="profile-ud-label">{{ __('Date of Birth') }}</span>
                <span class="profile-ud-value">{!! ($user->meta('profile_dob')) ? show_dob($user->meta('profile_dob'), $user->id) : '<em class="text-soft small">'.__('Not updated yet').'</em>' !!}</span>
            </div>
            <div class="profile-ud wider">
                <span class="profile-ud-label">{{ __('Gender') }}</span>
                <span class="profile-ud-value">{!! ($user->meta('profile_gender')) ? __(ucfirst($user->meta('profile_gender'))) : '<em class="text-soft small">'.__('Not updated yet').'</em>' !!}</span>
            </div>
            @if (!empty($user->meta('profile_telegram')))
            <div class="profile-ud wider">
                <span class="profile-ud-label">{{ __('Telegram') }}</span>
                <span class="profile-ud-value">{{ $user->meta('profile_telegram') }}</span>
            </div>
            @endif
        </div>
        <div class="profile-ud-item">
            <h6 class="title overline-title text-base mt-4 mt-md-0">{{ __('Residential Address') }}</h6>
            <div class="profile-ud wider">
                <span class="profile-ud-label">{{ __('Address Line') }}</span>
                @if($user->meta('profile_address_line_1') || $user->meta('profile_address_line_2'))
                <span class="profile-ud-value">
                    {{ $user->meta('profile_address_line_1') }}
                    {!! ($user->meta('profile_address_line_1') && $user->meta('profile_address_line_2')) ? '<br>' : '' !!}
                    {{ $user->meta('profile_address_line_2') }}
                </span>
                @else
                <span class="profile-ud-value">
                    <em class="text-soft small">{{ __('Not updated yet') }}</em>
                </span>
                @endif
            </div>
            <div class="profile-ud wider">
                <span class="profile-ud-label">{{ __('City') }}</span>
                <span class="profile-ud-value">{!! ($user->meta('profile_city')) ? $user->meta('profile_city') : '<em class="text-soft small">'.__('Not updated yet').'</em>'  !!}</span>
            </div>
            <div class="profile-ud wider">
                <span class="profile-ud-label">{{ __('State / Province') }}</span>
                <span class="profile-ud-value">{!! ($user->meta('profile_state')) ? $user->meta('profile_state') : '<em class="text-soft small">'.__('Not updated yet').'</em>'  !!}</span>
            </div>
            <div class="profile-ud wider">
                <span class="profile-ud-label">{{ __('Zip / Postal Code') }}</span>
                <span class="profile-ud-value">{!! ($user->meta('profile_zip')) ? $user->meta('profile_zip') : '<em class="text-soft small">'.__('Not updated yet').'</em>'  !!}</span>
            </div>
            <div class="profile-ud wider">
                <span class="profile-ud-label">{{ __('Country') }}</span>
                <span class="profile-ud-value">{!! ($user->meta('profile_country')) ? $user->meta('profile_country') : '<em class="text-soft small">'.__('Not updated yet').'</em>'  !!}</span>
            </div>
            @if(!empty($user->meta('profile_nationality')))
            <div class="profile-ud wider">
                <span class="profile-ud-label">{{ __('Nationality') }}</span>
                <span class="profile-ud-value">{!! ($user->meta('profile_nationality') == 'same') ? '<em>'.__('Same as Above').'</em>' : $user->meta('profile_nationality') !!}</span>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="nk-block">
    <div class="nk-block-head nk-block-head-line">
        <h6 class="title overline-title text-base">{{ __('Additional Information') }}</h6>
    </div>
    <div class="profile-ud-list">
        <div class="profile-ud-item">
            <div class="profile-ud wider">
                <span class="profile-ud-label">{{ __('Join Date') }}</span>
                <span class="profile-ud-value">{{ show_date($user->created_at) }}</span>
            </div>
        </div>
        @if($user->meta('registration_method'))
        <div class="profile-ud-item">
            <div class="profile-ud wider">
                <span class="profile-ud-label">{{ __('Reg Method') }}</span>
                <span class="profile-ud-value">{{ __("By :Method", ['method' => ucfirst($user->meta('registration_method'))]) }}</span>
            </div>
        </div>
        @endif
        @if(data_get($user, 'refer'))
        <div class="profile-ud-item">
            <div class="profile-ud wider">
                <span class="profile-ud-label">{{ __('Referred By') }}</span>
                <span class="profile-ud-value">
                    <span class="small text-soft nk-tooltip" title="{{ the_uid(data_get($user, 'refer')) }}"><em class="icon ni ni-info"></em></span>
                    {{ str_protect(data_get($user->referrer, 'username')) }}
                </span>
            </div>
        </div>
        @endif
        @if($user->has_social_auth)
        <div class="profile-ud-item">
            <div class="profile-ud wider">
                <span class="profile-ud-label">{{ __('Connected With') }}</span>
                <span class="profile-ud-value">{{  ucfirst($user->connected_with) }}</span>
            </div>
        </div>
        @endif
        @if($user->meta('email_verified'))
        <div class="profile-ud-item">
            <div class="profile-ud wider">
                <span class="profile-ud-label">{{ __('Email Verified At') }}</span>
                <span class="profile-ud-value">{{ show_date($user->meta('email_verified'), true) }}</span>
            </div>
        </div>
        @endif
        @if($user->meta('kyc_verified_at'))
        <div class="profile-ud-item">
            <div class="profile-ud wider">
                <span class="profile-ud-label">{{ __('KYC Verified At') }}</span>
                <span class="profile-ud-value">{{ show_date($user->meta('kyc_verified_at'), true) }}</span>
            </div>
        </div>
        @endif
        @if($user->meta('setting_activity_log'))
        <div class="profile-ud-item">
            <div class="profile-ud wider">
                <span class="profile-ud-label">{{ __('Save Activity Logs') }}</span>
                <span class="profile-ud-value">{{ ($user->meta('setting_unusual_activity')=='on') ? __("Enabled") : __("Disabled") }}</span>
            </div>
        </div>
        @endif
        @if($user->meta('setting_unusual_activity'))
        <div class="profile-ud-item">
            <div class="profile-ud wider">
                <span class="profile-ud-label">{{ __('Email Unusual Activity') }}</span>
                <span class="profile-ud-value">{{ ($user->meta('setting_unusual_activity')=='on') ? __("Enabled") : __("Disabled") }}</span>
            </div>
        </div>
        @endif
    </div>
</div>
