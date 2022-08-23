@section('title', __("Login Activities"))

<div class="nk-block-head">
    <h5 class="title">{{ __('Login Activities') }}</h5>
    <p>{{ __('The activities of login for the user.') }}</p>
</div>
<div class="nk-block is-stretch">
    <table class="table table-ulogs">
        <thead>
        <tr>
            <th class="tb-col-os"><span class="overline-title">{{ __('Browser') }} <span class="d-sm-none">/ {{ __('IP') }}</span></span></th>
            <th class="tb-col-ip"><span class="overline-title">{{ __('IP') }}</span></th>
            <th class="tb-col-time"><span class="overline-title">{{ __('Time') }}</span></th>
        </tr>
        </thead>
        <tbody>
        @forelse($user->activities as $activity)
            <tr>
                <td class="tb-col-os">{{ $activity->browser_with_platform }}</td>
                <td class="tb-col-ip"><span class="sub-text">{{ $activity->ip }}</span></td>
                <td class="tb-col-time">
                    <span class="sub-text">{{ show_date($activity->session) }}  <span class="d-none d-sm-inline-block">{{ show_time($activity->session) }}</span></span>
                </td>
            </tr>
        @empty
            <tr>
                <td class="text-center pt-4" colspan="3">{{ __("No activity log found!") }}</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@if($user->meta('last_clear_activity'))
<div class="notes mt-3 font-italic text-soft small">
    <p>{{  __('Last cleared log at :date', ['date' => show_date($user->meta('last_clear_activity'), true) ]) }}</p>
</div>
@endif
