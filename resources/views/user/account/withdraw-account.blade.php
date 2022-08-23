@extends('user.layouts.master')

@section('title', __('Payment Accounts'))

@section('content')
    <div class="nk-content-body">
        <div class="nk-block-head">
            <div class="nk-block-head-content">
                <h2 class="nk-block-title fw-normal">{{__('Payment Accounts')}}</h2>
                <div class="nk-block-des">
                    <p>{{ __('You have full control to manage your own account setting.') }}</p>
                </div>
            </div>
        </div>
        <ul class="nk-nav nav nav-tabs">
            @include('user.account.nav-tab')
        </ul>
        <div class="nk-block">
            @if(blank($accounts))
                @if(!blank($wdMethods))
                <div class="alert alert-warning">
                    <div class="alert-cta flex-wrap flex-md-nowrap g-2">
                        <div class="alert-text">
                            <p class="mb-sm-1"><strong>{{ __("You have not added any withdraw account yet in your account.") }}</strong></p>
                            <p>{{ __("Please add the personal or company accounts that you'd like to withdraw funds.") }}</p>
                        </div>
                        <div class="alert-actions">
                            <ul class="gx-3 my-1 my-sm-0">
                                <li class="order-md-last dropdown">
                                    <a href="#" class="btn btn-warning" data-toggle="dropdown">{{ __('Add Account') }}</a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <ul class="link-list-opt no-bdr">
                                            @foreach($wdMethods as $method)
                                                <li>
                                                    <a href="javascript:void(0)" class="wd-new-account" data-action="{{ route('user.withdraw.account.'.data_get($method, 'slug').'.form') }}" data-modal="wdm-account">
                                                        <em class="icon ni {{ data_get($method, 'module_config.icon') }}"></em>
                                                        <span>{{ data_get($method, 'module_config.account') }}</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                @else
                <div class="card card-bordered">
                    <div class="card-inner">
                        <div class="nk-help">
                            <div class="nk-help-text">
                                <h5>{{ __('Unavailable withdraw service!') }}</h5>
                                <p class="text-soft mb-1 mt-2">{{ __('Sorry, at the moment our withdraw service is unavailable. Please check back soon to add account.') }}</p>
                                <p class="text-soft">{{ __('If you have any question please feel free to contact us.') }}</p>
                            </div>
                            @if (the_page('contact'))
                            <div class="nk-help-action">
                                <a href="{{ the_page('contact')->link }}" class="btn btn-outline-primary">{{ __('Contact Us') }}</a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            @else
            <div class="nk-block-head">
                <div class="nk-block-between-md g-3">
                    <div class="nk-block-head-content">
                        <h5 class="nk-block-title">{{ __('Your Accounts') }}</h5>
                        <div class="nk-block-des">
                            <p>{{ __('Below accounts that you’d like to withdraw funds.') }}</p>
                        </div>
                    </div>
                    @if(!blank($wdMethods))
                    <div class="nk-block-head-tools">
                        <div class="dropdown">
                            <a href="#" class="btn btn-primary" data-toggle="dropdown">{{ __('Add Account') }}</a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <ul class="link-list-opt no-bdr">
                                    @foreach($wdMethods as $method)
                                        <li>
                                            <a href="javascript:void(0)" class="wd-new-account" data-action="{{ route('user.withdraw.account.'.data_get($method, 'slug').'.form') }}" data-modal="wdm-account">
                                                <em class="icon ni {{ data_get($method, 'module_config.icon') }}"></em>
                                                <span>{{ data_get($method, 'module_config.account') }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @if(blank($wdMethods))
                <div class="alert alert-info alert-icon mb-3">
                    <em class="icon ni ni-alert-circle"></em> {{ __('Sorry, at the moment our withdraw service is unavailable so you can not add new account.') }}
                </div>
            @endif
            <div class="card card-bordered" id="wd-account-list">
                @include('user.account.withdrawable-account')
            </div>
            <div class="notes mt-4">
                <ul>
                    <li class="alert-note is-plain text-danger">
                        <em class="icon ni ni-alert-circle"></em>
                        <p>{{ __('Caution: Your updated information only effect on new withdraw request.') }}</p>
                    </li>
                    <li class="alert-note is-plain">
                        <em class="icon ni ni-info"></em>
                        <p>{{ __('You should enter your correct information for receiving payment.') }}</p>
                    </li>
                </ul>
            </div>
            @endif
        </div>
    </div>
@endsection

@push('modal')
    <div class="modal fade" id="wdm-account"></div>
@endpush

@push('scripts')
<script type="text/javascript">
    const msgs = { wdm: {title: "{!! __("Are you sure you want to delete?") !!}", context: "{!! __("The withdraw account will be deleted immediately. You can't undo this action. Are you sure you want to proceed?") !!}", btn: {confirm: "{{ __('Delete Account') }}", cancel: "{{ __('Cancel') }}" } } };
</script>
@endpush