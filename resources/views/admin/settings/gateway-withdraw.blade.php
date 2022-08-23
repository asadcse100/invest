@extends('admin.layouts.master')
@section('title', __('Withdraw Method'))

@section('has-content-sidebar', 'has-content-sidebar')

@section('content-sidebar')
    @include('admin.settings.content-sidebar')
@endsection

@section('content')
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">{{ __('Withdraw Methods') }}</h3>
                    <p>{{ __('Manage user withdraw method to withdraw their money.') }}</p>
                </div>
                <div class="nk-block-head-content">
                    <ul class="nk-block-tools gx-1">
                        <li class="d-lg-none">
                            <a href="javascript:void(0)" class="btn btn-icon btn-trigger toggle" data-target="pageSidebar"><em class="icon ni ni-menu-right"></em></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="nk-block card card-bordered nk-block-mh">
            <div class="card-inner">
                @if(is_demo())
                    <div class="alert alert-danger alert-dim mb-4">
                        {!! 'All the additional <span class="badge badge-pill badge-dark">Module</span> and <span class="badge badge-pill badge-danger">Add-ons</span> are NOT part of main product. Please feel free to <strong><a class="alert-link" href="'. the_link('softn' . 'io' .'.com' .'/'. 'contact'). '" target="_blank">contact us</a></strong> for more information or to get those.' !!}
                    </div>
                @endif
                @if(!blank($withdrawMethods))
                <ul class="cl-pm-list">
                    @foreach($withdrawMethods as $key => $method)
                    <li class="cl-pm-item">
                        <div class="cl-pm-desc">
                            <div class="cl-pm-icon"><em class="icon ni {{ data_get($method, 'full_icon') }}"></em></div>
                            <div class="cl-pm-info">
                                <h6 class="cl-pm-title">
                                    <a href="{{ has_route('admin.settings.gateway.withdraw.'.data_get($method, 'slug')) ? route('admin.settings.gateway.withdraw.'.data_get($method, 'slug')) : 'javascript:void(0)' }}">
                                        <span class="cl-pm-name">{{ data_get($method, 'name') }} @if(data_get($method, 'is_addon', false)) <span class="badge badge-pill badge-danger">{{ __('Add-ons') }}</span></span> @elseif(data_get($method, 'system.addons', false)) <span class="badge badge-pill badge-dark">{{ __('Module') }}</span> @endif
                                    </a>
                                </h6>
                                <div class="cl-pm-meta">
                                    <span class="meta-opt">
                                        <span class="text-soft">{{ __('Minimum Withdraw:') }}</span> <span>{{ money(data_get($methodDetail, data_get($method, 'slug').'.min_amount'), base_currency()) }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="cl-pm-btn">
                            @if(has_route('admin.settings.gateway.withdraw.'.data_get($method, 'slug')))
                                <ul class="btn-group gx-4">
                                    <li class="align-center ml-2">
                                        @if(data_get($methodDetail, data_get($method, 'slug').'.has_config'))
                                            <div class="custom-control custom-control-sm custom-switch">
                                                <input type="checkbox" class="custom-control-input dwm-quick-action" data-slug="{{ data_get($method, 'slug') }}" data-action="withdraw" id="wm-{{ Str::slug(data_get($method, 'name')) }}"{{ (data_get($methodDetail, data_get($method, 'slug').'.status') == 'active') ? ' checked': '' }}>
                                                <label class="custom-control-label text-soft" for="wm-{{ Str::slug(data_get($method, 'name')) }}">{{ __('Enable') }}</label>
                                            </div>
                                        @endif
                                    </li>

                                    <li>
                                        <a href="{{ route('admin.settings.gateway.withdraw.'.data_get($method, 'slug')) }}" class="btn btn-icon btn-trigger">
                                            <em class="icon ni ni-setting"></em>
                                        </a>
                                    </li>
                                </ul>
                            @endif
                        </div>
                    </li>
                    @endforeach
                </ul>
                @endif
                <div class="notes mt-4">
                    <ul>
                        <li class="alert-note is-plain text-danger">
                            <em class="icon ni ni-alert"></em>
                            <p>{{ __("Caution: If you disable any withdraw method then users unable to add new account using that method.") }}</p>
                        </li>
                        <li class="alert-note is-plain">
                            <em class="icon ni ni-info"></em>
                            <p>{{ __("Users can still access and update their existing account if any withdraw method is not enabled.") }}</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script type="text/javascript">
    const routes = { withdraw: "{{ route('admin.settings.gateway.quick') }}" }, msgs = { withdraw: { title: "{{ __('Are you sure?') }}", btn: {cancel: "{{ __('Cancel') }}", confirm: "{{ __('Yes') }}"}, context: "{!! __("Do you want to perform this action?") !!}", custom: "", type: "info" } };
</script>
@endpush