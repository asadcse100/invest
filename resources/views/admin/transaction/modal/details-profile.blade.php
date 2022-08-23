@php 
   use \App\Enums\TransactionType as dTType;
   use \App\Enums\TransactionStatus as dTStatus;
   use \App\Enums\TransactionCalcType as dTCType;
@endphp
<div class="nk-block">
    <h4 class="title nk-modal-title">{{ __('User Profile') }} <a target="_blank" href="{{  route('admin.users.details', ['id' => $user->id, 'type' => 'personal']) }}" class="btn btn-round btn-sm btn-primary ml-2">{{ __('View Details') }}</a></h4>
    <div class="row gy-3">
        <div class="col-md-6">
            <span class="sub-text">{{ __('Current Balance') }}</span>
            <span class="caption-text fw-bold">{{ money($user->balance(AccType('main')), base_currency(), ['dp' => 'calc']) }}</span>
        </div>
        <div class="col-md-6">
            <span class="sub-text">{{ __('User ID') }}</span>
            <span class="caption-text fw-bold">{{ the_uid(data_get($user, 'id')) }}</span>
        </div>
    </div>
    <div class="divider md stretched"></div>
    <div class="row gy-3">
        <div class="col-md-6">
            <span class="sub-text">{{ __('Full Name') }}</span>
            <span class="caption-text fw-bold">{{ data_get($user, 'name') }}</span>
        </div>
        <div class="col-md-6">
            <span class="sub-text">{{ __('Email Address') }}</span>
            <span class="caption-text fw-bold">{{ str_protect(data_get($user, 'email')) }}</span>
        </div>
        <div class="col-md-6">
            <span class="sub-text">{{ __('Mobile Number') }}</span>
            <span class="caption-text fw-bold">{!! ($user->meta('profile_phone')) ? $user->meta('profile_phone') : '<em class="text-soft small">'.__('Not updated yet').'</em>' !!}</span>
        </div>
        <div class="col-md-6">
            <span class="sub-text">{{ __('Date of Birth') }}</span>
            <span class="caption-text fw-bold">{!! ($user->meta('profile_dob')) ? show_dob($user->meta('profile_dob')) : '<em class="text-soft small">'.__('Not updated yet').'</em>' !!}</span>
        </div>
        <div class="col-md-6">
            <span class="sub-text">{{ __('Country') }}</span>
            <span class="caption-text fw-bold">{!! ($user->meta('profile_country')) ? ucfirst($user->meta('profile_country')) : '<em class="text-soft small">'.__('Not updated yet').'</em>' !!}</span>
        </div>
    </div>
    <div class="divider md stretched"></div>
    <div class="row gy-3">
        <div class="col-md-6">
            <span class="sub-text">{{ __('Joining Date') }}</span>
            <span class="caption-text">{{ show_date(data_get($user, 'created_at'), true) }}</span>
        </div>
        <div class="col-md-6">
            <span class="sub-text">{{ __('Last Login') }}</span>
            <span class="caption-text">{!! show_date(data_get($user, 'last_login'), true) ?? '<em class="text-soft small">'.__('Not logged in yet').'</em>' !!}</span>
        </div>
    </div>
</div>
