<div class="nk-block-head">
    <div class="nk-block-between-md g-3">
        <div class="nk-block-head-content">
            <h4 class="nk-block-title">{{ __('Login Activity') }}</h4>
            <div class="nk-block-des">
                <p>{{ __('Here is your last 20 login activities log.') }} <span class="text-soft"><em class="icon ni ni-info tipinfo" title="{{ __("Stored activities whenever you login into account.") }}"></em></span></p>
            </div>
        </div>
        <div class="nk-block-head-content">
            <a href="{{ route('admin.profile.activity.clear') }}" class="link link-danger clear-profile-log" data-action="ajax">{{ __('Clear log') }}</a>
        </div>
    </div>
</div>
<div class="nk-block">
    <table class="table table-plain table-ulogs">
        <thead>
        <tr>
            <th class="tb-col-os"><span class="overline-title">{{ __('Browser') }} <span class="d-sm-none">/ {{ __("IP") }}</span></span></th>
            <th class="tb-col-ip"><span class="overline-title">{{ __('IP') }}</span></th>
            <th class="tb-col-time"><span class="overline-title">{{ __('Time') }}</span></th>
            <th class="tb-col-action"><span class="overline-title">&nbsp;</span></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            @forelse($activities as $activity)
                <tr>
                    <td class="tb-col-os">{{ $activity->browser_with_platform }}</td>
                    <td class="tb-col-ip"><span class="sub-text">{{ $activity->ip }}</span></td>
                    <td class="tb-col-time">
                        <span class="sub-text">{{ show_date($activity->session) }}  <span class="d-none d-sm-inline-block">{{ show_time($activity->session) }}</span></span>
                    </td>
                    <td class="tb-col-action">
                        @if(!$loop->first)
                            <a href="{{ route('admin.profile.activity.delete', ["id" => $activity->id]) }}" class="link-cross mr-sm-n1 clear-profile-log" data-action="ajax"><em class="icon ni ni-cross"></em></a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="text-center" colspan="4">{{ __("No Activity Log Found !") }}</td>
                </tr>
            @endforelse
        </tr>
        </tbody>
    </table>
</div>
@if(user_meta('last_clear_activity'))
<div class="notes mt-3 font-italic text-soft small">
    <p>{{  __('Last cleared log at :date', ['date' => show_date(user_meta('last_clear_activity'), true) ]) }}</p>
</div>
@endif

@push('scripts')
<script type="text/javascript">
    const msgs = { logs: {title: "{{ __('Are you sure?') }}", context: "{{ __('Do you want to delete your login activity?') }}", btn: {confirm: "{{ __('Yes, Clear Log') }}", cancel: "{{ __('Cancel') }}"} } };
</script>
@endpush