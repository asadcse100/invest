<div class="nk-block-head">
    <h4 class="nk-block-title">{{ __('Personal Information') }}</h4>
    <div class="nk-block-des">
        <p>{{ __('Basic info, like your name and address.') }}</p>
    </div>
</div>
<div class="nk-block">
    <div class="nk-data data-list data-list-s2 mt-n4">
        <div class="data-item" data-toggle="modal" data-target="#profile-edit">
            <div class="data-col">
                <span class="data-label">{{ __('Full Name') }}</span>
                <span class="data-value">{{ auth()->user()->name }}</span>
            </div>
            <div class="data-col data-col-end"><span class="data-more"><em class="icon ni ni-forward-ios"></em></span></div>
        </div>
        <div class="data-item" data-toggle="modal" data-target="#profile-edit">
            <div class="data-col">
                <span class="data-label">{{ __('Display Name') }}</span>
                <span class="data-value">{{ user_meta('profile_display_name') ?? '' }}</span>
            </div>
            <div class="data-col data-col-end"><span class="data-more"><em class="icon ni ni-forward-ios"></em></span></div>
        </div>
        <div class="data-item">
            <div class="data-col">
                <span class="data-label">{{ __('Email') }}</span>
                <span class="data-value">{{ auth()->user()->email }}</span>
            </div>
            <div class="data-col data-col-end"><span class="data-more disable"><em class="icon ni ni-lock-alt"></em></span></div>
        </div>
        <div class="data-item" data-toggle="modal" data-target="#profile-edit">
            <div class="data-col">
                <span class="data-label">{{ __('Phone Number') }}</span>
                <span class="data-value @if(empty(user_meta('profile_phone'))) text-soft @endif">{{ user_meta('profile_phone') ?? __('Not add yet') }}</span>
            </div>
            <div class="data-col data-col-end"><span class="data-more"><em class="icon ni ni-forward-ios"></em></span></div>
        </div>
        <div class="data-item" data-toggle="modal" data-target="#profile-edit">
            <div class="data-col">
                <span class="data-label">{{ __('Telegram') }}</span>
                <span class="data-value @if(empty(user_meta('profile_telegram'))) text-soft @endif">{{ user_meta('profile_telegram') ?? __('Not add yet') }}</span>
            </div>
            <div class="data-col data-col-end"><span class="data-more"><em class="icon ni ni-forward-ios"></em></span></div>
        </div>
        <div class="data-item" data-toggle="modal" data-target="#profile-edit">
            <div class="data-col">
                <span class="data-label">{{ __('Date of Birth') }}</span>
                <span class="data-value">{{ !empty(user_meta('profile_dob')) ? show_dob(user_meta('profile_dob')) : __('Not add yet') }}</span>
            </div>
            <div class="data-col data-col-end"><span class="data-more"><em class="icon ni ni-forward-ios"></em></span></div>
        </div>
        <div class="data-item" data-toggle="modal" data-target="#profile-edit" data-tab-target="#address">
            <div class="data-col">
                <span class="data-label">{{ __('Address') }}</span>
                <span class="data-value">
                                @if (!empty(user_meta('profile_address_line_1')))
                        {{ user_meta('profile_address_line_1').',' }}
                    @endif

                    @if (!empty(user_meta('profile_address_line_2')))
                        <br>{{ user_meta('profile_address_line_2') }}
                    @endif

                    @if (!empty(user_meta('profile_state')))
                        <br>{{ user_meta('profile_state') }}
                    @endif

                    @if (!empty(user_meta('profile_country')))
                        <br>{{ trans(user_meta('profile_country')) }}
                    @endif
                            </span>
            </div>
            <div class="data-col data-col-end"><span class="data-more"><em class="icon ni ni-forward-ios"></em></span></div>
        </div>
    </div>
    <div class="divider mt-2"></div>
    <h6 class="overline-title-alt mb-2">{{ __('Additional') }}</h6>
    <div class="row g-3">
        <div class="col-6 col-md-3">
            <span class="sub-text">{{ __('User ID:') }}</span>
            <span>{{ the_uid(auth()->user()->id) }}</span>
        </div>
        <div class="col-6 col-md-3">
            <span class="sub-text">{{ __('Last Login:') }}</span>
            <span>{{ !blank(auth()->user()->last_login) ? auth()->user()->last_login->format('j F Y') : '' }}</span>
        </div>
        <div class="col-6 col-md-3">
            <span class="sub-text">{{ __('Register At:') }}</span>
            <span>{{ !blank(auth()->user()->created_at) ? auth()->user()->created_at->format('j F Y') : '' }}</span>
        </div>
    </div>
</div>
