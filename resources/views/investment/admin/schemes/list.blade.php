@extends('admin.layouts.master')
@section('title', __('Investment - Manage Schemes'))

@php
    use App\Enums\SchemeStatus;
    use App\Enums\InterestRateType;
@endphp

@section('has-content-sidebar', '')

@section('content')
    @if (request()->method() == 'GET')
        {{ session(['ivlistStatus' => request('status')]) }}
    @endif
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between gx-2">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">{{ __('Manage Schemes') }}</h3>
                    <div class="nk-block-des text-soft">
                        <p>{{ __('Manage your investment plan that you want to offer.') }}</p>
                    </div>
                </div>
                <div class="nk-block-head-content">
                    <ul class="nk-block-tools g-3">
                        <li>
                            <a href="{{ route('admin.investment.list') }}" class="btn btn-white btn-dim btn-outline-gray d-none d-sm-inline-flex"><em class="icon ni ni-invest"></em><span>{{ __("Invested Plans") }}</span></a>
                            <a href="{{ route('admin.investment.list') }}" class="btn btn-icon btn-white btn-dim btn-outline-gray d-inline-flex d-sm-none"><em class="icon ni ni-invest"></em></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="btn btn-primary d-none d-sm-inline-flex m-ivs-scheme" data-action="scheme" data-view="modal"><em class="icon ni ni-plus"></em><span>{{ __('Add Scheme') }}</span></a>
                            <a href="javascript:void(0)" class="btn btn-icon btn-primary d-inline-flex d-sm-none m-ivs-scheme" data-action="scheme" data-view="modal"><em class="icon ni ni-plus"></em></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <ul class="nav nav-tabs mt-n2" id="scheme-list-nav">
            <li class="nav-item">
                <a class="nav-link{{ ((empty($status)) ? " active" : '') }}" href="{{ route('admin.investment.schemes') }}" data-tab_status="" id="iv-scheme"><span>{{ __('Scheme / Plan') }}</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link{{ (($status == 'inactive') ? " active" : '') }}" href="{{ route('admin.investment.schemes', SchemeStatus::INACTIVE) }}"><span>{{ __('Inactive') }}</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link{{ (($status == 'archived') ? " active" : '') }}" href="{{ route('admin.investment.schemes', SchemeStatus::ARCHIVED) }}"><span>{{ __('Archived') }}</span></a>
            </li>
        </ul>
        <div class="nk-block" id="ivpsl-ajcon">
            @include('investment.admin.schemes.cards', $schemes)
        </div>
    </div>
@endsection

@push('modal')
<div class="modal fade" role="dialog" id="ajax-modal"></div>
@endpush

@push('scripts')
<script type="text/javascript">
    const quick_update = "{{ route('admin.investment.scheme.status') }}",
        routes = {
            update: "{{ route('admin.investment.scheme.update') }}",
            scheme: "{{ route('admin.investment.scheme.action', 'new') }}", 
            edit: "{{ route('admin.investment.scheme.action', 'edit') }}",
            delete: "{{ route('admin.investment.scheme.action','delete') }}",
        },
        msgs = {
            update: {
                title: "{{ __('Are you sure?') }}", 
                btn: {cancel: "{{ __('Cancel') }}", confirm: "{{ __('Yes, Save Changes') }}"}, 
                context: "{!! __("Please confirm that you want to update the scheme as your changes will affect to new subscription.") !!}", 
                custom: "success", type: "info" 
            }
        },
        qmsg = { title: "{{ __('Are you sure?') }}", btn: {cancel: "{{ __('Cancel') }}", confirm: "{{ __('Confirm') }}"}, context: "{!! __("Do you want to perform this action?") !!}", action: {active: "{!! __("Do you want to active the investment scheme so users can purchase and invest on this plan?") !!}", inactive: "{!! __("Do you want to inactive the investment scheme? Once you confirmed, users cannot purchase the plan anymore.") !!}", archived: "{!! __("Do you want to archived the scheme? Once you confirmed, the plan will be hide cannot purchase the plan anymore.") !!}",delete:"{!! __("Do you want to delete the scheme? Once you confirmed, the plan will be deleted and cannot be purchased anymore.") !!}"} };

</script>
@endpush
