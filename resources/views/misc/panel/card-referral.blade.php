@php 

$type = isset($type) ? $type : false;

$card_id = (isset($attr['id']) && !empty($attr['id'])) ? ' id="'.$attr['id'].'"' : '';
$card_class = (isset($attr['class']) && !empty($attr['class'])) ? ' '.$attr['class'] : '';

@endphp

@if ($type=='cards')
<div class="card card-bordered"><div class="nk-refwg">
@endif

@if ($type == 'invite' || $type == 'invite-card' || $type == 'cards')

    @if($type == 'invite-card')
    <div class="nk-block"><div class="card card-bordered"><div class="card-inner">
    @endif

    <div class="nk-refwg-invite{{ $card_class }}"{{ $card_id }}>
        <div class="nk-refwg-head g-3">
            <div class="nk-refwg-title">
                <h5 class="title">{{ __(sys_settings('referral_invite_title', 'Refer Us & Earn')) }}</h5>
                @if(sys_settings('referral_invite_text'))
                <div class="title-sub">{{ __(sys_settings('referral_invite_text', 'Use the below link to invite your friends.')) }}</div>
                @endif
            </div>
            {{-- <div class="nk-refwg-action">
                <a href="#" class="btn btn-primary">{{__('Invite')}}</a>
            </div> --}}
        </div>
        <div class="nk-refwg-url">
            <div class="form-control-wrap">
                <div class="form-clip clipboard-init" data-clipboard-target="#ref-url" data-success="{{ __('Copied') }}" data-text="{{ __('Copy Link') }}"><em class="clipboard-icon icon ni ni-copy"></em> <span class="clipboard-text">{{ __("Copy Link") }}</span></div>
                <div class="form-icon">
                    <em class="icon ni ni-link-alt"></em>
                </div>
                <input type="text" class="form-control copy-text" id="ref-url" value="{{ route('auth.invite', ['ref' => get_ref_code(1)]) }}">
            </div>
        </div>
    </div>

    @if($type == 'invite-card')
    </div></div></div>
    @endif
@endif

@if ($type == 'stats' || $type == 'stats-card' || $type == 'cards')
    @if($type=='stats-card')
    <div class="nk-block"><div class="card card-bordered"><div class="card-inner">
    @endif

    <div class="nk-refwg-stats{{ $card_class. (($type=='cards') ? ' bg-lighter' : '') }}"{{ $card_id }}>
        <div class="nk-refwg-group g-3">
            <div class="nk-refwg-name">
                <h6 class="title">{{ __('My Referral') }} <em class="icon ni ni-info" data-toggle="tooltip" data-placement="right" title="{{ __('People who have signed up using your referral link.') }}"></em></h6>
            </div>
            <div class="nk-refwg-info g-3">
                <div class="nk-refwg-sub">
                    <div class="title">{{ $referrals['total'] ?? 0 }}</div>
                    <div class="sub-text">{{ __('Total Joined') }}</div>
                </div>
                <div class="nk-refwg-sub">
                    <div class="title">{{ $referrals['month'] ?? 0 }}</div>
                    <div class="sub-text">{{ __('This Month') }}</div>
                </div>
            </div>
            @if(has_route('referrals'))
            <div class="nk-refwg-more dropdown mt-n1 mr-n1">
                <a href="{{ route('referrals') }}" class="btn btn-icon btn-trigger"><em class="icon ni ni-more-h"></em></a>
            </div>
            @endif
        </div>
        @if(!empty($referrals['chart']))
        <div class="nk-refwg-ck">
            <canvas class="chart-refer-stats" id="referralchart"></canvas>
        </div>
        @endif
    </div>

    @if ($type == 'stats-card')
    </div></div></div>
    @endif
@endif

@if($type=='cards')
</div></div>
@endif