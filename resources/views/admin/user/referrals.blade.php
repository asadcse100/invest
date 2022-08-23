@section('title', __("Referral Activities"))

<div class="nk-block-head">
    <h5 class="title">{{ __('Referral Activities') }}</h5>
    <p>{{ __('The referral members tree view of this user.') }}</p>
</div>
<div class="nk-block is-stretch">
    <div class="nk-tree-cointainer" data-simplebar>
        <div class="nk-tree sm">
            <ul class="nk-tree-pr">
                <li class="nk-tree-cl">
                    @include('admin.user.referral-tree.user-info', ['user' => $user, 'treeCollapse' => false, 'first' => true])
                    @if(count(data_get($user, 'referrals', [])) > 0)
                        <ul class="nk-tree-pr">
                            @foreach(data_get($user, 'referrals', []) as $referral)
                                <li class="nk-tree-cl" id="tree-id-{{ data_get($referral, 'referred.id') }}">
                                    @include('admin.user.referral-tree.user-info', ['user' => data_get($referral, 'referred')])
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            </ul>
        </div>
    </div>
</div>

@push('scripts')
<script>
    !(function (App, $) {
        $(document).ready(function(){
            $(document).on('click', '.tree-collapse', function(){
                let elm = $(this);
                let plusIcon = elm.children('.ni-plus-circle-fill');
                let loader = elm.children('.spinner-border');
                if (elm.hasClass('is-clicked')) {
                    return;
                }

                elm.addClass('is-clicked');

                let id = $(this).data('user_id');
                let treeContainer = $('#tree-id-'+id);
                if (treeContainer.children(".nk-tree-pr").length === 0) {
                    plusIcon.addClass('d-none');
                    loader.removeClass('d-none');
                    let url = '{{ route('admin.users.user.refers') }}';
                    App.Form.toAjax(url, {"id": id}, {
                        "method": "GET",
                        "onSuccess": function (res) {
                            treeContainer.append(res);
                            App.Treetip('.uinfo-pop');
                            elm.removeClass('is-clicked');
                            loader.addClass('d-none');
                            plusIcon.removeClass('d-none');
                            plusIcon.removeClass('ni-plus-circle-fill');
                            plusIcon.addClass('ni-plus');
                        }
                    });
                }
            });
        });
    })(NioApp, jQuery);
</script>
@endpush
