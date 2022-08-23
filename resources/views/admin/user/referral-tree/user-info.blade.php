@php
    $treeCollapse = $treeCollapse ?? true;
    $mainUser = $first ?? false;
    $members  = count(data_get($user, 'referrals', []));
@endphp

@if ($mainUser && $members == 0)
    <p class="p-4 border border-light rounded"><span class="icon ni ni-meh fs-22px text-soft"></span><br>{{ __("No one join yet!") }}</p>
@else
<div class="nk-tree-sb">
    <div class="user-pro-info">
        @if ($mainUser)
            {!! user_avatar($user) !!}
            <div class="user-info">
                <span class="lead-text">{{ $user->name }}</span>
            </div>
        @else
        <a class="uinfo-pop" href="{{ route('admin.users.details', ['id' => $user->id, 'type' => 'personal']) }}" target="_blank">
            {!! user_avatar($user, 'sm') !!}
            <div class="user-info">
                <span class="lead-text{{ ($user->status!='active') ? css_state($user->status, 'text') : '' }}">{{ $user->username }}</span>
            </div>
        </a>
        @endif

        @if (!$mainUser)
        <div class="uinfo-over">
            <div class="user-info">
                <span class="lead-text">
                    {{ $user->name }} 
                    @if ($user->status!='active') 
                        <span class="ml-1 badge badge-dot{{ css_state($user->status, 'badge') }}">&nbsp;</span>
                    @endif
                </span>
                <span class="sub-text">{{ the_uid($user->id) }} / {{$user->email }}</span>
                <span class="sub-text mt-1 lh-4">
                    {{ __("Joined Date") }}: <span>{{ show_date($user->created_at) }}</span> <br>
                    {{ __("Total Earned") }}:  <span>{{ $user->referral_bonus_earned }} {{ base_currency() }}</span> <br>
                    @if ($members)
                    {{ __("Total Referral") }}: <span>{{ ($members >= 2) ? __(":num Members", ['num' => $members]) : __(":num Member", ['num' => $members]) }}</span>
                    @endif
                </span>
            </div>
        </div>
        @endif

        @if($treeCollapse && ($members > 0))
        <div class="tree-collapse" data-user_id="{{ data_get($user, 'id') }}">
            <em class="icon ni ni-plus-circle-fill"></em>
            <em class="spinner-border spinner-border-sm d-none"></em>
        </div>
        @endif
    </div>
</div>
@endif