<div class="modal-body modal-body-lg text-center">
    <div class="nk-modal">
        <em class="nk-modal-icon icon icon-circle icon-circle-xxl ni ni-check bg-success"></em>
        <h4 class="nk-modal-title title">{{ __('Deposit is being processed') }}!</h4>
        <div class="nk-modal-text">
            <p class="caption-text">{{ __('Your deposit will be processed once we receive your funds.') }}</p>
            <p class="sub-text-sm">{{ __('Check your transaction history for an update.') }}</p>
        </div>
        <div class="nk-modal-action-lg">
            <ul class="btn-group gx-4">
                <li><a href="javascript:void(0)" id="deposit-more" data-url="{{ route('deposit') }}"
                       class="btn btn-lg btn-mw btn-primary">{{ __('Deposit More') }}</a></li>
                <li><a href="{{ route('transaction.list') }}"
                       class="btn btn-lg btn-mw btn-secondary btn-dim">{{ __('Transaction') }}</a></li>
            </ul>
        </div>
    </div>
</div>
