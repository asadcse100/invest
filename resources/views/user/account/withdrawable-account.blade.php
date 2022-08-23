<div class="nk-data data-list">
    @foreach($accounts as $account)
        <div class="data-item">
            <div class="data-col">
                <span class="data-label">
                	{{ data_get($account, 'method_name') }} {{ (data_get($account, 'name')) ? '('.data_get($account, 'name').')' : __('(No label)') }}
                	<br>
                	<em class="small text-soft">{{ (data_get($account, 'last_used')) ? __('Last used at :date', ['date' => show_date(data_get($account, 'last_used'))]) : "" }}</em>
                </span>
                <span class="data-value">{!! data_get($account, 'account_details') !!}<br></span>
            </div>
            <div class="data-col data-col-end">
            	<a class="wd-view-account" href="javascript:void(0);" data-action="{{ route('user.withdraw.account.'.data_get($account, 'slug').'.edit', ['id' => the_hash(data_get($account, 'id')) ]) }}" data-modal="wdm-account"><span class="data-more"><em class="icon ni ni-forward-ios"></em></span></a>
            </div>
        </div>
    @endforeach
</div>
