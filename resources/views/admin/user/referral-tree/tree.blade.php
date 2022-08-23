<ul class="nk-tree-pr">
    @foreach(data_get($user, 'referrals', []) as $referral)
        <li class="nk-tree-cl" id="tree-id-{{ data_get($referral, 'referred.id') }}">
            @include('admin.user.referral-tree.user-info', ['user' => data_get($referral, 'referred')])
        </li>
    @endforeach
</ul>
