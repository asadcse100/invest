<div class="card-inner p-0">
    <div class="nk-tb-list nk-tb-tnx {{ user_meta('tnx_display') == 'compact' ? 'is-compact': '' }}">
        <div class="nk-tb-item nk-tb-head">
            <div class="nk-tb-col"><span>{{ __('Details') }}</span></div>
            <div class="nk-tb-col tb-col-sm"><span>{{ __('Tnx By') }}</span></div>
            <div class="nk-tb-col tb-col-lg"><span>{{ __('Order') }}</span></div>
            <div class="nk-tb-col text-right"><span>{{ __('Amount') }}</span></div>
            <div class="nk-tb-col nk-tb-col-tools"></div>
        </div>
        @foreach($transactions as $transaction)
            <div class="nk-tb-item" id="order-id-{{ $transaction->tnx }}">
                @include('admin.transaction.trans-row', ['transaction' => $transaction])
            </div>
        @endforeach
    </div>
</div>
@if(filled($transactions))
<div class="card-inner pt-3 pb-3">
    {{ $transactions->appends(request()->all())->links('misc.pagination') }}
</div>
@endif