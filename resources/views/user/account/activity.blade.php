@extends('user.layouts.master')

@section('title', __('Login Activity'))

@section('content')
    <div class="nk-content-body">
        <div class="nk-block-head">
            <div class="nk-block-head-content">
                <h2 class="nk-block-title fw-normal">{{ __('Login Activity') }}</h2>
                <div class="nk-block-des">
                    <p>{{ __('You have full control to manage your own account setting.') }}</p>
                </div>
            </div>
        </div>
        <ul class="nk-nav nav nav-tabs">
            @include('user.account.nav-tab')
        </ul>
        <div class="nk-block">
            <div class="nk-block-head">
                <div class="nk-block-between-md g-3">
                    <div class="nk-block-head-content">
                        <h5 class="nk-block-title">{{ __('Activity on your account') }}</h5>
                        <div class="nk-block-des">
                            <p>{{ __('Here is your last 20 login activities log.') }} <span class="text-soft"><em class="icon ni ni-info tipinfo" title="{{ __("Stored activities whenever you login into account.") }}"></em></span></p>
                        </div>
                    </div>
                    <div class="nk-block-head-content">
                        <a href="{{ route('account.activity.clear') }}" class="link link-danger clear-profile-log">{{ __('Clear log') }}</a>
                    </div>
                </div>
            </div>
            <div class="card card-bordered">
                <table class="table table-ulogs">
                    <thead class="thead-light">
                    <tr>
                        <th class="tb-col-os"><span class="overline-title">{{ __('Browser') }} <span class="d-sm-none">/ {{ __('IP') }}</span></span></th>
                        <th class="tb-col-ip"><span class="overline-title">{{ __('IP') }}</span></th>
                        <th class="tb-col-time"><span class="overline-title">{{ __('Time') }}</span></th>
                        <th class="tb-col-action"><span class="overline-title">&nbsp;</span></th>
                    </tr>
                    </thead>
                    <tbody>

                    @if(!blank($activities))
                        @foreach($activities as $activity)
                            <tr>
                                <td class="tb-col-os">{{ $activity->browser_with_platform }}</td>
                                <td class="tb-col-ip"><span class="sub-text">{{ $activity->ip }}</span></td>
                                <td class="tb-col-time">
                                    <span class="sub-text">{{ show_date($activity->session) }}  <span class="d-none d-sm-inline-block">{{ show_time($activity->session) }}</span></span>
                                </td>
                                <td class="tb-col-action">
                                    @if(!$loop->first)
                                        <a href="{{ route('account.activity.delete', ["id" => $activity->id]) }}" class="link-cross mr-sm-n1"><em class="icon ni ni-cross"></em></a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center" colspan="4">{{ __("No activity log found!") }}</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script type="text/javascript">
    const msgs = { logs: {title: "{{ __('Are you sure?') }}", context: "{{ __('Do you want to delete your login activity?') }}", btn: {confirm: "{{ __('Yes, Clear Log') }}", cancel: "{{ __('Cancel') }}"} } };
</script>
@endpush
